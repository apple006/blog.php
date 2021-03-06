<?php
namespace Bybzmt\Blog\Common\Service;

use Parsedown;
use Bybzmt\Framework\Service;
use Bybzmt\Blog\Common\Row\User;

class Article extends Service
{

    //首页列表 (从首页列表缓存中取)
    public function getIndexList(int $offset, int $length)
    {
        return $this->getCache('IndexArticles')->getlist($offset, $length);
    }

    //首页列表文章数量 (从首页列表缓存中取)
    public function getIndexCount()
    {
        return $this->getCache('IndexArticles')->count();
    }

    public function getTag(string $name)
    {
        $row = $this->getTable("Tag")->getTag($name);
        if (!$row) {
            return false;
        }

        return $this->initRow("Tag", $row);
    }

    public function addTag(string $name)
    {
        $data = array(
            'name' => $name,
            'sort' => 0,
            'top' => 0,
            'status' => 1,
        );

        return $this->getTable("Tag")->insert($data);
    }

    public function addArticle(User $user, $title, $intro, $content)
    {
        $content = strip_tags($content);

        $parsedown = new Parsedown();
        $html = $parsedown->text($content);

        $data = array(
            'user_id' => $user->id,
            'title' => $title,
            'intro' => $intro,
            'content' => $content,
            'html' => $html,
            'addtime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'edittime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
            'status' => 1,
            'top' => 0,
            '_tags' => '',
            '_comments_num' => 0,
        );

        //保存数据
        $id = $this->getTable('Article')->insert($data);

        return $id;
    }

    /**
     * 得到用户文章列表
     */
    public function getUserList(User $user, int $offset, int $length)
    {
        $ids = $this->getTable("Article")->getUserListIds($user->id, $offset, $length);

        return $this->getLazyRows("Article", $ids);
    }

    /**
     * 得到用户文章列表数量
     */
    public function getUserListCount(User $user)
    {
        return $this->getTable("Article")->getUserListCount($user->id);
    }

}
