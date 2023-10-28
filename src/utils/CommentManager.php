<?php

declare(strict_types=1);

namespace App\Utils;

use App\Class\Comment;
use Carbon\Carbon;

class CommentManager
{
    private static $instance = null;
    private $db;

    private function __construct()
    {
        $this->db = DB::getInstance();
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
     * @return array
     */
    public function index(): array
    {
        $rows = $this->db->select('SELECT * FROM comment');

        $comments = [];
        foreach ($rows as $row) {
            $n = new Comment();
            $comments[] = $n->setId($row['id'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at'])
                ->setNewsId($row['news_id']);
        }

        return $comments;
    }

    /**
     * @param string $body
     * @param int $newsId
     * @return mixed
     */
    public function store(string $body, int $newsId): mixed
    {
        $query = 'INSERT INTO
            comment
        SET
            news_id = ?,
            body = ?,
            created_at = ?
        ';

        $sql = $this->db->prepare($query);
        $sql->execute(
            [
                $newsId,
                $body,
                Carbon::now()
            ]
        );

        return $this->db->lastInsertId($sql);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = $this->db->prepare("DELETE FROM comment WHERE news_id = ?");
        $sql->execute([$id]);

        return true;
    }
}
