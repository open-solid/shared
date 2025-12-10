<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html#configuration
 */
return static function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
            ->children()
                ->arrayNode('doctrine')
                    ->children()
                        ->arrayNode('orm')
                            ->children()
                                ->arrayNode('mapping')
                                    ->children()
                                        ->scalarNode('type')->defaultValue('xml')->end()
                                        ->scalarNode('relative_path')->defaultValue('/Infrastructure/Resources/config/doctrine/mapping/')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
    ;
};
