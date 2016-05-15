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

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\Model\DataModel;

/**
 * Class DataModelFactory
 *
 * @package Sweikenb\Clutter\Datastore\Service\Factory
 */
class DataModelFactory implements ModelFactoryInterface
{
    /**
     * @param array $entityData
     *
     * @return DataModelInterface
     */
    public function create(array $entityData)
    {
        return new DataModel(null, $entityData);
    }
}
