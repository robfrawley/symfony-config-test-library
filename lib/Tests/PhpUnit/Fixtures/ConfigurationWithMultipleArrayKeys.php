<?php

/*
 * This file is part of the Scribe Cache Bundle.
 *
 * (c) Scribe Inc. <source@scribe.software>
 * (c) Matthias Noback <matthiasnoback@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Test\Symfony\Config\Tests\PhpUnit\Fixtures;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class ConfigurationWithMultipleArrayKeys.
 */
class ConfigurationWithMultipleArrayKeys implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('root');
        $rootNode
            ->children()
                ->arrayNode('array_node_1')
                    ->isRequired()
                    ->children()
                        ->scalarNode('required_value_1')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('array_node_2')
                    ->isRequired()
                    ->children()
                        ->scalarNode('required_value_2')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

/* EOF */
