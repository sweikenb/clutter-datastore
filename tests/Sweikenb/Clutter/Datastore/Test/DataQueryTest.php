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

use Sweikenb\Clutter\Datastore\API\RepositoryQueryInterface;
use Sweikenb\Clutter\Datastore\Exceptions\InvalidQueryConditionModeException;
use Sweikenb\Clutter\Datastore\Model\Transfer\DataQuery;

/**
 * Class DataQueryTest
 *
 * @package Sweikenb\Clutter\Datastore\Test
 */
class DataQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getConditionModes()
    {
        $interfRef = new \ReflectionClass(RepositoryQueryInterface::class);
        $constants = $interfRef->getConstants();

        $dataQuery = new DataQuery();
        foreach ($constants as $constant) {
            $this->assertContains($constant, $dataQuery->getConditionModes());
        }
    }

    /**
     * @test
     */
    public function field()
    {
        $interfRef = new \ReflectionClass(RepositoryQueryInterface::class);
        $conditions = $interfRef->getConstants();

        $i = 0;
        $compare = [];

        $dataQuery = new DataQuery();
        $this->assertEquals([], $dataQuery->getConditions());

        foreach ($conditions as $condition) {
            $field = 'field' . $i;
            $value = 'value' . $i++;
            $compare[$field] = [$condition, $value];
            $dataQuery->field($field, $condition, $value);
        }
        $this->assertEquals($compare, $dataQuery->getConditions());

        $this->expectException(InvalidQueryConditionModeException::class);
        $this->expectExceptionMessage('The given query condition mode is invalid: invalid-condition');
        $dataQuery->field('field', 'invalid-condition', 'value');
    }
}
