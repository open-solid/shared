<?php

namespace OpenSolid\Shared;

use OpenSolid\Shared\Infrastructure\Symfony\DependencyInjection\Compiler\RegisterGenericDbalTypesPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SharedBundle extends AbstractBundle
{
    protected string $extensionAlias = 'opensolid_shared';

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterGenericDbalTypesPass($container));
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }
    
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
    }
}