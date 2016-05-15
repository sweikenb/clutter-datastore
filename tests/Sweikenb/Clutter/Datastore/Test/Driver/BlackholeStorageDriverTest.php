<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Test\Driver;

use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\Driver\BlackholeStorageDriver;

/**
 * Class BlackholeStorageDriverTest
 *
 * @package Sweikenb\Clutter\Datastore\Test\Driver
 */
class BlackholeStorageDriverTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID1 = 'ID-1234567890';

    /**
     * @test
     */
    public function simpleOperations()
    {
        $driver = new BlackholeStorageDriver();

        $this->assertNull($driver->getEntityById(self::TEST_ID1));
        $this->assertTrue($driver->saveEntity(self::TEST_ID1, ['foo' => 'bar']));
        $this->assertTrue($driver->saveEntity('', []));
        $this->assertTrue($driver->deleteEntity(self::TEST_ID1));
        $this->assertTrue($driver->deleteEntity(''));
    }

    /**
     * @test
     */
    public function queryOperations()
    {
        $driver = new BlackholeStorageDriver();

        $query = $this->getMock(RepositoryQueryInterface::class);
        $methods = get_class_methods(RepositoryQueryInterface::class);
        foreach($methods as $method) {
            $query->expects($this->never())->method($method);
        }

        $this->assertEquals([], $driver->getEntitiesByQuery());
        $this->assertEquals([], $driver->getEntitiesByQuery($query));
        $this->assertEquals(0, $driver->getEntityCountByQuery());
        $this->assertEquals(0, $driver->getEntityCountByQuery($query));
    }
}
