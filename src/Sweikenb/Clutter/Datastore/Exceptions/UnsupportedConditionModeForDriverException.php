<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Exceptions;

/**
 * Class UnsupportedConditionModeForDriverException
 *
 * @package Sweikenb\Clutter\Datastore\Exceptions
 */
class UnsupportedConditionModeForDriverException extends \InvalidArgumentException
{
    /**
     * @param string $conditionMode
     * @param string $driver
     *
     * @return UnsupportedConditionModeForDriverException
     */
    public static function unsupportedConditionMode($conditionMode, $driver)
    {
        return new self(sprintf('The given query condition mode %s is not supported for the current driver %s', $conditionMode, $driver));
    }
}
