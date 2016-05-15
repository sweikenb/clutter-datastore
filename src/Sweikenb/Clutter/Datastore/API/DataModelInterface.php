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
 * Interface DataModelInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface DataModelInterface extends \Serializable, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Internal ID field for mapping
     */
    const ID = '_id';

    /**
     * @return string|null
     */
    public function getId();

    /**
     * @param string $objectId
     *
     * @return $this
     */
    public function setId($objectId);

    /**
     * @return array
     */
    public function getArrayCopy();
}
