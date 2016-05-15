<?php
/*
 * This file is part of clutter-datastore.
 *
 * (c) 2016 Simon Schröer <code@sweikenb.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sweikenb\Clutter\Datastore\Test\Factory;

use Sweikenb\Clutter\Datastore\API\DataModelInterface;
use Sweikenb\Clutter\Datastore\API\ModelFactoryInterface;
use Sweikenb\Clutter\Datastore\API\RepositoryResultInterface;
use Sweikenb\Clutter\Datastore\Model\DataModel;
use Sweikenb\Clutter\Datastore\Service\Factory\ResultFactory;

class ResultFactoryTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID1 = 'ID-1234567890';
    const TEST_ID2 = 'ID-0987654321';

    /**
     * @test
     */
    public function create()
    {
        $modelFactoryMock = $this->getMock(ModelFactoryInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|ModelFactoryInterface $modelFactoryMock */

        $result1 = [
            DataModelInterface::ID => static::TEST_ID1,
            'foo'                  => 'bar'
        ];

        $result2 = [
            DataModelInterface::ID => static::TEST_ID2,
            'bar'                  => 'baz'
        ];

        $modelFactoryMock
            ->expects($this->exactly(2))
            ->method('create')
            ->with(
                $this->logicalXor(
                    $this->equalTo($result1),
                    $this->equalTo($result2)
                )
            )
            ->willReturnCallback([$this, 'factoryCallback']);

        $resultFactory = new ResultFactory($modelFactoryMock);
        $resultlist = $resultFactory->create([$result1, $result2]);

        $this->assertInstanceOf(RepositoryResultInterface::class, $resultlist);

        foreach ($resultlist as $i => $dataModel) {
            /* @var DataModelInterface $dataModel */
            $this->assertEquals(${'result' . ($i + 1)}, $dataModel->getArrayCopy());
        }
    }

    public function factoryCallback(array $entityData)
    {
        return new DataModel(null, $entityData);
    }
}
