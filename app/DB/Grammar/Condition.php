<?php

namespace App\DB\Grammar;

/**
 * Class Condition
 * @package App\DB\Grammar
 */
class Condition
{
    const IN_CONDITION = ['in', 'not in'];
    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Condition constructor.
     * @param $column
     * @param $operator
     * @param $value
     */
    public function __construct(string $column, string $operator, $value)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $condition = '%s %s "%s"';
        if(is_numeric($this->value))
            $condition = '%s %s %s';
        if (in_array(strtolower($this->operator), self::IN_CONDITION)) {
            $condition = '%s %s %s';
            $this->value = sprintf('("%s")', implode('","', $this->value));
        }
        $condition = sprintf($condition, $this->column, $this->operator, $this->value);
        return $condition;
    }
}