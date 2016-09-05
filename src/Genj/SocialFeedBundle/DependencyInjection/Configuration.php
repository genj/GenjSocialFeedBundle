<?php

namespace Genj\SocialFeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @package Genj\SocialFeedBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('genj_social_feed');

        $rootNode
            ->children()
                ->arrayNode('oAuth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('twitter')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('consumer_key')->isRequired()->end()
                                ->scalarNode('consumer_secret')->isRequired()->end()
                                ->scalarNode('user_token')->isRequired()->end()
                                ->scalarNode('user_secret')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('facebook')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('app_id')->isRequired()->end()
                                ->scalarNode('app_secret')->isRequired()->end()
                                ->scalarNode('client_token')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('instagram')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('client_id')->isRequired()->end()
                                ->scalarNode('access_token')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('feed_users')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('twitter')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('facebook')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('instagram')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
