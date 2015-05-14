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

use Scribe\UnitTest\SymfonyConfig\PhpUnit\ConfigurationTestCaseTrait;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\ConfigurationWithRequiredValue;

/**
 * Class ConfigurationTestCaseTraitTest.
 */
class ConfigurationTestCaseTraitTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @return ConfigurationWithRequiredValue
     */
    protected function getConfiguration()
    {
        return new ConfigurationWithRequiredValue();
    }

    public function testItCanAssertThatAConfigurationIsInvalid()
    {
        $this->assertConfigurationIsInvalid(
            [
                [] // no configuration values
            ],
            'required_value'
        );
    }

    public function testItFailsWhenAConfigurationIsValidWhenItShouldHaveBeenInvalid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'invalid');

        $this->assertConfigurationIsInvalid(
            [
                ['required_value' => 'some value']
            ]
        );
    }

    public function testItCanAssertThatAConfigurationIsValid()
    {
        $this->assertConfigurationIsValid(
            [
                ['required_value' => 'some value']
            ]
        );
    }

    public function testItFailsWhenAConfigurationIsInvalidWhenItShouldHaveBeenValid()
    {
        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'valid');

        $this->assertConfigurationIsValid(
            [
                []
            ]
        );
    }

    public function testItCanAssertThatAProcessedConfigurationMatchesTheExpectedArrayOfValues()
    {
        $value = 'some value';

        $this->assertProcessedConfigurationEquals(
            [
                [],
                ['required_value' => $value]
            ],
            [
                'required_value' => $value
            ]
        );
    }

    public function testItFailsWhenAProcessedConfigurationDoesNotMatchTheExpectedArrayOfValues()
    {
        $value = 'some value';

        $this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'equal');
        $this->assertProcessedConfigurationEquals(
            [
                ['required_value' => $value]
            ],
            [
                'invalid_key' => 'invalid_value'
            ]
        );
    }

    public function testItThrowsAComparisonFailedExceptionWithTheValuesInTheRightOrder()
    {
        $value = 'some value';

        //$this->setExpectedException('\PHPUnit_Framework_ExpectationFailedException', 'equal');
        $configurationValues = [
            ['required_value' => $value]
        ];

        $expectedProcessedConfigurationValues = [
            'invalid_key' => 'invalid_value'
        ];

        try {
            $this->assertProcessedConfigurationEquals(
                $configurationValues,
                $expectedProcessedConfigurationValues
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
