<?php

declare(strict_types=1);

use App\Utils\CommentManager;
use App\Utils\NewsManager;

require_once __DIR__ . '/vendor/autoload.php';

$news = NewsManager::getInstance();
$comment = CommentManager::getInstance();

// add news
//$news->store('Israel Updates', 'Israel attacking gaza');

// delete news
//$news->delete(15);

// add comment
//$comment->store('the quick brown fox', 10);

// delete comment
//$comment->delete(16);

foreach ($news->index() as $row) {
    echo "############ NEWS: " . $row->getTitle() . " ############\n";
    echo "News body: " . $row->getBody() . "\n";

    foreach ($news->comments($row->getId()) as $comment) {
        echo "\tComment " . $comment->getId() . ": " . $comment->getBody() . "\n";
    }

    echo "\n";
}
