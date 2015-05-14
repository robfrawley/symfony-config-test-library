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

namespace Scribe\Test\Symfony\Config\TestCase;

use Scribe\Test\Symfony\Config\TestConstraint\ConfigurationValuesAreInvalidConstraint;
use Scribe\Test\Symfony\Config\TestConstraint\ConfigurationValuesAreValidConstraint;
use Scribe\Test\Symfony\Config\TestConstraint\ProcessedConfigurationEqualsConstraint;

/**
 * Trait ConfigurationTestCaseTrait.
 *
 * Add this trait to your Test Case to add the ability of testing your configuration
 * which should implement Symfony\Component\Config\Definition\ConfigurationInterface
 */
trait ConfigurationTestCaseTrait
{
    /**
     * Return the instance of ConfigurationInterface that should be used by the
     * Configuration-specific assertions in this test-case.
     *
     * @return \Symfony\Component\Config\Definition\ConfigurationInterface
     */
    abstract protected function getConfiguration();

    /**
     * Assert that the given configuration values are invalid.
     *
     * @param array       $configurationValues
     * @param string      $breadcrumbPath           The path that should be validated, e.g. "doctrine.orm"
     * @param string|null $expectedExceptionMessage
     * @param bool        $exceptionMessageAsRegex
     */
    protected function assertPartialConfigurationIsInvalid(array $configurationValues, $breadcrumbPath = null,
                                                           $expectedExceptionMessage = null,
                                                           $exceptionMessageAsRegex = false
    ) {
        \PHPUnit_Framework_TestCase::assertThat(
            $configurationValues,
            new ConfigurationValuesAreInvalidConstraint(
                $this->getConfiguration(),
                $expectedExceptionMessage,
                $exceptionMessageAsRegex,
                $breadcrumbPath
            )
        );
    }

    /**
     * Assert that the given configuration values are invalid.
     *
     * @param array       $configurationValues
     * @param string|null $expectedExceptionMessage
     * @param bool        $exceptionMessageAsRegex
     */
    protected function assertConfigurationIsInvalid(array $configurationValues, $expectedExceptionMessage = null,
                                                    $exceptionMessageAsRegex = false)
    {
        $this->assertPartialConfigurationIsInvalid(
            $configurationValues,
            null,
            $expectedExceptionMessage,
            $exceptionMessageAsRegex
        );
    }

    /**
     * Assert that the given configuration values are valid.
     *
     * @param array       $configurationValues
     * @param string|null $breadcrumbPath
     */
    protected function assertConfigurationIsValid(array $configurationValues, $breadcrumbPath = null)
    {
        \PHPUnit_Framework_TestCase::assertThat(
            $configurationValues,
            new ConfigurationValuesAreValidConstraint(
                $this->getConfiguration(),
                $breadcrumbPath
            )
        );
    }

    /**
     * Assert that the given configuration values, when processed, will equal to the given array.
     *
     * @param array       $configurationValues
     * @param array       $expectedProcessedConfiguration
     * @param string|null $breadcrumbPath
     */
    protected function assertProcessedConfigurationEquals(array $configurationValues, array $expectedProcessedConfiguration, $breadcrumbPath = null)
    {
        \PHPUnit_Framework_TestCase::assertThat(
            $expectedProcessedConfiguration,
            new ProcessedConfigurationEqualsConstraint(
                $this->getConfiguration(),
                $configurationValues,
                $breadcrumbPath
            )
        );
    }
}

/* EOF */
