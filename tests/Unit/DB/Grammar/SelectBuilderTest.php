<?php

namespace Tests\Unit\DB\Grammar;

use App\DB\Grammar\SelectBuilder;

/**
 * Class SelectBuilderTest
 * @package Tests\Unit\DB\Grammar
 */
class SelectBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $table = 'select_table';

    /**
     * @var SelectBuilder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = new SelectBuilder($this->table);
    }

    /**
     * @param $selector
     * @param $query
     * @dataProvider getSelectQueries
     */
    public function test_get_select_query($selector, $query)
    {
        $this->builder->select($selector);
        static::assertEquals($query, $this->builder->getQuery());
    }

    /**
     * @dataProvider getSelectByConditions
     * @param $selector
     * @param $conditions
     * @param $query
     */
    public function test_get_select_by_condition_query($selector, $conditions, $query)
    {
        $this->builder->select($selector);
        foreach ($conditions as $condition) {
            if (!isset($condition['operator']))
                $this->builder->where($condition['column'], $condition['value']);
            else
                $this->builder->where($condition['column'], $condition['operator'], $condition['value']);
        }

        static::assertEquals($query, $this->builder->getQuery());
    }

    public function getSelectQueries()
    {
        return [
            '#1 one field' => [
                'selector' => ['name'],
                'query' => sprintf('SELECT name FROM %s', $this->table)
            ],
            '#2 select multiple' => [
                'selector' => null,
                'query' => sprintf('SELECT * FROM %s', $this->table)
            ],
            '#3 select multiple fields' => [
                'selector' => ['name', 'dob'],
                'query' => sprintf('SELECT name,dob FROM %s', $this->table)
            ]
        ];
    }

    public function getSelectByConditions()
    {
        return [
            '#1 one field' => [
                'selector' => ['name'],
                'conditions' => [
                    [
                        'column' => 'name',
                        'value' => 'Paul'
                    ]
                ],
                'query' => sprintf('SELECT name FROM %s WHERE name = "Paul"', $this->table)
            ],
            '#2 select multiple' => [
                'selector' => null,
                'conditions' => [
                    [
                        'column' => 'name',
                        'value' => 'Paul'
                    ],
                    [
                        'column' => 'age',
                        'operator' => '>',
                        'value' => 20
                    ],
                    [
                        'column' => 'point',
                        'operator' => '=',
                        'value' => "20.5"
                    ]
                ],
                'query' => sprintf('SELECT * FROM %s WHERE name = "Paul" AND age > 20 AND point = 20.5', $this->table)
            ],
        ];
    }
}