<?php
namespace Bybzmt\Blog\Common\Row;

use Bybzmt\Blog\Common;

class Comment extends Common\Row
{
    const max_cache_replys_num=60;

    public function getReply(int $offset, int $length)
    {
        if ($offset+$length <= intval(strlen($this->_replys_id)/4)) {
            $ids = array_slice($this->_getCacheReplyIds(), $offset, $length);
        } else {
            $ids = $this->_context->getTable('CommentsReply')->getReplyIds($this->id, $offset, $length);
        }

        $rows = [];
        foreach ($ids as $id) {
            $rows[] = $this->_context->getLazyRow('CommentsReply', $id);
        }
        return $rows;
    }

    public function addReply(User $user, Comment $reply=null, string $content)
    {
        $data = array(
            'id' => "{$this->article_id}:",
            'article_id' => $this->article_id,
            'comment_id' => $this->id,
            'reply_id' => $reply ? $reply->id : $this->id,
            'user_id' => $user->id,
            'content' => $content,
            'status' => 1,
            '_replys_id' => '',
        );

        //保存数据
        $id = $this->_context->getTable('Comment')->insert($data);
        if (!$id) {
            return false;
        }

        //给被回复的评论修改缓存记录
        if (intval(strlen($this->_replys_id)/4) < self::max_cache_replys_num) {
            $this->_replys_id .= pack("N", $id);

            $this->_context->getTable('Comment')->update($this->id, ['_replys_id'=>$this->_replys_id]);
        }

        //给用户增加发评论的关联记录
        $this->_context->getTable("Record")->insert(array(
            'id' => "{$user->id}:",
            'user_id' => $user->id,
            'type' => 1,
            'to_id' => $id,
        ));

        return true;
    }

    public function _removeCacheReplysId(int $id)
    {
        $replyIds = $this->_getCacheReplyIds();

        if (array_search($id, $replyIds) !== false) {
            if (count($replyIds) < self::max_cache_replys_num) {
                $ids = array_diff($replyIds, [$id]);
            } else {
                $ids = $this->_context->getTable('CommentReply')->getReplyIds($this->id, 0, self::max_cache_replys_num);
            }

            $this->_setCacheReplyIds($ids);
        }
    }

    public function del()
    {
        //标记删除
        $ok = $this->_context->getTable('Comment')->update($this->id, ['status'=>0]);
        if ($ok) {
            $this->status = 0;

            //删除文章中的评论缓存
            $this->article->delCommentCache($this->id);
        }

        return $ok;
    }

    //恢复评论
    public function restore()
    {
        $ok = $this->_context->getTable("Comment")->update($this->id, array('status'=>1));
        if ($ok) {
            $this->status = 1;

            //重置文章的评论缓存
            $this->article->restCommentCacheNum();
        }

        return $ok;
    }

    protected function _getCacheReplyIds()
    {
        return unpack('N*', $this->_replys_id);
    }

    protected function _setCacheReplyIds(array $ids)
    {
        $str = "";
        foreach ($ids as $id) {
            $str .= pack("N", $id);
        }

        //更新评论记录
        $this->_context->getTable('Comment')->update($this->id, ['_replys_id'=>$str]);
    }
}
