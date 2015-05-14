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

namespace Scribe\Test\Symfony\Config\Tests\TestConstraint;

use Scribe\Test\Symfony\Config\TestConstraint\ProcessedConfigurationEqualsConstraint;
use Scribe\Test\Symfony\Config\Tests\TestCase\Fixtures\AlwaysValidConfiguration;
use Scribe\Test\Symfony\Config\Tests\TestCase\Fixtures\ConfigurationWithRequiredValue;

class ProcessedConfigurationEqualsConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testIfConfigIsInvalidItDoesNotMatch()
    {
        $constraint = new ProcessedConfigurationEqualsConstraint(
            new AlwaysValidConfiguration(),
            array()
        );

        static::assertFalse($constraint->evaluate(array('non-existing-key' => array()), '', true));
    }

    public function testIfProcessedConfigMatchesExpectedValues()
    {
        $value = 'some value';

        $constraint = new ProcessedConfigurationEqualsConstraint(
            new ConfigurationWithRequiredValue(),
            array(array('required_value' => $value))
        );

        static::assertTrue($constraint->evaluate(array('required_value' => $value), '', true));
    }

    public function testToStringIsNotImplements()
    {
        $constraint = new ProcessedConfigurationEqualsConstraint(
            new AlwaysValidConfiguration(),
            array()
        );

        static::assertNull($constraint->toString());
    }
}

/* EOF */
