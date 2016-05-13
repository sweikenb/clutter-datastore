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

/**
 * Interface StorageDriverInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface StorageDriverInterface
{
    /**
     * @param string $id
     *
     * @return array|null
     */
    public function getEntityById($id);

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return array[]
     */
    public function getEntitiesByQuery(RepositoryQueryInterface $query = null);

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getEntityCountByQuery(RepositoryQueryInterface $query = null);

    /**
     * @param string $entityId
     * @param array  $entityData
     *
     * @return bool
     */
    public function saveEntity($entityId, array $entityData);

    /**
     * @param string $entityId
     *
     * @return bool
     */
    public function deleteEntity($entityId);
}
