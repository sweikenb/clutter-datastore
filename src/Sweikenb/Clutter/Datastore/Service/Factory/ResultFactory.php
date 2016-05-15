<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Service\Factory;

use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;
use Sweikenb\Clutter\Datastore\API\ResultFactoryInterface;
use Sweikenb\Clutter\Datastore\Model\Transfer\DataResult;

/**
 * Class ResultFactory
 *
 * @package Sweikenb\Clutter\Datastore\Service\Factory
 */
class ResultFactory implements ResultFactoryInterface
{
    /**
     * @var ModelFactoryInterface
     */
    private $modelFactory;

    /**
     * ResultFactory constructor.
     *
     * @param ModelFactoryInterface $modelFactory
     */
    public function __construct(ModelFactoryInterface $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param array $results
     *
     * @return RepositoryResultInterface
     */
    public function create(array $results)
    {
        $resultlist = new DataResult();
        foreach ($results as $entityData) {
            $resultlist->addResult($this->modelFactory->create($entityData));
        }

        return $resultlist;
    }
}
