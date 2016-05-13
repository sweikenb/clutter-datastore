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
use Predis\ClientInterface as PredisClientInterface;

/**
 * Class RedisStorageDriver
 *
 * @package Sweikenb\Clutter\Datastore\Driver
 */
class RedisStorageDriver implements StorageDriverInterface
{
    /**
     * @var PredisClientInterface
     */
    private $client;

    /**
     * RedisStorageDriver constructor.
     *
     * @param PredisClientInterface $client
     */
    public function __construct(PredisClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $id
     *
     * @return array|null
     */
    public function getEntityById($id)
    {
        // TODO: Implement getEntityById() method.
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return array[]
     */
    public function getEntitiesByQuery(RepositoryQueryInterface $query = null)
    {
        // TODO: Implement getEntitiesByQuery() method.
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getEntityCountByQuery(RepositoryQueryInterface $query = null)
    {
        // TODO: Implement getEntityCountByQuery() method.
    }

    /**
     * @param string $entityId
     * @param array  $entityData
     *
     * @return bool
     */
    public function saveEntity($entityId, array $entityData)
    {
        // TODO: Implement saveEntity() method.
    }

    /**
     * @param string $entityId
     *
     * @return bool
     */
    public function deleteEntity($entityId)
    {
        // TODO: Implement deleteEntity() method.
    }
}
