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
 * Class InvalidQueryConditionModeException
 *
 * @package Sweikenb\Clutter\Datastore\Exceptions
 */
class InvalidQueryConditionModeException extends \InvalidArgumentException
{
    /**
     * @param $conditionMode
     *
     * @return InvalidQueryConditionModeException
     */
    public static function unsupportedConditionMode($conditionMode)
    {
        return new self(sprintf('The given query condition mode is invalid: %s', $conditionMode));
    }
}
