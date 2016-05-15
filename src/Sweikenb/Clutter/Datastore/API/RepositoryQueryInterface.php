<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\API;

use Sweikenb\Clutter\Datastore\Exceptions\InvalidQueryConditionModeException;

/**
 * Interface RepositoryQueryInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface RepositoryQueryInterface
{
    const EQ = 'eq';
    const NEQ = 'neq';
    const IN = 'in';
    const NOT_IN = 'nin';
    const GT = 'gt';
    const GTE = 'gte';
    const LT = 'lt';
    const LTE = 'lte';
    const IS_NULL = 'inull';
    const NOT_NULL = 'nnull';
    const BETWEEN = 'btw';
    const NOT_BETWEEN = 'nbtw';

    /**
     * @return array
     */
    public function getConditionModes();

    /**
     * @return array
     */
    public function getConditions();

    /**
     * @param string     $field
     * @param string     $mode
     * @param mixed|null $value
     *
     * @return $this
     * @throws InvalidQueryConditionModeException
     */
    public function field($field, $mode, $value = null);
}
