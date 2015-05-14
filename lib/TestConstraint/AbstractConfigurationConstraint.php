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

namespace Scribe\Test\Symfony\Config\TestConstraint;

use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Scribe\Test\Symfony\Config\Definition\PartialProcessor;

/**
 * Class AbstractConfigurationConstraint.
 */
abstract class AbstractConfigurationConstraint extends \PHPUnit_Framework_Constraint
{
    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var string|null
     */
    protected $breadcrumbPath;

    /**
     * @param ConfigurationInterface $configuration
     * @param null                   $breadcrumbPath
     */
    public function __construct(ConfigurationInterface $configuration, $breadcrumbPath = null)
    {
        $this->configuration = $configuration;
        $this->breadcrumbPath = $breadcrumbPath;
        $this->exporter = new Exporter();

        parent::__construct();
    }

    /**
     * @param array $configurationValues
     *
     * @return array
     */
    protected function processConfiguration(array $configurationValues)
    {
        $processor = new PartialProcessor();

        return $processor->processConfiguration($this->configuration, $this->breadcrumbPath, $configurationValues);
    }

    /**
     * @param $configurationValues
     */
    protected function validateConfigurationValuesArray($configurationValues)
    {
        if (false === is_array($configurationValues)) {
            throw new \InvalidArgumentException('Configuration values should be an array');
        }

        foreach ($configurationValues as $values) {
            if (false === is_array($values)) {
                throw new \InvalidArgumentException('Configuration values should be an array of arrays');
            }
        }
    }
}

/* EOF */
