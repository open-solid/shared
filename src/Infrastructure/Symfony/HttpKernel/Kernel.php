<?php

namespace OpenSolid\Shared\Infrastructure\Symfony\HttpKernel;

use OpenSolid\Shared\Infrastructure\Symfony\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel
{
    protected function prepareContainer(ContainerBuilder $container): void
    {
        parent::prepareContainer($container);

        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass());
    }
}
