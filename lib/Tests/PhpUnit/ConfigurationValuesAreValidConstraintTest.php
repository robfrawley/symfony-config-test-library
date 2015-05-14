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

namespace Scribe\Test\Symfony\Config\Tests;

use Scribe\Test\Symfony\Config\PhpUnit\ConfigurationValuesAreValidConstraint;
use Scribe\Test\Symfony\Config\Tests\PhpUnit\Fixtures\AlwaysValidConfiguration;
use Scribe\Test\Symfony\Config\Tests\PhpUnit\Fixtures\ConfigurationWithRequiredValue;

class ConfigurationValuesAreValidConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testItFailsIfConfigValuesIsNotArray()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate('not an array');
    }

    public function testItFailsIfConfigValuesIsNotArrayOfArrays()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        $this->setExpectedException('\InvalidArgumentException', 'array');

        $constraint->evaluate(array('not an array'));
    }

    public function testMatchesIfConfigValuesAreValid()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        static::assertTrue($constraint->evaluate(array(array()), '', true));
    }

    public function testDoesNotMatchIfConfigValuesAreInvalid()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new ConfigurationWithRequiredValue());

        static::assertFalse($constraint->evaluate(array(array()), '', true));
    }

    public function testToStringReturnsMessage()
    {
        $constraint = new ConfigurationValuesAreValidConstraint(new AlwaysValidConfiguration());

        static::assertSame('is valid for the given configuration', $constraint->toString());
    }
}

/* EOF */
