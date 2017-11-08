<?php
namespace Bybzmt\Blog\Common\Table;

use Bybzmt\Blog\Common;
use PDO;

class Comment extends Common\Table
{
    protected $_dbName = 'blog';
    protected $_tableName = 'comments';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'article_id',
        'user_id',
        'content',
        'addtime',
        'status',
        'cache_replys_id',
    ];

    public function getList(int $article_id, int $offset, int $length)
    {
        $sql = "select id from comments where article_id = ? AND status = 1 order by id desc limit $offset, $length";

        $stmt = $this->getSlave()->prepare($sql);
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


}
