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

use Scribe\UnitTest\SymfonyConfig\PhpUnit\ConfigurationValuesAreInvalidConstraint;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\AlwaysValidConfiguration;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\ConfigurationWithRequiredValue;

/**
 * Class ConfigurationValuesAreInvalidConstraintTest.
 */
class ConfigurationValuesAreInvalidConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testItFailedIfConfigValuesIsNotArray()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate('not an array');
    }

    public function testItFailedIfConfigValuesIsNotArrayOfArrays()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate(['not an array']);
    }

    public function testItDoesNotMatchIfConfigValuesAreValid()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(new AlwaysValidConfiguration());

        static::assertFalse($constraint->evaluate([[]], '', true));
    }

    public function testItMatchesIfConfigurationValuesAreInvalid()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(new ConfigurationWithRequiredValue());

        static::assertTrue($constraint->evaluate([[]], '', true));
    }

    public function testItDoesNotMatchWhenExceptionMessageIsNotRightIfConfigurationValuesAreInvalid()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(
            new ConfigurationWithRequiredValue(),
            'expected message which will not be part of the actual message'
        );

        static::assertFalse($constraint->evaluate([[]], '', true));
    }

    public function testItDoesMatchWhenExceptionMessageIsRightIfConfigurationValuesAreInvalid()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(
            new ConfigurationWithRequiredValue(),
            'required_value'
        );

        static::assertTrue($constraint->evaluate([[]], '', true));
    }

    public function testItDoesMatchWhenExceptionMessageIsRightAndRegexIfConfigurationValuesAreInvalid()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(
            new ConfigurationWithRequiredValue(),
            '/required[_]{1}value/',
            true
        );

        static::assertTrue($constraint->evaluate([[]], '', true));
    }

    public function testToStringReturnsAMessage()
    {
        $constraint = new ConfigurationValuesAreInvalidConstraint(
            new AlwaysValidConfiguration()
        );

        static::assertSame('is invalid for the given configuration', $constraint->toString());
    }

    public function testToStringMentionsTheExpectedExceptionMessage()
    {
        $expectedMessage = 'the expected message';
        $constraint = new ConfigurationValuesAreInvalidConstraint(
            new AlwaysValidConfiguration(),
            $expectedMessage
        );

        $expectedResult = 'is invalid for the given configuration (expected exception message: '.$expectedMessage.')';

        static::assertSame($expectedResult, $constraint->toString());
    }
}

/* EOF */
