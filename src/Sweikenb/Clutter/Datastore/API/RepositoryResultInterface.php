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
 * Interface RepositoryResultInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface RepositoryResultInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @param DataModelInterface $dataModel
     *
     * @return $this
     */
    public function addResult(DataModelInterface $dataModel);
}
