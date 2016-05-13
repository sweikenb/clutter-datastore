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
     */
    public function getEntitiesByQuery(RepositoryQueryInterface $query = null)
    {
        // TODO: Implement getEntitiesByQuery() method.
        return [];
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getEntityCountByQuery(RepositoryQueryInterface $query = null)
    {
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
