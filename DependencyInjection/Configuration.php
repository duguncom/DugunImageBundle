<?php

namespace Dugun\ImageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('dugun_image')
            ->children()
                ->variableNode('temporary_folder')->defaultValue('/tmp')->info('Give a folder that your client have access to write permission')->end()
                ->variableNode('watermark_position')->defaultValue('bottom')->end()
                ->variableNode('watermark_file')->defaultValue('')->info('Give your watermark image path')->end()
                ->variableNode('driver')->defaultValue('gd')->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
