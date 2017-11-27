<?php

namespace App\DB\Grammar;

use App\DB\Connector\Connection;

/**
 * Class AbstractBuilder
 * @package App\DB\Grammar
 */
abstract class AbstractBuilder
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var array
     */
    protected $conditions;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var string
     */
    protected $sort;

    /**
     * AbstractBuilder constructor.
     * @param Connection $connection
     * @param $table
     */
    public function __construct(Connection $connection, $table = null)
    {
        $this->table = $table;
        $this->connection = $connection;
    }

    /**
     * ['title' => 'me', 'author' => 'Paul]
     * @param $column
     * @param $operator
     * @param $value
     */
    public function where($columns, $operator = null, $value = null)
    {
        if (is_array($columns)) {
            foreach ($columns as $key => $value) {
                $this->where($key, $value);//$this->where($key, '=', $value)
            }
            return;
        }
        if (!$value) {//In case we receive 2 params: 1st is column, 2nd($operator) is value
            $value = $operator;
            $operator = '=';
        }
        $this->conditions[] = new Condition($columns, $operator, $value);
    }

    /**
     * @param $table
     * @param $on
     */
    public function join($table, $on)
    {
        $this->joins = new Join('JOIN', $table, $on);
    }

    /**
     * @param $table
     * @param $on
     */
    public function leftJoin($table, $on)
    {
        $this->joins[] = new Join('LEFT JOIN', $table, $on);
    }

    /**
     * @param int $limit
     */
    public function limit(int $limit)
    {
        $this->limit($limit);
    }

    /**
     * @param $column
     * @param string $sort
     */
    public function orderBy($column, $sort = 'DESC')
    {
        $this->sort = sprintf('ORDER BY %s %s', $column, $sort);
    }

    /**
     * @param bool $fetch
     * @return array
     */
    public function getResults($fetch = true)
    {
//         print_r($this->getQuery());
        return $this->connection->query($this->getQuery(), $fetch);
    }

    /**
     * Exec query
     */
    public function execute()
    {
        $this->connection->execute($this->getQuery());
    }

    /**
     * @return string
     */
    abstract public function getQuery(): string;

    protected function wrapValue($value)
    {
        if (is_numeric($value)) {
            $number = (int)$value;
            if ($this->doubleCheck((string)$number, $value))
                return $number;
            $number = (float)$value;
            if ($this->doubleCheck((string)$number, $value))
                return $number;
            $number = (double)$value;
            if ($this->doubleCheck((string)$number, $value))
                return $number;
            return $value;
        }
        return sprintf('"%s"', $value);
    }

    /**
     * @param string $string
     * @param $value
     * @return bool
     */
    private function doubleCheck(string $string, $value)
    {
        return ((string)$string === $value);
    }
}