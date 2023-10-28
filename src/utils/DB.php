<?php

declare(strict_types=1);

namespace App\Utils;

use PDO;
use PDOStatement;

class DB
{
    private PDO $pdo;
    private string $host = "127.0.0.1";
    private string $db_name = "phptest";
    private string $user = "root";
    private string $password = "";

    private static $instance = null;

    private function __construct()
    {
        $dsn = 'mysql:dbname=' . $this->db_name . ';host=' . $this->host;
        $this->pdo = new PDO($dsn, $this->user, $this->password);
    }

    /**
     * @return mixed
     */
    public static function getInstance(): mixed
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    /**
     * @param string $sql
     * @return array
     */
    public function select(string $sql): array
    {
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll();
    }

    /**
     * @return string
     */
    public function lastInsertId(): string
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

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
