<?php
namespace Bybzmt\Blog\Admin\Controller\Blog;

use Bybzmt\Blog\Common\Helper\Pagination;
use Bybzmt\Blog\Admin\Reverse;
use Bybzmt\Blog\Admin\Controller\AuthWeb;

class ArticleList extends AuthWeb
{
    public $type;
    public $search;
    public $status;

    public $_page;
    public $_offset;
    public $_length = 10;

    public function init()
    {
        $this->_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->type = isset($_GET['type']) ? (int)$_GET['type'] : 1;
        $this->status = isset($_GET['status']) ? (int)$_GET['status'] : 0;
        $this->keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($this->_page < 1) {
            $this->_page = 1;
        }
        $this->_offset = ($this->_page-1) * $this->_length;

        if (!in_array($this->type, [1,2,3,4,5])) {
            $this->type = 1;
        }
    }

    public function show()
    {
        //查出所有管理组
        list($articles, $count) = $this->_ctx->getService("Blog")
            ->getArticleList($this->type, $this->keyword, $this->_offset, $this->_length);

        array_walk($articles, function($article){
            $article->author = $this->_ctx->getLazyRow("User", $article->user_id);
        });

        $this->render(array(
            'sidebarMenu' => '文章管理',
            'pagination' => $this->pagination($count),
            'articles' => $articles,
            'search_type' => $this->type,
            'search_status' => $this->status,
            'search_keyword' => $this->keyword,
        ));
    }

    protected function pagination($count)
    {
        return Pagination::style2($count, $this->_length, $this->_page, function($page){
            $params = array();

            if ($this->keyword) {
                $params['type'] = $this->type;
                $params['search'] = $this->status;
            }

            if ($page > 1) {
                $params['page'] = $page;
            }

            return Reverse::mkUrl('Blog.ArticleList', $params);
        });
    }


}
