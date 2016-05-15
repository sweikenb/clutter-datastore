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

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\Driver\InMemoryStorageDriver;
use Sweikenb\Clutter\Datastore\Exceptions\UnsupportedConditionModeForDriverException;
use Sweikenb\Clutter\Datastore\Model\Transfer\DataQuery;

/**
 * Class InMemoryStorageDriverTest
 *
 * @package Sweikenb\Clutter\Datastore\Test\Driver
 */
class InMemoryStorageDriverTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID1 = 'ID-1234567890';
    const TEST_ID2 = 'ID-0987654321';
    const TEST_ID3 = 'ID-5432167890';
    const TEST_ID4 = 'ID-6789054321';
    const TEST_UNKNOWN = 'UNKNOWN';

    const FIELD_PRICE = 'price';
    const FIELD_PREFS = 'porefs';

    private function saveEntitiesForFiltering(InMemoryStorageDriver $memoryDriver)
    {
        $entities = [
            self::TEST_ID1 => [
                DataModelInterface::ID => self::TEST_ID1,
                self::FIELD_PRICE      => 11.11,
                self::FIELD_PREFS      => 1
            ],
            self::TEST_ID2 => [
                DataModelInterface::ID => self::TEST_ID2,
                self::FIELD_PRICE      => 22.22,
                self::FIELD_PREFS      => null
            ],
            self::TEST_ID3 => [
                DataModelInterface::ID => self::TEST_ID3,
                self::FIELD_PRICE      => 33.33,
                self::FIELD_PREFS      => 1
            ],
            self::TEST_ID4 => [
                DataModelInterface::ID => self::TEST_ID4,
                self::FIELD_PRICE      => 44.44,
                self::FIELD_PREFS      => null
            ]
        ];

        foreach ($entities as $id => $data) {
            $memoryDriver->saveEntity($id, $data);
        }

        return $entities;
    }

    /**
     * @test
     */
    public function storageObject()
    {
        $memoryDriver = new InMemoryStorageDriver();
        $this->assertAttributeEquals(new \ArrayObject(), 'storage', $memoryDriver);
        $entities = $this->saveEntitiesForFiltering($memoryDriver);
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery());
        $this->assertEquals($entities, $memoryDriver->getEntitiesByQuery());
        $this->assertAttributeEquals(new \ArrayObject($entities), 'storage', $memoryDriver);
    }

    /**
     * @test
     */
    public function getCountSaveDeleteEntityById()
    {
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::EQ, self::TEST_ID1);

        $memoryDriver = new InMemoryStorageDriver();
        $this->assertNull($memoryDriver->getEntityById(self::TEST_ID1));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery());
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));

        $entityData = [
            DataModelInterface::ID => self::TEST_ID1,
            'foo'                  => 'bar'
        ];
        $this->assertTrue($memoryDriver->saveEntity(self::TEST_ID1, $entityData));
        $this->assertEquals($entityData, $memoryDriver->getEntityById(self::TEST_ID1));
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery());
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        $this->assertTrue($memoryDriver->deleteEntity(self::TEST_ID1));
        $this->assertNull($memoryDriver->getEntityById(self::TEST_ID1));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery());
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterEqNeq()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- EQ --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::EQ, self::TEST_ID2);
        $query->field('unknown-field', DataQuery::EQ, self::TEST_ID2);
        $this->assertEquals([$entities[self::TEST_ID2]], $memoryDriver->getEntitiesByQuery($query));
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::EQ, self::TEST_ID3);
        $this->assertEquals(
            [
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        //-- NEQ --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NEQ, self::TEST_ID2);
        $query->field('unknown-field', DataQuery::NEQ, self::TEST_ID2);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(3, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NEQ, self::TEST_ID3);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(3, $memoryDriver->getEntityCountByQuery($query));

        //-- UNKNOWN --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::EQ, self::TEST_UNKNOWN);
        $this->assertEquals([], $memoryDriver->getEntitiesByQuery($query));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NEQ, self::TEST_UNKNOWN);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterInNin()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- IN --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::IN, [self::TEST_ID2, self::TEST_ID4]);
        $query->field('unknown-field', DataQuery::IN, [self::TEST_ID2, self::TEST_ID4]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::IN, [self::TEST_ID1, self::TEST_ID3]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- NOT_IN --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NOT_IN, [self::TEST_ID2, self::TEST_ID4]);
        $query->field('unknown-field', DataQuery::NOT_IN, [self::TEST_ID2, self::TEST_ID4]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NOT_IN, [self::TEST_ID1, self::TEST_ID3]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- UNKNOWN --
        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::IN, [self::TEST_UNKNOWN]);
        $this->assertEquals([], $memoryDriver->getEntitiesByQuery($query));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(DataModelInterface::ID, DataQuery::NOT_IN, [self::TEST_UNKNOWN]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterGtGte()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- GT --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GT, 22.22);
        $query->field('unknown-field', DataQuery::GT, 22.22);
        $this->assertEquals(
            [
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GT, 33.33);
        $this->assertEquals(
            [
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        //-- GTE --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GTE, 22.22);
        $query->field('unknown-field', DataQuery::GTE, 22.22);
        $this->assertEquals(
            [
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(3, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GTE, 33.33);
        $this->assertEquals(
            [
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- UNKNOWN --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GT, 44.44);
        $this->assertEquals([], $memoryDriver->getEntitiesByQuery($query));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::GTE, 11.11);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterLtLte()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- LT --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LT, 33.33);
        $query->field('unknown-field', DataQuery::LT, 33.33);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LT, 22.22);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        //-- LTE --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LTE, 33.33);
        $query->field('unknown-field', DataQuery::LTE, 33.33);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(3, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LTE, 22.22);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- UNKNOWN --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LT, 11.11);
        $this->assertEquals([], $memoryDriver->getEntitiesByQuery($query));
        $this->assertEquals(0, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::LTE, 44.44);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterNullNotnull()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- NULL --
        $query = new DataQuery();
        $query->field(self::FIELD_PREFS, DataQuery::IS_NULL);
        $query->field('unknown-field', DataQuery::IS_NULL);
        $this->assertEquals(
            [
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- NOT_NULL --
        $query = new DataQuery();
        $query->field(self::FIELD_PREFS, DataQuery::NOT_NULL);
        $query->field('unknown-field', DataQuery::NOT_NULL);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function filterBetweenNotbetween()
    {
        //-- Setup --
        $memoryDriver = new InMemoryStorageDriver();
        $entities = $this->saveEntitiesForFiltering($memoryDriver);

        //-- BETWEEN --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::BETWEEN, [20.1, 40.1]);
        $query->field('unknown-field', DataQuery::BETWEEN, [20.1, 40.1]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::BETWEEN, [30.1, 40.1]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID3]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(1, $memoryDriver->getEntityCountByQuery($query));

        //-- NOT_BETWEEN --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::NOT_BETWEEN, [20.1, 40.1]);
        $query->field('unknown-field', DataQuery::NOT_BETWEEN, [20.1, 40.1]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::NOT_BETWEEN, [30.1, 50.1]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(2, $memoryDriver->getEntityCountByQuery($query));

        //-- ALL --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::BETWEEN, [0.0, 99.9]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));

        //-- UNKNOWN --
        $query = new DataQuery();
        $query->field(self::FIELD_PRICE, DataQuery::NOT_BETWEEN, [49.99, 99.99]);
        $this->assertEquals(
            [
                $entities[self::TEST_ID1],
                $entities[self::TEST_ID2],
                $entities[self::TEST_ID3],
                $entities[self::TEST_ID4]
            ],
            $memoryDriver->getEntitiesByQuery($query)
        );
        $this->assertEquals(4, $memoryDriver->getEntityCountByQuery($query));
    }

    /**
     * @test
     */
    public function unsupportedConditionMonde()
    {
        $unknownMode = 'unknown-condition-mode';

        $this->expectException(UnsupportedConditionModeForDriverException::class);
        $this->expectExceptionMessage(
            'The given query condition mode ' . $unknownMode . ' ' .
            'is not supported for the current driver ' . InMemoryStorageDriver::class
        );

        $queryMock = $this->getMock(RepositoryQueryInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|RepositoryQueryInterface $queryMock */
        $queryMock
            ->expects($this->atLeastOnce())
            ->method('getConditions')
            ->willReturn(
                [
                    DataModelInterface::ID => [$unknownMode, self::TEST_ID1]
                ]
            );

        $memoryDriver = new InMemoryStorageDriver();
        $memoryDriver->getEntitiesByQuery($queryMock);
    }
}
