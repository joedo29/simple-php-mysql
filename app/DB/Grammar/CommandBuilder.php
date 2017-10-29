<?php

namespace App\DB\Grammar;

use App\DB\Connector\Connection;

/**
 * Class CommandBuilder
 * @package App\DB\Grammar
 */
class CommandBuilder extends AbstractBuilder
{
    const COMMAND_UPDATE = 'update';
    const COMMAND_DELETE = 'delete from';
    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $updates;

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
     * @param $data
     * @return $this
     */
    public function update(array $data)
    {
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = sprintf('%s = %s', $key, $this->wrapValue($value));
        }
        $this->updates = $updates;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function delete(array $data)
    {
        $this->command = self::COMMAND_DELETE;
        foreach ($data as $key => $value) {
            $this->where($key, $value);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $query = sprintf('%s %s',
            strtoupper($this->command),
            $this->table
        );
        if (count($this->updates))
            $query = sprintf('%s SET %s', $query, implode(', ', $this->updates));
        if (count($this->conditions))
            $query = sprintf('%s WHERE %s',
                $query,
                count($this->conditions) === 0 ? '' : implode(' AND ', $this->conditions)
            );
        return $query;
    }
}