<?php
namespace Bybzmt\Blog\Common\Cache;

use Bybzmt\Blog\Common;

/**
 * 文章评论列表
 */
class TagArticles extends Common\ListCache
{
    protected function getRows(array $ids):array
    {
        $articles = [];
        foreach ($ids as $id) {
            $articles[] = $this->getLazyRowCache('Article', $id);
        }
        return $articles;
    }

    protected function loadData(int $length):array
    {
        return $this->getTable('ArticleTag')->getArticleIds($this->list_id, 0, $length);
    }


}
