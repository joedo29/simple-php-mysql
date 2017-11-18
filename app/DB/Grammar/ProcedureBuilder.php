<?php

namespace App\DB\Grammar;

use App\DB\Connector\Connection;

/**
 * Class ProcedureBuilder
 * @package App\DB\Grammar
 */
class ProcedureBuilder extends AbstractBuilder
{
    const COMMAND_PROCEDURE = 'call %()';
    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $procedure;

    /**
     * CommandBuilder constructor.
     * @param Connection $connection
     * @param $table
     * @param string $command
     */
    public function __construct(Connection $connection, $table, $command = 'UPDATE')
    {
        parent::__construct($connection, $table);
        $this->command = $command;
    }

    /**
     * @param string $procedure
     * @param array $data
     * @return $this
     */
    public function update(string $procedure, array $data)
    {
        $this->procedure = $procedure;
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = $this->wrapValue($value);
        }

        $this->data = $values;

        $this->getResults();

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $query = sprintf(self::COMMAND_PROCEDURE, $this->procedure, implode(',', $this->data));

        return $query;
    }
}