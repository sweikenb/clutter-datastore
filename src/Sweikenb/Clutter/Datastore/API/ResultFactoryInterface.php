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
 * Interface ResultFactoryInterface
 *
 * @package Sweikenb\Clutter\Datastore\API
 */
interface ResultFactoryInterface
{
    /**
     * @param array $results
     *
     * @return RepositoryResultInterface
     */
    public function create(array $results);
}
