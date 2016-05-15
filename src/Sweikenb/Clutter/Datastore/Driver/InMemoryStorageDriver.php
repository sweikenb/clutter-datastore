<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Driver;

use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\API\StorageDriverInterface;
use Sweikenb\Clutter\Datastore\Exceptions\UnsupportedConditionModeForDriverException;

/**
 * Class InMemoryStorageDriver
 *
 * @package Sweikenb\Clutter\Datastore\Driver
 */
class InMemoryStorageDriver implements StorageDriverInterface
{
    /**
     * @var \ArrayObject
     */
    private $storage;

    /**
     * InMemoryStorageDriver constructor.
     */
    public function __construct()
    {
        $this->storage = new \ArrayObject();
    }

    /**
     * @param string $id
     *
     * @return array|null
     */
    public function getEntityById($id)
    {
        if ($this->storage->offsetExists($id)) {
            return $this->storage->offsetGet($id);
        }
        return null;
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return array[]
     * @throws UnsupportedConditionModeForDriverException
     */
    public function getEntitiesByQuery(RepositoryQueryInterface $query = null)
    {
        if (null === $query || empty($query->getConditions())) {
            return $this->storage->getArrayCopy();
        }

        $filter = $this->storage->getArrayCopy();
        $results = [];

        foreach ($query->getConditions() as $field => $condition) {
            $mode = $condition[0];
            $value = $condition[1];

            if (!empty($results)) {
                unset($filter);
                $filter = $results;
                $results = [];
            }

            $onlySkipped = true;
            foreach ($filter as $id => $item) {
                if (!in_array($field, array_keys($item))) {
                    continue;
                }
                $onlySkipped = false;
                if ($this->processMode($mode, $value, $item[$field])) {
                    $results[$id] = $item;
                }
            }

            if ($onlySkipped) {
                $results = $filter;
            }
        }

        return array_values($results);
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getEntityCountByQuery(RepositoryQueryInterface $query = null)
    {
        if (null !== $query) {
            $results = $this->getEntitiesByQuery($query);
            return count($results);
        }
        return $this->storage->count();
    }

    /**
     * @param string $entityId
     * @param array  $entityData
     *
     * @return bool
     */
    public function saveEntity($entityId, array $entityData)
    {
        $this->storage->offsetSet($entityId, $entityData);
        return true;
    }

    /**
     * @param string $entityId
     *
     * @return bool
     */
    public function deleteEntity($entityId)
    {
        if ($this->storage->offsetExists($entityId)) {
            $this->storage->offsetUnset($entityId);
        }
        return true;
    }

    /**
     * @param string $mode
     * @param mixed  $value
     * @param mixed  $compareValue
     *
     * @return bool
     * @throw UnsupportedConditionModeForDriverException
     */
    private function processMode($mode, $value, $compareValue)
    {
        switch ($mode) {
            case RepositoryQueryInterface::EQ:
                return ($compareValue == $value);

            case RepositoryQueryInterface::NEQ:
                return ($compareValue != $value);

            case RepositoryQueryInterface::IN:
                return in_array($compareValue, $value);

            case RepositoryQueryInterface::NOT_IN:
                return !in_array($compareValue, $value);

            case RepositoryQueryInterface::GT:
                return ($compareValue > $value);

            case RepositoryQueryInterface::GTE:
                return ($compareValue >= $value);

            case RepositoryQueryInterface::LT:
                return ($compareValue < $value);

            case RepositoryQueryInterface::LTE:
                return ($compareValue <= $value);

            case RepositoryQueryInterface::IS_NULL:
                return (null === $compareValue);

            case RepositoryQueryInterface::NOT_NULL:
                return (null !== $compareValue);

            case RepositoryQueryInterface::BETWEEN:
                return (is_array($value) && count($value) === 2 && $compareValue >= $value[0] && $compareValue <= $value[1]);

            case RepositoryQueryInterface::NOT_BETWEEN:
                return !(is_array($value) && count($value) === 2 && $compareValue >= $value[0] && $compareValue <= $value[1]);

            default:
                throw UnsupportedConditionModeForDriverException::unsupportedConditionMode($mode, self::class);
        }
    }
}
