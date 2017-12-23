<?php
namespace Bybzmt\Blog\Admin\Table;

use Bybzmt\Blog\Common;
use Bybzmt\Blog\Admin;

class Comment extends Common\Table\Comment
{
    private function buildWhere(int $type, string $search)
    {
        if ($search) {
            switch ($type) {
            case 1: //用户id
                $sql2 = "select id from users where id = ?";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 2: //用户名
                $sql2 = "select id from users where user link ? limit 1000";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 3: //用户昵称
                $sql2 = "select id from users where nickname link ? limit 1000";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 4: //文章id
                return ['article_id'=>$search];
            case 5: //文章标题
                $sql2 = "select id from articles where title link ? limit 1000";
                $ids = $this->getSlave()->fetchColumnAll($sql, [$search]);
                if (!$ids) { return $empty; }

                return ['user_id'=>$ids];
            case 6: //评论内容
                return ['content'=>$search];
            }
        }

        //无限制条件
        return [];
    }

    //得到后台列表
    public function getAdminList(int $type, string $search, int $offset, int $length)
    {
        $tmps = $this->buildWhere($type, $search);
        $str = [];
        $vals = [];
        foreach ($tmps as $key => $val) {
            if (is_array($val)) {
                $str[] = "`key` in (?".str_repeat(", ?", count($val)-1).")";
                $vals = array_merge($vals, $val);
            } else {
                $str[] = "`$key` = ?";
                $vals[] = $val;
            }
        }

        $where = $str ? " AND " . implode(" AND ", $str) : "";

        $sql = "select * from comments where status > 0 $where LIMIT $offset, $length";
        $sql2 = "select COUNT(*) from comments where status > 0 " . $where;

        $rows = $this->getSlave()->fetchAll($sql, $vals);

        $count = $this->getSlave()->fetchColumn($sql2, $vals);

        return [$rows, $count];
    }
}
