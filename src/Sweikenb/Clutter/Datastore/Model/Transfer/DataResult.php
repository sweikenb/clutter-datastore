<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Model\Transfer;

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;

/**
 * Class DataResult
 *
 * @package Sweikenb\Clutter\Datastore\Model\Transfer
 */
final class DataResult extends \ArrayObject implements RepositoryResultInterface
{
    /**
     * @param DataModelInterface $dataModel
     *
     * @return $this
     */
    public function addResult(DataModelInterface $dataModel)
    {
        $this->append($dataModel);
        return $this;
    }
}
