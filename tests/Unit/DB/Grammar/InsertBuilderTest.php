<?php

namespace Tests\Unit\DB\Grammar;

use App\DB\Connector\Connection;
use App\DB\Grammar\InsertBuilder;

/**
 * Class InsertBuilderTest
 * @package Tests\Unit\DB\Grammar
 */
class InsertBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $table = 'select_table';

    /**
     * @var InsertBuilder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $conn = $this->createMock(Connection::class);
        $this->builder = new InsertBuilder($conn, $this->table);
    }

    /**
     * @param $data
     * @param $query
     * @dataProvider getInsertData
     */
    public function test_insert($data, $query)
    {
        $this->builder->insert($data);
        static::assertEquals($query, $this->builder->getQuery());
    }

    public function getInsertData()
    {
        return [
            '#1 two field' => [
                'data' => [
                    'name' => 'Paul',
                    'age' => 20
                ],
                'query' => sprintf('INSERT INTO %s (name,age) VALUES ("Paul",20)', $this->table)
            ],
            '#2 two numeric field' => [
                'data' => [
                    'name' => 'Paul',
                    'age' => '20'
                ],
                'query' => sprintf('INSERT INTO %s (name,age) VALUES ("Paul",20)', $this->table)
            ],
        ];
    }
}