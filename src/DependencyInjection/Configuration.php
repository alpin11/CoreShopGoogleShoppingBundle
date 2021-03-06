<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('core_shop_google_shopping_bundle');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->scalarNode('host')->defaultNull()->end()
                ->scalarNode('protocol')->defaultValue('https')->end()
                ->scalarNode('cdn_enabled')->defaultFalse()->end()
                ->arrayNode('repositories')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('id')->cannotBeEmpty()->end()
                                ->integerNode('priority')->end()
                            ->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}