<?php

declare(strict_types=1);

namespace OpenSolid\Core\Infrastructure\Symfony\DependencyInjection\Compiler;

use OpenSolid\Core\Infrastructure\Symfony\Module\ModuleExtension;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass as BaseMergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MergeExtensionConfigurationPass extends BaseMergeExtensionConfigurationPass
{
    public function process(ContainerBuilder $container): void
    {
        $extensions = [];
        foreach ($container->getExtensions() as $extension) {
            $extensions[] = $extension->getAlias();
        }

        foreach (array_keys($container->getDefinitions()) as $class) {
            if (\class_exists($class) && \is_subclass_of($class, ModuleExtension::class)) {
                $container->registerExtension($extension = new $class());
                $extensions[] = $extension->getAlias();
            }
        }

        foreach ($extensions as $extension) {
            if (!\count($container->getExtensionConfig($extension))) {
                $container->loadFromExtension($extension, []);
            }
        }

        parent::process($container);
    }
}
