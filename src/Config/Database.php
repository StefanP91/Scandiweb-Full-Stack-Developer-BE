<?php

namespace App\Config;

interface DatabaseInterface
{
    public function getConnection(): \mysqli;
}

class Database implements DatabaseInterface
{
    private const HOST = "localhost";
    private const USER = "root";
    private const PASS = "";
    private const NAME = "ecommerce";

    protected \mysqli $connection;

    public function __construct()
    {
        $this->connection = new \mysqli(self::HOST, self::USER, self::PASS, self::NAME);
        if ($this->connection->connect_error) {
            throw new \Exception("MySQL connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection(): \mysqli
    {
        return $this->connection;
    }
}