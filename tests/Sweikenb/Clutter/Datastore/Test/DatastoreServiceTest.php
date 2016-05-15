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
use Sweikenb\Clutter\Datastore\API\RepositoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\Service\DatastoreService;

/**
 * Class DatastoreServiceTest
 *
 * @package Sweikenb\Clutter\Datastore\Test
 */
class DatastoreServiceTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID = 'ID-1234567890';
    const TEST_RESPONSE = 'ID-1234567890';

    /**
     * @test
     */
    public function implementation()
    {
        // prepare mock
        $repositoryMock = $this->getMock(RepositoryInterface::class);
        $dataModelMock = $this->getMock(DataModelInterface::class);
        $queryMock = $this->getMock(RepositoryQueryInterface::class);

        $repositoryMock
            ->expects($this->once())
            ->method('getObject')
            ->with(self::TEST_ID)
            ->willReturn(self::TEST_RESPONSE);

        $repositoryMock
            ->expects($this->exactly(2))
            ->method('getList')
            ->with(
                $this->logicalOr(
                    $this->equalTo(null),
                    $this->equalTo($queryMock)
                )
            )
            ->willReturn(self::TEST_RESPONSE);

        $repositoryMock
            ->expects($this->exactly(2))
            ->method('getCount')
            ->with(
                $this->logicalOr(
                    $this->equalTo(null),
                    $this->equalTo($queryMock)
                )
            )
            ->willReturn(self::TEST_RESPONSE);

        $repositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($dataModelMock)
            ->willReturn(self::TEST_RESPONSE);

        $repositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($dataModelMock)
            ->willReturn(self::TEST_RESPONSE);

        /* @var \PHPUnit_Framework_MockObject_MockObject|RepositoryInterface $repositoryMock */
        /* @var \PHPUnit_Framework_MockObject_MockObject|DataModelInterface $dataModelMock */
        /* @var \PHPUnit_Framework_MockObject_MockObject|RepositoryQueryInterface $queryMock */
        $datastoreService = new DatastoreService($repositoryMock);
        $this->assertAttributeEquals($repositoryMock, 'repository', $datastoreService);

        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->get(self::TEST_ID));
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->getAll());
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->countAll());
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->set($dataModelMock));
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->delete($dataModelMock));
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->filter($queryMock));
        $this->assertEquals(self::TEST_RESPONSE, $datastoreService->filterCount($queryMock));
    }

}
