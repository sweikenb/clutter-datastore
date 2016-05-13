<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Test;

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\Model\DataModel;

/**
 * Class DataModelTest
 *
 * @package Sweikenb\Clutter\Datastore\Test
 */
class DataModelTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID1 = 'ID-1234567890';
    const TEST_ID2 = 'ID-0987654321';

    /*
     *
     * HINT:
     *  As the DataModel extens the native \ArrayObject and is marked as final, only additional methods will be tested here.
     *
     */

    /**
     * @test
     */
    public function objectId()
    {
        // test initialisation data
        $dataObject = new DataModel(self::TEST_ID1);
        $this->assertEquals(self::TEST_ID1, $dataObject->getId());
        $this->assertEquals(self::TEST_ID1, $dataObject->offsetGet(DataModelInterface::ID));
        $this->assertEquals([DataModelInterface::ID => self::TEST_ID1], $dataObject->getIterator()->getArrayCopy());

        // test same scenarios with overwritten id
        $dataObject->setId(self::TEST_ID2);
        $this->assertEquals(self::TEST_ID2, $dataObject->getId());
        $this->assertEquals(self::TEST_ID2, $dataObject->offsetGet(DataModelInterface::ID));
        $this->assertEquals([DataModelInterface::ID => self::TEST_ID2], $dataObject->getIterator()->getArrayCopy());
    }

    /**
     * @test
     */
    public function objectData()
    {
        $dataArray = [
            'foo' => 'bar',
            'bar' => [
                'baz' => 42
            ]
        ];

        // simple case
        $dataObject = new DataModel(self::TEST_ID1, $dataArray);
        $this->assertEquals(array_merge($dataArray, [DataModelInterface::ID => self::TEST_ID1]), $dataObject->getIterator()->getArrayCopy());

        // constructor ID must overwrite the array ID
        $dataObject2 = new DataModel(self::TEST_ID2, array_merge($dataArray, [DataModelInterface::ID => self::TEST_ID1]));
        $this->assertEquals(array_merge($dataArray, [DataModelInterface::ID => self::TEST_ID2]), $dataObject2->getIterator()->getArrayCopy());

        // if no ID is provided, no ID should be generated
        $dataObject3 = new DataModel(null, $dataArray);
        $this->assertEquals($dataArray, $dataObject3->getIterator()->getArrayCopy());
        $this->assertArrayNotHasKey(DataModelInterface::ID, $dataObject3);
        $this->assertNull($dataObject3->getId());
    }
}
