<?php

namespace App\DB\Grammar;

/**
 * Class SelectBuilder
 * @package App\DB\Grammar
 */
class SelectBuilder extends AbstractBuilder
{
    /**
     * @var string
     */
    protected $selectors = '*';

    /**
     * @param $data
     * @return $this
     */
    public function select($data)
    {
        if (is_array($data))
            $this->selectors = implode(',', $data);
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $selector = sprintf('SELECT %s FROM %s', $this->selectors, $this->table);
        if (count($this->joins)) {
            $selector = sprintf('%s %s', $selector, implode(',', $this->joins));
        }
        if (count($this->conditions)) {
            $selector = sprintf('%s WHERE %s', $selector, implode(' AND ', $this->conditions));
        }
        return trim(sprintf('%s %s %',
            $selector,
            $this->sort,
            $this->limit > 0 ? sprintf('LIMIT %d', $this->limit) : ''
        ));
    }
}