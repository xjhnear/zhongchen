<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\post\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\User\Model\Post;
use Youxiduo\User\Model\Comment;

class PostController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'post';
    }

    public function getList()
    {
		$data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
		$search = array();

        $data['datalist'] = Post::getList($pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Post::getCount();
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('post-list', $data);
    }

    public function getCommentlist()
    {
        $data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
        $search = Input::only('pid','content');
        $search['type'] = 3;

        $data['datalist'] = Comment::getList($search,$pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Comment::getCount($search);
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('comment-list', $data);
    }
}