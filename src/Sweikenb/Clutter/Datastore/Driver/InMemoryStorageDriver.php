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
            switch ($mode) {
                case RepositoryQueryInterface::EQ:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] == $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::NEQ:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] != $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::IN:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if (in_array($item[$field], $value)) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::NOT_IN:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if (!in_array($item[$field], $value)) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::GT:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] > $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::GTE:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] >= $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::LT:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] < $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::LTE:
                    foreach ($filter as $id => $item) {
                        if (!isset($item[$field])) {
                            continue;
                        }
                        $onlySkipped = false;
                        if ($item[$field] <= $value) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::IS_NULL:
                    foreach ($filter as $id => $item) {
                        if (!in_array($field, array_keys($item))) {
                            continue;
                        }
                        $onlySkipped = false;
                        if (null === $item[$field]) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::NOT_NULL:
                    foreach ($filter as $id => $item) {
                        if (!in_array($field, array_keys($item))) {
                            continue;
                        }
                        $onlySkipped = false;
                        if (null !== $item[$field]) {
                            $results[$id] = $item;
                        }
                    }
                    break;

                case RepositoryQueryInterface::BETWEEN:
                    if (is_array($value) && count($value) === 2) {
                        foreach ($filter as $id => $item) {
                            if (!isset($item[$field])) {
                                continue;
                            }
                            $onlySkipped = false;
                            if ($item[$field] >= $value[0] && $item[$field] <= $value[1]) {
                                $results[$id] = $item;
                            }
                        }
                    }
                    break;

                case RepositoryQueryInterface::NOT_BETWEEN:
                    if (is_array($value) && count($value) === 2) {
                        foreach ($filter as $id => $item) {
                            if (!isset($item[$field])) {
                                continue;
                            }
                            $onlySkipped = false;
                            if (!($item[$field] >= $value[0] && $item[$field] <= $value[1])) {
                                $results[$id] = $item;
                            }
                        }
                    }
                    break;

                default:
                    throw UnsupportedConditionModeForDriverException::unsupportedConditionMode($mode, self::class);
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
}
