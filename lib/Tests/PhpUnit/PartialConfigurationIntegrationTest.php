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

namespace Scribe\UnitTest\SymfonyConfig\Tests;

use Scribe\UnitTest\SymfonyConfig\PhpUnit\AbstractConfigurationTestCase;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\ConfigurationWithMultipleArrayKeys;

class PartialConfigurationIntegrationTest extends AbstractConfigurationTestCase
{
    /**
     * @return ConfigurationWithMultipleArrayKeys
     */
    protected function getConfiguration()
    {
        return new ConfigurationWithMultipleArrayKeys();
    }

    public function test_it_can_assert_that_a_configuration_is_invalid()
    {
        $this->assertPartialConfigurationIsInvalid(
            [
                [] // no configuration values
            ],
            'array_node_1',
            'array_node_1'
        );
    }

    public function test_it_fails_when_a_configuration_is_valid_when_it_should_have_been_invalid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'invalid');

        $this->assertPartialConfigurationIsInvalid(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => 'some value'
                    ]
                ]
            ],
            'array_node_1'
        );
    }

    public function test_it_can_assert_that_a_configuration_is_valid()
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => 'some value'
                    ]
                ]
            ],
            'array_node_1'
        );
    }

    public function test_it_fails_when_a_configuration_is_invalid_when_it_should_have_been_valid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'valid');

        $this->assertConfigurationIsValid(
            [
                []
            ],
            'array_node_1'
        );
    }

    public function test_it_can_assert_that_a_processed_configuration_matches_the_expected_array_of_values()
    {
        $value = 'some value';

        $this->assertProcessedConfigurationEquals(
            [
                [],
                [
                    'array_node_1' => [
                        'required_value_1' => $value
                    ]
                ]
            ],
            [
                'array_node_1' => [
                    'required_value_1' => $value
                ]
            ],
            'array_node_1'
        );
    }

    public function test_it_fails_when_a_processed_configuration_does_not_match_the_expected_array_of_values()
    {
        $value = 'some value';

        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'equal');
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'array_node_1' => [
                        'required_value_1' => $value
                    ]
                ]
            ],
            [
                'invalid_key' => 'invalid_value'
            ],
            'array_node_1'
        );
    }

    public function test_it_throws_a_comparison_failed_exception_with_the_values_in_the_right_order()
    {
        $value = 'some value';

        $configurationValues = [
            [
                'array_node_1' => [
                    'required_value_1' => $value
                ]
            ]
        ];

        $expectedProcessedConfigurationValues = [
            'invalid_key' => 'invalid_value'
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
