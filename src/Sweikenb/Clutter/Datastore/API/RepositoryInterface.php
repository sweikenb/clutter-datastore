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
 * Interface RepositoryInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface RepositoryInterface
{
    /**
     * @param string $objectId
     *
     * @return DataModelInterface|null
     */
    public function getObject($objectId);

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return RepositoryResultInterface
     */
    public function getList(RepositoryQueryInterface $query = null);

    /**
     * @param RepositoryQueryInterface|null $query
     *
     * @return int
     */
    public function getCount(RepositoryQueryInterface $query = null);

    /**
     * @param DataModelInterface $dataObject
     *
     * @return bool
     */
    public function save(DataModelInterface $dataObject);

    /**
     * @param DataModelInterface $dataObject
     *
     * @return bool
     */
    public function delete(DataModelInterface $dataObject);
}
