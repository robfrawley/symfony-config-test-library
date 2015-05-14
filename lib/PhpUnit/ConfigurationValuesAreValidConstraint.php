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

namespace Scribe\Test\Symfony\Config\PhpUnit;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationValuesAreValidConstraint extends AbstractConfigurationConstraint
{
    /**
     * @param ConfigurationInterface $configuration
     * @param string|null            $breadcrumbPath
     */
    public function __construct(ConfigurationInterface $configuration, $breadcrumbPath = null)
    {
        parent::__construct($configuration, $breadcrumbPath);
    }

    /**
     * @param mixed $other
     *
     * @return bool
     */
    public function matches($other)
    {
        $this->validateConfigurationValuesArray($other);

        $success = true;

        try {
            $this->processConfiguration($other);
        } catch (InvalidConfigurationException $exception) {
            $success = false;
        }

        return $success;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'is valid for the given configuration';
    }
}

/* EOF */
