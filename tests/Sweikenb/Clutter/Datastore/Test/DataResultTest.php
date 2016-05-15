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
use Sweikenb\Clutter\Datastore\Model\Transfer\DataResult;

/**
 * Class DataResultTest
 *
 * @package Sweikenb\Clutter\Datastore\Test
 */
class DataResultTest extends \PHPUnit_Framework_TestCase
{
    /*
     *
     * HINT:
     *  As the DataResult extens the native \ArrayObject and is marked as final, only additional methods will be tested here.
     *
     */

    /**
     * @test
     */
    public function implementation()
    {
        $dataModel = $this->getMock(DataModelInterface::class);
        /* @var \PHPUnit_Framework_MockObject_MockObject|DataModelInterface $dataModel */

        $result = new DataResult();

        $this->assertEquals(0, $result->count());
        $this->assertSame($result, $result->addResult($dataModel));
        $this->assertEquals(1, $result->count());
        $this->assertSame($dataModel, $result[0]);
    }
}
