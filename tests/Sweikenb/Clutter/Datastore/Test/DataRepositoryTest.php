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
use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;
use Sweikenb\Clutter\Datastore\API\ResultFactoryInterface;
use Sweikenb\Clutter\Datastore\API\StorageDriverInterface;
use Sweikenb\Clutter\Datastore\Model\DataRepository;

/**
 * Class DataRepositoryTest
 *
 * @package Sweikenb\Clutter\Datastore\Test
 */
class DataRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID1 = 'ID-1234567890';
    const TEST_ID2 = 'ID-0987654321';

    private function getRepositoryObjects()
    {
        $storageDriver = $this->getMock(StorageDriverInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */

        $modelFactory = $this->getMock(ModelFactoryInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|ModelFactoryInterface $modelFactory */

        $resultFactory = $this->getMock(ResultFactoryInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|ResultFactoryInterface $resultFactory */

        $dataRepository = new DataRepository($storageDriver, $modelFactory, $resultFactory);

        return [$dataRepository, $storageDriver, $modelFactory, $resultFactory];
    }

    /**
     * @test
     */
    public function getObjectNoResult()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        list($dataRepository, $storageDriver,) = $this->getRepositoryObjects();

        $storageDriver
            ->expects($this->once())
            ->method('getEntityById')
            ->with(self::TEST_ID1)
            ->willReturn(null);
        $dataRepository->getObject(self::TEST_ID1);
    }

    /**
     * @test
     */
    public function getObjectResult()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        /* @var \PHPUnit_Framework_MockObject_MockObject|ModelFactoryInterface $modelFactory */
        list($dataRepository, $storageDriver, $modelFactory) = $this->getRepositoryObjects();

        $entityData = [
            DataModelInterface::ID => self::TEST_ID2
        ];

        $modelFactory
            ->expects($this->once())
            ->method('create')
            ->with($entityData)
            ->willReturn('OK');

        $storageDriver
            ->expects($this->once())
            ->method('getEntityById')
            ->with(self::TEST_ID2)
            ->willReturn($entityData);

        $this->assertEquals('OK', $dataRepository->getObject(self::TEST_ID2));
    }

    /**
     * @test
     */
    public function getListNoParams()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        /* @var \PHPUnit_Framework_MockObject_MockObject|ModelFactoryInterface $modelFactory */
        /* @var \PHPUnit_Framework_MockObject_MockObject|ResultFactoryInterface $resultFactory */
        list($dataRepository, $storageDriver, $modelFactory, $resultFactory) = $this->getRepositoryObjects();

        $queryMock = null;
        $result = [];
        $resultMock = $this->getMock(RepositoryResultInterface::class);

        $storageDriver
            ->expects($this->once())
            ->method('getEntitiesByQuery')
            ->with($queryMock)
            ->willReturn($result);

        $resultFactory
            ->expects($this->once())
            ->method('create')
            ->with($result)
            ->willReturn($resultMock);

        $this->assertSame($resultMock, $dataRepository->getList($queryMock));
    }

    /**
     * @test
     */
    public function getListWithParams()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        /* @var \PHPUnit_Framework_MockObject_MockObject|ModelFactoryInterface $modelFactory */
        /* @var \PHPUnit_Framework_MockObject_MockObject|ResultFactoryInterface $resultFactory */
        list($dataRepository, $storageDriver, $modelFactory, $resultFactory) = $this->getRepositoryObjects();

        $queryMock = $this->getMock(RepositoryQueryInterface::class);
        $result = [DataModelInterface::ID => self::TEST_ID1];
        $resultMock = $this->getMock(RepositoryResultInterface::class);

        $storageDriver
            ->expects($this->once())
            ->method('getEntitiesByQuery')
            ->with($queryMock)
            ->willReturn($result);

        $resultFactory
            ->expects($this->once())
            ->method('create')
            ->with($result)
            ->willReturn($resultMock);

        $this->assertSame($resultMock, $dataRepository->getList($queryMock));
    }

    /**
     * @test
     */
    public function getResultCountAll()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        list($dataRepository, $storageDriver,) = $this->getRepositoryObjects();

        $queryMock = null;
        $storageDriver
            ->expects($this->once())
            ->method('getEntityCountByQuery')
            ->with($queryMock)
            ->willReturn(123);

        $this->assertEquals(123, $dataRepository->getCount($queryMock));
    }

    /**
     * @test
     */
    public function getResultCountFiltered()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        list($dataRepository, $storageDriver,) = $this->getRepositoryObjects();

        $queryMock = $this->getMock(RepositoryQueryInterface::class);
        $storageDriver
            ->expects($this->once())
            ->method('getEntityCountByQuery')
            ->with($queryMock)
            ->willReturn(5);

        $this->assertEquals(5, $dataRepository->getCount($queryMock));
    }

    /**
     * @test
     */
    public function saveDelete()
    {
        /* @var DataRepository $dataRepository */
        /* @var \PHPUnit_Framework_MockObject_MockObject|StorageDriverInterface $storageDriver */
        list($dataRepository, $storageDriver,) = $this->getRepositoryObjects();

        $dataModelMock = $this->getMock(DataModelInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|DataModelInterface $dataModelMock */

        $dataModelMock
            ->expects($this->exactly(2))
            ->method('getId')
            ->willReturn(static::TEST_ID1);

        $dataMock = [
            DataModelInterface::ID => static::TEST_ID1,
            'foo'                  => 'bar'
        ];
        $dataModelMock
            ->expects($this->once())
            ->method('getArrayCopy')
            ->willReturn($dataMock);

        $storageDriver
            ->expects($this->once())
            ->method('saveEntity')
            ->with(static::TEST_ID1, $dataMock)
            ->willReturn(true);

        $storageDriver
            ->expects($this->once())
            ->method('deleteEntity')
            ->with(static::TEST_ID1)
            ->willReturn(true);

        $this->assertTrue($dataRepository->save($dataModelMock));
        $this->assertTrue($dataRepository->delete($dataModelMock));
    }
}
