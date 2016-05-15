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
use Sweikenb\Clutter\Datastore\Service\Factory\DataModelFactory;

class DataModelFactoryTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID = 'ID-1234567890';

    /**
     * @test
     */
    public function create()
    {
        $entityData = [
            DataModelInterface::ID => self::TEST_ID,
            'foo'                  => 'bar'
        ];

        $factory = new DataModelFactory();
        $dataModel = $factory->create($entityData);

        $this->assertInstanceOf(DataModelInterface::class, $dataModel);
        $this->assertEquals($entityData, $dataModel->getArrayCopy());
    }
}
