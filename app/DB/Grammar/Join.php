<?php

namespace App\DB\Grammar;

/**
 * Class Join
 * @package App\DB\Grammar
 */
class Join
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $condition;

    /**
     * @var string
     */
    private $type;

    /**
     * Join constructor.
     * @param $type
     * @param string $table
     * @param string $condition
     */
    public function __construct($type, $table, $condition)
    {
        $this->type = $type;
        $this->table = $table;
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('% %s ON %s', $this->type, $this->table, $this->condition);
    }
}