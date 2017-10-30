<?php

namespace App\DB;

use App\DB\Connector\Connection;
use App\DB\Grammar\CommandBuilder;
use App\DB\Grammar\InsertBuilder;
use App\DB\Grammar\SelectBuilder;

/**
 * Class AbstractModel
 * @package App\DB
 */
abstract class AbstractModel
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string
     */
    protected $table;

    /**
     * AbstractModel constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $data
     * @return SelectBuilder
     */
    public function select($data)
    {
        $selector = new SelectBuilder($this->connection, $this->table);
        $selector->select($data);

        return $selector;
    }

    /**
     * @param $data
     * @return CommandBuilder
     */
    public function update($data)
    {
        $updater = new CommandBuilder($this->connection, $this->table);
        $updater->update($data);

        return $updater;
    }

    /**
     * @param $data
     * @return CommandBuilder
     */
    public function delete($data)
    {
        $deleting = new CommandBuilder($this->connection, $this->table);
        $deleting->delete($data);

        return $deleting;
    }

    /**
     * @param array $data
     */
    public function insert(array $data)
    {
        $insert = new InsertBuilder($this->connection, $this->table);
        $insert->insert($data);
        $insert->execute();
    }
}
