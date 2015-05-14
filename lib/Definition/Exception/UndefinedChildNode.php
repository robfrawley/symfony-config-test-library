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

namespace Scribe\Test\Symfony\Config\Definition\Exception;

use Symfony\Component\Config\Definition\NodeInterface;

/**
 * Class UndefinedChildNode.
 */
class UndefinedChildNode extends AbstractInvalidNodeNavigationException
{
    /**
     * @param NodeInterface $parentNode
     * @param int           $childNodeName
     */
    public function __construct(NodeInterface $parentNode, $childNodeName)
    {
        parent::__construct(
            sprintf(
                'Undefined child node "%s" (the part of the path that was successful: "%s")',
                $childNodeName,
                $parentNode->getPath()
            )
        );
    }
}

/* EOF */
