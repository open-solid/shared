<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Symfony\DependencyInjection\Compiler;

use OpenSolid\Core\Infrastructure\Persistence\Doctrine\DBAL\GenericType;
use OpenSolid\Core\Infrastructure\Persistence\Doctrine\ORM\Mapping\AutoMapGenericTypes;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterGenericDbalTypesPass implements CompilerPassInterface
{
    public function __construct(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(GenericType::class)
            ->addTag('doctrine.dbal.generic_type');
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.dbal.connection_factory.types')) {
            return;
        }

        /** @var array<string, array{class: string}> $typeDefinition */
        $typeDefinition = $container->getParameter('doctrine.dbal.connection_factory.types');

        $autoMapTypes = [];
        $types = $container->findTaggedResourceIds('doctrine.dbal.generic_type');
        foreach ($types as $type => $attributes) {
            $definition = $container->getDefinition($type);

            /** @var class-string $class */
            $class = $definition->getClass();

            $reflectorClass = new \ReflectionClass($class);

            if (!$reflectorClass->isSubclassOf(GenericType::class)) {
                throw new \LogicException(\sprintf('The class "%s" must extend "%s".', $class, GenericType::class));
            }

            $methodReturnType = $reflectorClass->getMethod('convertToPHPValue')->getReturnType();

            if (!$methodReturnType instanceof \ReflectionNamedType) {
                throw new \LogicException(\sprintf('The method "%s::convertToPHPValue()" must have a named return type.', $class));
            }

            $superClass = $methodReturnType->getName();

            if ($methodReturnType->isBuiltin() || !class_exists($superClass)) {
                throw new \LogicException(\sprintf('The method "%s::convertToPHPValue()" must return a class, got "%s".', $class, $superClass));
            }

            $autoMapTypes[] = $superClass;

            foreach ($container->getDefinitions() as $id => $definition) {
                if ($definition->isAbstract() || !\is_subclass_of($id, $superClass)) {
                    continue;
                }

                $typeDefinition[$id] = ['class' => $class];
            }
        }

        $container->getDefinition(AutoMapGenericTypes::class)->setArgument(0, $autoMapTypes);
        $container->setParameter('doctrine.dbal.connection_factory.types', $typeDefinition);
    }
}
