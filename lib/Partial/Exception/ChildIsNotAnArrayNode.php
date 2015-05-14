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

namespace Scribe\Test\Symfony\Config\Partial\Exception;

use Symfony\Component\Config\Definition\BaseNode;

/**
 * Class ChildIsNotAnArrayNode.
 */
class ChildIsNotAnArrayNode extends AbstractInvalidNodeNavigationException
{
    /**
     * @param BaseNode $parentNode
     * @param int      $nodeName
     */
    public function __construct(BaseNode $parentNode, $nodeName)
    {
        parent::__construct(
            sprintf(
                'Child node "%s" is not an array node (current path: "%s")',
                $nodeName,
                $parentNode->getPath()
            )
        );
    }
}

/* EOF */
