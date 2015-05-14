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
use Scribe\Test\Symfony\Config\Definition\Exception\ChildIsNotAnArrayNode;
use Scribe\Test\Symfony\Config\Definition\Exception\UndefinedChildNode;

/**
 * Class PartialNode.
 */
class PartialNode
{
    /**
     * @var \ReflectionProperty
     */
    private static $nodeChildrenProperty;

    /**
     * Provide an ArrayNode instance (e.g. the root node created by a TreeBuilder) and a path that is relevant to you,
     * e.g. "dbal.connections": this will strip every node that is not contained in the given path (e.g. the "orm" node
     * would be removed entirely.
     *
     * @param ArrayNode $node
     * @param string    $breadcrumbPath
     */
    public static function excludeEverythingNotInBreadcrumbPath(ArrayNode $node, $breadcrumbPath)
    {
        if ($breadcrumbPath === null) {
            return;
        }

        $path = explode('.', $breadcrumbPath);

        self::excludeEverythingNotInPath($node, $path);
    }

    /**
     * @param array $path
     */
    public static function excludeEverythingNotInPath(ArrayNode $node, array $path = [])
    {
        if (0 === count($path)) {
            return;
        }

        $nextNodeName = array_shift($path);

        $nextNode = self::childNode($node, $nextNodeName);

        if (!($nextNode instanceof ArrayNode)) {
            throw new ChildIsNotAnArrayNode($node, $nextNodeName);
        }

        $children = self::nodeChildrenProperty()->getValue($node);

        foreach ($children as $name => $child) {
            if ($name !== $nextNodeName) {
                unset($children[$name]);
            }
        }

        self::nodeChildrenProperty()->setValue($node, $children);
        self::excludeEverythingNotInPath($nextNode, $path);
    }

    /**
     * @param ArrayNode $node
     * @param string    $childNodeName
     *
     * @return mixed
     */
    private static function childNode(ArrayNode $node, $childNodeName)
    {
        $children = self::nodeChildrenProperty()->getValue($node);

        if (false === isset($children[$childNodeName])) {
            throw new UndefinedChildNode(
                $node,
                $childNodeName
            );
        }

        return $children[$childNodeName];
    }

    /**
     * @return \ReflectionProperty
     */
    private static function nodeChildrenProperty()
    {
        if (null === self::$nodeChildrenProperty || false === self::$nodeChildrenProperty) {
            self::$nodeChildrenProperty = new \ReflectionProperty(
                'Symfony\Component\Config\Definition\ArrayNode',
                'children'
            );

            self::$nodeChildrenProperty->setAccessible(true);
        }

        return self::$nodeChildrenProperty;
    }
}

/* EOF */
