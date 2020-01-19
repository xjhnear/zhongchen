<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\article\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\User\Model\Article;
use Youxiduo\User\Model\Comment;
use Youxiduo\User\Model\ArticleGroup;

class ArticleController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'article';
    }

    public function getList()
    {
		$data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
		$search = array();

        $data['datalist'] = Article::getList($pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Article::getCount();
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('article-list', $data);
    }

    public function getAdd()
    {
        $data = array();
//        $groups = ArticleGroup::getNameList();
//        $data['groups'] = $groups;
        return $this->display('article-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('title', 'content', 'summary','img','gid');

        $data['title'] = $input['title'];
        $data['summary'] = $input['summary'];
        $data['content'] = $input['content'];
//        $data['gid'] = $input['gid'];
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'article_img');
            $data['img'] = $img;
        }

        $result = Article::save($data);
        
        if ($result) {
            return $this->redirect('article/article/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function getEdit($id)
    {
        $data = array();
        $data['data'] = Article::getInfo($id);
//        $groups = ArticleGroup::getNameList();
//        $data['groups'] = $groups;
        $data['data']['img'] = Config::get('app.img_url').$data['data']['img'];
        return $this->display('article-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'title', 'content', 'summary','img','old_img','gid');
        
        $data['arid'] = $input['id'];
        $data['title'] = $input['title'];
        $data['summary'] = $input['summary'];
        $data['content'] = $input['content'];
//        $data['gid'] = $input['gid'];
        $img = $input['old_img'];unset($input['old_img']);
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'article_img');
        }
        $data['img'] = $img;

        $result = Article::save($data);
        
        if ($result) {
            return $this->redirect('article/article/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function getCommentlist()
    {
        $data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
        $search = Input::only('pid','content');
        $search['type'] = 1;

        $data['datalist'] = Comment::getList($search,$pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Comment::getCount($search);
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('comment-list', $data);
    }

    public function postAjaxDel()
    {
        $id = Input::get('id');
        if($id){
            Article::del($id);
        }
        return json_encode(array('state'=>1,'msg'=>'删除成功'));
    }
}