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

use Scribe\UnitTest\SymfonyConfig\PhpUnit\ConfigurationValuesAreValidConstraint;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\AlwaysValidConfiguration;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\ConfigurationWithRequiredValue;

class ConfigurationValuesAreValidConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function test_if_configuration_values_is_no_array_it_fails()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate('not an array');
    }

    public function test_if_configuration_values_is_no_array_of_arrays_it_fails()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate(array('not an array'));
    }

    public function test_if_configuration_values_are_valid_it_matches()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        static::assertTrue($constraint->evaluate(array(array()), '', true));
    }

    public function test_if_configuration_values_are_invalid_it_does_not_match()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new ConfigurationWithRequiredValue());

        static::assertFalse($constraint->evaluate(array(array()), '', true));
    }

    public function test_to_string_returns_a_message()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        static::assertSame('is valid for the given configuration', $constraint->toString());
    }
}

/* EOF */
