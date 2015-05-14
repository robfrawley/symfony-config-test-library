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

namespace Scribe\Test\Symfony\Config\Definition;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class PartialProcessor.
 */
class PartialProcessor
{
    /**
     * @param ArrayNode $node
     * @param string    $breadcrumbPath
     * @param array     $configs
     *
     * @return array
     */
    public function process(ArrayNode $node, $breadcrumbPath, array $configs)
    {
        PartialNode::excludeEverythingNotInBreadcrumbPath($node, $breadcrumbPath);

        $processor = new Processor();

        return $processor->process($node, $configs);
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param string                 $breadcrumbPath
     * @param array                  $configs
     *
     * @return array
     */
    public function processConfiguration(ConfigurationInterface $configuration, $breadcrumbPath, array $configs)
    {
        return $this->process($configuration->getConfigTreeBuilder()->buildTree(), $breadcrumbPath, $configs);
    }
}

/* EOF */
