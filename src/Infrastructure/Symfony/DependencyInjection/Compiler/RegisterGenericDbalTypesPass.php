<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Symfony\DependencyInjection\Compiler;

use Doctrine\DBAL\Types\Type;
use OpenSolid\Core\Infrastructure\Persistence\Doctrine\DBAL\Attribute\AsGenericType;
use OpenSolid\Core\Infrastructure\Persistence\Doctrine\ORM\Mapping\AutoMapGenericTypes;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterGenericDbalTypesPass implements CompilerPassInterface
{
    public function __construct(ContainerBuilder $container)
    {
        $container->registerAttributeForAutoconfiguration(AsGenericType::class, function (ChildDefinition $definition, AsGenericType $attribute, \ReflectionClass $reflector) {
            if (!$reflector->isSubclassOf(Type::class)) {
                throw new \LogicException(\sprintf('The class "%s" must extend "%s" to use the #[AsGenericType] attribute.', $reflector->getName(), Type::class));
            }

            $methodReturnType = $reflector->getMethod('convertToPHPValue')->getReturnType();

            if (!$methodReturnType instanceof \ReflectionNamedType) {
                throw new \LogicException(\sprintf('The method "%s::convertToPHPValue()" must have a named return type to use the #[AsGenericType] attribute.', $reflector->getName()));
            }

            if ($methodReturnType->isBuiltin()) {
                throw new \LogicException(\sprintf('The method "%s::convertToPHPValue()" must return a class type, got "%s".', $reflector->getName(), $methodReturnType->getName()));
            }

            $definition->addResourceTag('doctrine.dbal.type', [
                'super_class' => $methodReturnType->getName(),
            ]);
        });
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.dbal.connection_factory.types')) {
            return;
        }

        $typeDefinition = $container->getParameter('doctrine.dbal.connection_factory.types');

        $autoMapTypes = [];
        foreach ($container->findTaggedResourceIds('doctrine.dbal.type') as $type => $attributes) {
            $autoMapTypes[] = $superClass = $attributes[0]['super_class'] ?? throw new \LogicException(\sprintf('The "doctrine.dbal.type" tag for "%s" must have a "super_class" attribute.', $type));

            foreach ($container->getDefinitions() as $id => $definition) {
                if ($definition->isAbstract() || !\is_subclass_of($id, $superClass)) {
                    continue;
                }

                $typeDefinition[$id] = ['class' => $type];
            }
        }

        $container->getDefinition(AutoMapGenericTypes::class)->setArgument(0, $autoMapTypes);
        $container->setParameter('doctrine.dbal.connection_factory.types', $typeDefinition);
    }
}
