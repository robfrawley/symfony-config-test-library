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

namespace Scribe\UnitTest\SymfonyConfig\Tests\Partial;

use Scribe\UnitTest\SymfonyConfig\Partial\PartialProcessor;
use Scribe\UnitTest\SymfonyConfig\Tests\Partial\Fixtures\ConfigurationStub;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class PartialProcessorTest.
 */
class PartialProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testItProcessesOnlyTheValuesInTheBreadcrumbPathForAGivenNode()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('root');
        $root
            ->children()
                ->arrayNode('only_test_this_node')
                    ->children()
                        ->scalarNode('scalar_node')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('ignore_this_node')
                    // this would normally trigger an error
                    ->isRequired()
                ->end()
            ->end();
        $node = $treeBuilder->buildTree();

        $partialProcessor = new PartialProcessor();

        $processedConfig = $partialProcessor->process($node, 'only_test_this_node', [
            [
                'only_test_this_node' => [
                    'scalar_node' => 'no'
                ]
            ],
            [
                'only_test_this_node' => [
                    'scalar_node' => 'yes'
                ]
            ]
        ]);

        static::assertSame(
            [
                'only_test_this_node' => [
                    'scalar_node' => 'yes'
                ]
            ],
            $processedConfig
        );
    }

    public function testItProcessesOnlyTheValuesInTheGivenBreadcrumbPathForAGivenConfigurationInstance()
    {
        $partialProcessor = new PartialProcessor();

        $processedConfig = $partialProcessor->processConfiguration(
            new ConfigurationStub(),
            'only_test_this_node',
            [
                [
                    'only_test_this_node' => [
                        'scalar_node' => 'yes'
                    ]
                ]
            ]
        );

        static::assertSame(
            [
                'only_test_this_node' => [
                    'scalar_node' => 'yes'
                ]
            ],
            $processedConfig
        );
    }
}

/* EOF */
