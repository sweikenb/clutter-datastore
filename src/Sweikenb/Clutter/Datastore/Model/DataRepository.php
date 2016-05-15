<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Model;

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;
use Sweikenb\Clutter\Datastore\API\ResultFactoryInterface;
use Sweikenb\Clutter\Datastore\API\StorageDriverInterface;

/**
 * Class DataRepository
 *
 * @package Sweikenb\Clutter\Datastore\Model
 */
class DataRepository implements RepositoryInterface
{
    /**
     * @var StorageDriverInterface
     */
    private $storageDriver;

    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;

    /**
     * @var ResultFactoryInterface
     */
    private $resultFactory;

    /**
     * DataRepository constructor.
     *
     * @param StorageDriverInterface $storageDriver
     * @param ModelFactoryInterface $modelFactory
     * @param ResultFactoryInterface $resultFactory
     */
    public function __construct(
        StorageDriverInterface $storageDriver,
        ModelFactoryInterface $modelFactory,
        ResultFactoryInterface $resultFactory
    ) {
        $this->storageDriver = $storageDriver;
        $this->modelFactory = $modelFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @param string $objectId
     *
     * @return DataModelInterface|null
     */
    public function getObject($objectId)
    {
        $entityData = $this->storageDriver->getEntityById($objectId);
        if ($entityData) {
            return $this->modelFactory->create($entityData);
        }
        return null;
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return RepositoryResultInterface
     */
    public function getList(RepositoryQueryInterface $query = null)
    {
        $entities = $this->storageDriver->getEntitiesByQuery($query);
        return $this->resultFactory->create($entities);
    }

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getCount(RepositoryQueryInterface $query = null)
    {
        return $this->storageDriver->getEntityCountByQuery($query);
    }

    /**
     * @param DataModelInterface $dataModel
     *
     * @return bool
     */
    public function save(DataModelInterface $dataModel)
    {
        return $this->storageDriver->saveEntity($dataModel->getId(), $dataModel->getArrayCopy());
    }

    /**
     * @param DataModelInterface $dataModel
     *
     * @return bool
     */
    public function delete(DataModelInterface $dataModel)
    {
        return $this->storageDriver->deleteEntity($dataModel->getId());
    }
}
