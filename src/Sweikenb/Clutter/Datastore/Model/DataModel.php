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

/**
 * Class DataModel
 *
 * @package Sweikenb\Clutter\Datastore\Model
 */
final class DataModel extends \ArrayObject implements DataModelInterface
{
    /**
     * DataModel constructor.
     *
     * @param null         $objectId       The storage ID for this object
     * @param array|object $input          The input parameter accepts an array or an Object.
     * @param int          $flags          Flags to control the behaviour of the ArrayObject object.
     * @param string       $iterator_class Specify the class that will be used for iteration of the ArrayObject object. ArrayIterator is the default class used.
     */
    public function __construct($objectId = null, $input = [], $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
        if (null !== $objectId) {
            $this->setId($objectId);
        }
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        if (!$this->offsetExists(self::ID)) {
            return null;
        }
        return (string)$this->offsetGet(self::ID);
    }

    /**
     * @param string $objectId
     *
     * @return $this
     */
    public function setId($objectId)
    {
        $this->offsetSet(self::ID, (string)$objectId);
        return $this;
    }
}
