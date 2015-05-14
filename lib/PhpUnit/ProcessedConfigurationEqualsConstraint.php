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

/**
 * Class ProcessedConfigurationEqualsConstraint.
 */
class ProcessedConfigurationEqualsConstraint extends AbstractConfigurationConstraint
{
    /**
     * @var array
     */
    private $configurationValues;

    /**
     * @param ConfigurationInterface $configuration
     * @param array                  $configurationValues
     * @param string|null            $breadcrumbPath
     */
    public function __construct(ConfigurationInterface $configuration, array $configurationValues, $breadcrumbPath = null)
    {
        $this->validateConfigurationValuesArray($configurationValues);
        $this->configurationValues = $configurationValues;

        parent::__construct($configuration, $breadcrumbPath);
    }

    /**
     * @param mixed  $other
     * @param string $description
     * @param bool   $returnResult
     *
     * @return mixed
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $processedConfiguration = $this->processConfiguration($this->configurationValues);

        $constraint = new \PHPUnit_Framework_Constraint_IsEqual($other);

        return $constraint->evaluate($processedConfiguration, '', $returnResult);
    }

    /**
     */
    public function toString()
    {
        // won't be used, this constraint only wraps \PHPUnit_Framework_Constraint_IsEqual
    }
}

/* EOF */
