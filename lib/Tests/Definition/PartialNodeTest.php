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

namespace Scribe\Test\Symfony\Config\Tests\Definition;

use Scribe\Test\Symfony\Config\Definition\PartialNode;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class PartialNodeTest.
 */
class PartialNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testItStripsChildrenThatAreNotInTheGivenPathWithOneName()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('root');
        $root
            ->children()
                ->arrayNode('node_1')
                    ->children()
                        ->scalarNode('node_1_scalar_node')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('node_2')
                    ->children()
                        ->scalarNode('node_2_scalar_node');

        $node = $treeBuilder->buildTree();
        /* @var ArrayNode $node */

        PartialNode::excludeEverythingNotInPath($node, ['node_2']);

        $this->nodeOnlyHasChild($node, 'node_2');
    }

    public function testItStripsChildrenThatAreNotInTheGivenPathWithServeralNames()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('root');
        $root
            ->children()
                ->arrayNode('node_1')
                    ->children()
                        ->arrayNode('node_1_a')
                            ->children()
                                ->scalarNode('scalar_node')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('node_1_b')
                            ->children()
                                ->scalarNode('scalar_node')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('node_2')
                    ->children()
                        ->scalarNode('scalar_node');

        $node = $treeBuilder->buildTree();
        /* @var ArrayNode $node */

        PartialNode::excludeEverythingNotInPath($node, ['node_1', 'node_1_b']);

        $node1 = $this->nodeOnlyHasChild($node, 'node_1');
        $this->nodeOnlyHasChild($node1, 'node_1_b');
    }

    public function testItFailsWhenARequestedChildNodeDoesNotExist()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('root');
        $root
            ->children()
                ->arrayNode('sub_node')
                    ->children()
                        ->arrayNode('sub_sub_tree');

        $node = $treeBuilder->buildTree();

        $this->setExpectedException(
            'Scribe\Test\Symfony\Config\Definition\Exception\UndefinedChildNode',
            'Undefined child node "non_existing_node" (the part of the path that was successful: "root.sub_node")'
        );
        PartialNode::excludeEverythingNotInPath($node, ['sub_node', 'non_existing_node']);
    }

    public function testItFailsWhenARequestedChildNodeIsNoArrayNodeItself()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('root');
        $root
            ->children()
                ->arrayNode('sub_node')
                    ->children()
                        ->scalarNode('scalar_node');

        $node = $treeBuilder->buildTree();

        $this->setExpectedException(
            'Scribe\Test\Symfony\Config\Definition\Exception\ChildIsNotAnArrayNode',
            'Child node "scalar_node" is not an array node (current path: "root.sub_node")'
        );
        PartialNode::excludeEverythingNotInPath($node, ['sub_node', 'scalar_node']);
    }

    private function nodeOnlyHasChild(ArrayNode $node, $nodeName)
    {
        $property = new \ReflectionProperty($node, 'children');
        $property->setAccessible(true);
        $children = $property->getValue($node);

        static::assertCount(1, $children);

        $firstChild = reset($children);
        static::assertSame($nodeName, $firstChild->getName());

        return $firstChild;
    }
}

/* EOF */
