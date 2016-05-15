<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Service;

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;

/**
 * Class DatastoreService
 *
 * @package Sweikenb\Clutter\Datastore\Service
 */
class DatastoreService
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * DatastoreService constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $objectId
     *
     * @return null|DataModelInterface
     */
    public function get($objectId)
    {
        return $this->repository->getObject($objectId);
    }

    /**
     * @return RepositoryResultInterface
     */
    public function getAll()
    {
        return $this->repository->getList();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->repository->getCount();
    }

    /**
     * @param DataModelInterface $dataModel
     *
     * @return bool
     */
    public function set(DataModelInterface $dataModel)
    {
        return $this->repository->save($dataModel);
    }

    /**
     * @param DataModelInterface $dataModel
     *
     * @return bool
     */
    public function delete(DataModelInterface $dataModel)
    {
        return $this->repository->delete($dataModel);
    }

    /**
     * @param RepositoryQueryInterface $query
     *
     * @return RepositoryResultInterface
     */
    public function filter(RepositoryQueryInterface $query)
    {
        return $this->repository->getList($query);
    }

    /**
     * @param RepositoryQueryInterface $query
     *
     * @return int
     */
    public function filterCount(RepositoryQueryInterface $query)
    {
        return $this->repository->getCount($query);
    }
}
