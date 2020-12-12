<?php

namespace Horoshii\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('horoshii_url_shortener');
        // BC layer for symfony/config < 4.2
        $rootNode = method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('horoshii_url_shortener');
        $rootNode
            ->children()
                ->integerNode('hash_min_length')->defaultValue(5)->setDeprecated('Url short hash min length')->end()
                ->scalarNode('hash_salt')->defaultValue('WERS#$%^3fDSw123')->setDeprecated('Url hash salt')->end()
                ->scalarNode('hash_alphabet')->defaultValue('abcdefghijklmnopqrstuvwxyz1234567890#$%^3fDSw123')->setDeprecated('Url hash alphabet')->end()
                ->integerNode('link_default_ttl')->defaultValue(60)->setDeprecated('Default link time life in minutes')->end()
                ->booleanNode('check_user_info')->defaultFalse()->end()
            ->end();
        return $treeBuilder;
    }
}