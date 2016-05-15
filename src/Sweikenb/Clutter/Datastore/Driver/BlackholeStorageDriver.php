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
 * Class BlackholeStorageDriver
 *
 * @package Sweikenb\Clutter\Datastore\Driver
 */
class BlackholeStorageDriver implements StorageDriverInterface
{
    /**
     * @param string $id
     *
     * @return array|null
     */
    public function getEntityById($id)
    {
        return null;
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return array[]
     */
    public function getEntitiesByQuery(RepositoryQueryInterface $query = null)
    {
        return [];
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getEntityCountByQuery(RepositoryQueryInterface $query = null)
    {
        return 0;
    }

    /**
     * @param string $entityId
     * @param array  $entityData
     *
     * @return bool
     */
    public function saveEntity($entityId, array $entityData)
    {
        return true;
    }

    /**
     * @param string $entityId
     *
     * @return bool
     */
    public function deleteEntity($entityId)
    {
        return true;
    }
}
