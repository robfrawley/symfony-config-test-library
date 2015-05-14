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

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class ConfigurationValuesAreInvalidConstraint.
 */
class ConfigurationValuesAreInvalidConstraint extends AbstractConfigurationConstraint
{
    /**
     * @var string|null
     */
    private $expectedExceptionMessage;

    /**
     * @var bool
     */
    private $exceptionMessageAsRegex;

    /**
     * @param ConfigurationInterface $configuration
     * @param string|null            $expectedExceptionMessage
     * @param bool                   $exceptionMessageAsRegex
     * @param string|null            $breadcrumbPath
     */
    public function __construct(ConfigurationInterface $configuration, $expectedExceptionMessage = null,
                                $exceptionMessageAsRegex = false, $breadcrumbPath = null
    ) {
        parent::__construct($configuration, $breadcrumbPath);

        $this->expectedExceptionMessage = $expectedExceptionMessage;
        $this->exceptionMessageAsRegex = $exceptionMessageAsRegex;
    }

    /**
     * @param mixed  $other
     * @param string $description
     * @param bool   $returnResult
     *
     * @return bool|mixed
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        $this->validateConfigurationValuesArray($other);

        try {
            $this->processConfiguration($other);
        } catch (InvalidConfigurationException $exception) {
            return $this->evaluateException($exception, $description, $returnResult);
        }

        if ($returnResult) {
            return false;
        }

        return $this->fail($other, $description);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $toString = 'is invalid for the given configuration';

        if ($this->expectedExceptionMessage !== null) {
            $toString .= ' (expected exception message: '.$this->expectedExceptionMessage.')';
        }

        return $toString;
    }

    /**
     * @param \Exception $exception
     * @param string     $description
     * @param string     $returnResult
     *
     * @return bool|mixed
     */
    private function evaluateException(\Exception $exception, $description, $returnResult)
    {
        if ($this->expectedExceptionMessage === null) {
            return true;
        }

        return $this
            ->createPhpUnitConstraint()
            ->evaluate($exception, $description, $returnResult)
        ;
    }

    /**
     * @return \PHPUnit_Framework_Constraint_ExceptionMessage|\PHPUnit_Framework_Constraint_ExceptionMessageRegExp
     */
    private function createPhpUnitConstraint()
    {
        if (true === $this->exceptionMessageAsRegex) {
            return new \PHPUnit_Framework_Constraint_ExceptionMessageRegExp($this->expectedExceptionMessage);
        }

        return new \PHPUnit_Framework_Constraint_ExceptionMessage($this->expectedExceptionMessage);
    }
}

/* EOF */
