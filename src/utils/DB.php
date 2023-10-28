<?php

declare(strict_types=1);

namespace App\Utils;

use PDOStatement;

class DB
{
    private $pdo;

    private static $instance = null;

    private function __construct()
    {
        $dsn = 'mysql:dbname=phptest;host=127.0.0.1';
        $user = 'root';
        $password = '';

        $this->pdo = new \PDO($dsn, $user, $password);
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    public function select($sql)
    {
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll();
    }

    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @param string $sql
     * @return bool|PDOStatement
     */
    public function prepare(string $sql): bool|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}
