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


use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\API\ResultFactoryInterface;
use Sweikenb\Clutter\Datastore\API\StorageDriverInterface;

/**
 * Class DatastoreService
 *
 * @package Sweikenb\Clutter\Datastore\Service
 */
class DatastoreService
{
    /**
     * @var StorageDriverInterface
     */
    private $dataStorage;

    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;

    /**
     * @var ResultFactoryInterface
     */
    private $resultFactory;

    /**
     * DatastoreService constructor.
     *
     * @param StorageDriverInterface $dataStorage
     * @param ModelFactoryInterface  $modelFactory
     * @param ResultFactoryInterface $resultFactory
     */
    public function __construct(StorageDriverInterface $dataStorage, ModelFactoryInterface $modelFactory, ResultFactoryInterface $resultFactory)
    {
        $this->dataStorage = $dataStorage;
        $this->modelFactory = $modelFactory;
        $this->resultFactory = $resultFactory;
    }



}
