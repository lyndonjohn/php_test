<?php

declare(strict_types=1);

namespace App\Utils;

use App\Class\Comment;
use App\Class\News;
use Carbon\Carbon;

class NewsManager
{
    private static $instance = null;
    private $db;

    private function __construct()
    {
        $this->db = DB::getInstance();
    }

    public static function getInstance()
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
        $rows = $this->db->select('SELECT * FROM news');

        $news = [];
        foreach ($rows as $row) {
            $n = new News();
            $news[] = $n->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
        }

        return $news;
    }

    /**
     * @param string $title
     * @param string $body
     * @return mixed
     */
    public function store(string $title, string $body): mixed
    {
        $query = 'INSERT INTO
            news
        SET
            title = ?,
            body = ?,
            created_at = ?
        ';

        $sql = $this->db->prepare($query);
        $sql->execute(
            [
                $title,
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
        try {
            /**
             * added transaction since we are touching two tables
             */
            $this->db->beginTransaction();

            $sql = $this->db->prepare('DELETE FROM news WHERE id = ?');
            $sql->execute([$id]);

            /**
             * the code below can be prevented if news_id foreign key in comment table
             * was setup as cascade on delete to automatically delete child if parent is deleted.
             */
            foreach ($this->comments($id) as $comment) {
                CommentManager::getInstance()->delete($comment->getId());
            }

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            $this->db->rollback();

            $message = "[" . Carbon::now() . "] Failed to delete news.";
            error_log(
                $message . $e->getMessage() . "\r\n",
                3,
                __DIR__ . "/../../error.log"
            );

            return false;
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function comments(int $id): array
    {
        $rows = $this->db->prepare('SELECT id, body FROM comment WHERE news_id = ?');
        $rows->execute([$id]);

        $comments = [];
        foreach ($rows->fetchAll() as $row) {
            $n = new Comment();
            $comments[] = $n->setId($row['id'])
                ->setBody($row['body']);
        }

        return $comments;
    }
}
