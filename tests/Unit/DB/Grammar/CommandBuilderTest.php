<?php

namespace Tests\Unit\DB\Grammar;

use App\DB\Connector\Connection;
use App\DB\Grammar\CommandBuilder;

/**
 * Class CommandBuilderTest
 * @package Tests\Unit\DB\Grammar
 */
class CommandBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $table = 'update_table';
    /**
     * @var CommandBuilder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $connection = $this->createMock(Connection::class);
        $this->builder = new CommandBuilder($connection, $this->table);
    }

    /**
     * @param array $updates
     * @param $where
     * @param $query
     * @dataProvider getUpdateQueries
     */
    public function test_update_query(array $updates, $where, $query)
    {
        $this->builder->update($updates);
        if ($where) {
            foreach ($where as $condition) {
                if (!isset($condition['operator']))
                    $this->builder->where($condition['column'], $condition['value']);
                else
                    $this->builder->where($condition['column'], $condition['operator'], $condition['value']);
            }
        }
        static::assertEquals($query, $this->builder->getQuery());
    }

    /**
     * @param array $deletes
     * @param $where
     * @param $query
     * @dataProvider getDeleteQueries
     */
    public function test_delete_query(array $deletes, $where, $query)
    {
        $this->builder->delete($deletes);
        if ($where) {
            foreach ($where as $condition) {
                if (!isset($condition['operator']))
                    $this->builder->where($condition['column'], $condition['value']);
                else
                    $this->builder->where($condition['column'], $condition['operator'], $condition['value']);
            }
        }
        static::assertEquals($query, $this->builder->getQuery());
    }

    public function getUpdateQueries()
    {
        return [
            '#1 no condition' => [
                'updates' => ['name' => 'Paul', 'age' => 20],
                'where' => null,
                'query' => 'UPDATE update_table SET name = "Paul", age = 20'
            ],
            '#2 has condition' => [
                'updates' => ['name' => 'Paul', 'age' => 20],
                'where' => [
                    [
                        'column' => 'name',
                        'operator' => 'LIKE',
                        'value' => '%Aan'
                    ]
                ],
                'query' => 'UPDATE update_table SET name = "Paul", age = 20 WHERE name LIKE "%Aan"'
            ]
        ];
    }

    public function getDeleteQueries()
    {
        return [
            '#1 no condition' => [
                'deletes' => ['name' => 'Paul', 'age' => 20],
                'where' => null,
                'query' => 'DELETE FROM update_table WHERE name = "Paul" AND age = 20'
            ],
            '#2 has condition' => [
                'deletes' => ['name' => 'Paul', 'age' => 20],
                'where' => [
                    [
                        'column' => 'last_name',
                        'operator' => 'LIKE',
                        'value' => '%Aan'
                    ]
                ],
                'query' => 'DELETE FROM update_table WHERE name = "Paul" AND age = 20 AND last_name LIKE "%Aan"'
            ]
        ];
    }
}