<?php
declare(strict_types=1);

namespace App\DB\Grammar;

/**
 * Class InsertBuilder
 * @package App\DB\Grammar
 */
class InsertBuilder extends AbstractBuilder
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $data
     * @return $this
     */
    public function insert(array $data)
    {
        $this->attributes = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $query = 'INSERT INTO %s (%s) VALUES (%s)';
        $column = implode(',', array_keys($this->attributes));
        $values = [];
        foreach ($this->attributes as $key => $value) {
            $values[] = $this->wrapValue($value);
        }
        $value = implode(',', $values);

        return sprintf($query, $this->table, $column, $value);
    }

    /**
     * Execute Insert Query
     */
    public function execute()
    {
        $this->getResults();
    }
}