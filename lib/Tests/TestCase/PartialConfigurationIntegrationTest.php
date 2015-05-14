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

namespace Scribe\Test\Symfony\Config\Tests\TestCase;

use Scribe\Test\Symfony\Config\TestCase\AbstractConfigurationTestCase;
use Scribe\Test\Symfony\Config\Tests\TestCase\Fixtures\ConfigurationWithMultipleArrayKeys;

class PartialConfigurationIntegrationTest extends AbstractConfigurationTestCase
{
    /**
     * @return ConfigurationWithMultipleArrayKeys
     */
    protected function getConfiguration()
    {
        return new ConfigurationWithMultipleArrayKeys();
    }

    public function testAssertConfigIsInvalid()
    {
        $this->assertPartialConfigurationIsInvalid(
            [
                [], // no configuration values
            ],
            'array_node_1',
            'array_node_1'
        );
    }

    public function testItFailsWhenConfigIsValidWhenItShouldHaveBeenInvalid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'invalid');

        $this->assertPartialConfigurationIsInvalid(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => 'some value',
                    ],
                ],
            ],
            'array_node_1'
        );
    }

    public function testAssertThatConfigIsValid()
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => 'some value',
                    ],
                ],
            ],
            'array_node_1'
        );
    }

    public function testItFailsWhenConfigIsInvalidWhenItShouldHaveBeenValid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'valid');

        $this->assertConfigurationIsValid(
            [
                [],
            ],
            'array_node_1'
        );
    }

    public function testAssertThatAProcessedConfigMatchesTheArrayOfValues()
    {
        $value = 'some value';

        $this->assertProcessedConfigurationEquals(
            [
                [],
                [
                    'array_node_1' => [
                        'required_value_1' => $value,
                    ],
                ],
            ],
            [
                'array_node_1' => [
                    'required_value_1' => $value,
                ],
            ],
            'array_node_1'
        );
    }

    public function testItFailsWhenProcessedConfigDoesNotMatchTheArrayofValues()
    {
        $value = 'some value';

        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'equal');
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => $value,
                    ],
                ],
            ],
            [
                'invalid_key' => 'invalid_value',
            ],
            'array_node_1'
        );
    }

    public function testThrowsComparisonFailedExceptionWithValuesOrderedCorrectly()
    {
        $value = 'some value';

        $configurationValues = [
            [
                'array_node_1' => [
                    'required_value_1' => $value,
                ],
            ],
        ];

        $expectedProcessedConfigurationValues = [
            'invalid_key' => 'invalid_value',
        ];

        try {
            $this->assertProcessedConfigurationEquals(
                $configurationValues,
                $expectedProcessedConfigurationValues,
                'array_node_1'
            );
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            static::assertSame(
                $expectedProcessedConfigurationValues,
                $exception->getComparisonFailure()->getExpected()
            );
        }
    }
}

/* EOF */
