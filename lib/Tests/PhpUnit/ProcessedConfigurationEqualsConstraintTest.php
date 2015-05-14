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

use Scribe\UnitTest\SymfonyConfig\PhpUnit\ProcessedConfigurationEqualsConstraint;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\AlwaysValidConfiguration;
use Scribe\UnitTest\SymfonyConfig\Tests\PhpUnit\Fixtures\ConfigurationWithRequiredValue;

class ProcessedConfigurationEqualsConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function test_if_configuration_is_invalid_it_does_not_match()
    {
        $constraint = new ProcessedConfigurationEqualsConstraint(
            new AlwaysValidConfiguration(),
            array()
        );

        static::assertFalse($constraint->evaluate(array('non-existing-key' => array()), '', true));
    }

    public function test_if_processed_configuration_equals_the_expected_values_it_matches()
    {
        $value = 'some value';

        $constraint = new ProcessedConfigurationEqualsConstraint(
            new ConfigurationWithRequiredValue(),
            array(array('required_value' => $value))
        );

        static::assertTrue($constraint->evaluate(array('required_value'=> $value), '', true));
    }

    public function test_to_string_is_not_implemented()
    {
        $constraint = new ProcessedConfigurationEqualsConstraint(
            new AlwaysValidConfiguration(),
            array()
        );

        static::assertNull($constraint->toString());
    }
}

/* EOF */
