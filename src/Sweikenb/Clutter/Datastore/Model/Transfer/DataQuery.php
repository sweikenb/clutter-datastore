<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Model\Transfer;

use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\Exceptions\InvalidQueryConditionModeException;

/**
 * Class DataQuery
 *
 * @package Sweikenb\Clutter\Datastore\Model\Transfer
 */
class DataQuery implements RepositoryQueryInterface
{
    /**
     * @var array
     */
    private $conditions;

    /**
     * DataQuery constructor.
     */
    public function __construct()
    {
        $this->conditions = [];
    }

    /**
     * @return array
     */
    public function getConditionModes()
    {
        return [
            self::EQ,
            self::NEQ,
            self::IN,
            self::NOT_IN,
            self::GT,
            self::GTE,
            self::LT,
            self::LTE,
            self::IS_NULL,
            self::NOT_NULL,
            self::BETWEEN,
            self::NOT_BETWEEN
        ];
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string     $field
     * @param string     $mode
     * @param mixed|null $value
     *
     * @return $this
     * @throws InvalidQueryConditionModeException
     */
    public function field($field, $mode, $value = null)
    {
        if (!in_array($mode, $this->getConditionModes())) {
            throw InvalidQueryConditionModeException::unsupportedConditionMode($mode);
        }

        $this->conditions[$field] = [$mode, $value];
        return $this;
    }
}
