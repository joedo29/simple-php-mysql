<?php

namespace App\DB\Connector;

/**
 * Class Connection
 * @package App\DB\Connector
 */
class Connection
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Connection constructor.
     * @param $server
     * @param $database
     * @param $username
     * @param $password
     */
    public function __construct($server, $database, $username, $password)
    {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $query
     * @return array
     */
    public function query(string $query)
    {
        $this->connect();
        $this->statement = $this->conn->prepare($query);
        $this->statement->setFetchMode(\PDO::FETCH_ASSOC);
        $this->statement->execute();
        $resultSet = $this->statement->fetchAll();
        return $resultSet;
    }


    /**
     * @param string $query
     * @return array
     */
    public function execute(string $query)
    {
        $this->connect();
        $this->statement = $this->conn->prepare($query);
        $this->statement->execute();
    }

    /**
     * @param string $query
     */
    public function exec(string $query)
    {
        $this->conn->exec($query);
    }

    public function close()
    {
        $this->statement = null;
        $this->conn = null;
    }

    /**
     * @return $this
     */
    private function connect()
    {
        if (!$this->conn) {
            $conn = new \PDO("mysql:host=$this->server;dbname=$this->database", $this->username, $this->password);
            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
        }
        return $this;
    }
}