<?php
namespace Bybzmt\Blog\Common\Cache;

/**
 * 文章评论列表
 */
class ArticleComments extends ListCache
{
    protected function getRows(array $ids):array
    {
        return $this->_ctx->getLazyRows('Comment', $ids);
    }

    protected function loadData(int $length):array
    {
        $ids = $this->_ctx->getTable('Comment')->getListIds($this->list_id, 0, $length);
        $out = array();
        foreach ($ids as $id) {
            $out[] = $this->list_id.":".$id;
        }
        return $out;
    }


}
