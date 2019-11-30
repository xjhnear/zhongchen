<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\video\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\User\Model\Video;
use Youxiduo\User\Model\Comment;
use Youxiduo\User\Model\VideoGroup;

class VideoController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'video';
    }

    public function getList()
    {
		$data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
		$search = array();

        $data['datalist'] = Video::getList($pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Video::getCount();
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('video-list', $data);
    }

    public function getAdd()
    {
        $data = array();
        $groups = VideoGroup::getNameList();
        $data['groups'] = $groups;
        return $this->display('video-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('title', 'link', 'summary','img','gid');

        $data['title'] = $input['title'];
        $data['summary'] = $input['summary'];
        $data['link'] = $input['link'];
        $data['gid'] = $input['gid'];
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'video_img');
            $data['img'] = $img;
        }

        $result = Video::save($data);
        
        if ($result) {
            return $this->redirect('video/video/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function getEdit($id)
    {
        $data = array();
        $data['data'] = Video::getInfo($id);
        $groups = VideoGroup::getNameList();
        $data['groups'] = $groups;
        $data['data']['img'] = Config::get('app.img_url').$data['data']['img'];
        return $this->display('video-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'title', 'link', 'summary','img','old_img','gid');
        
        $data['vid'] = $input['id'];
        $data['title'] = $input['title'];
        $data['summary'] = $input['summary'];
        $data['link'] = $input['link'];
        $data['gid'] = $input['gid'];
        $img = $input['old_img'];unset($input['old_img']);
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'video_img');
        }
        $data['img'] = $img;

        $result = Video::save($data);
        
        if ($result) {
            return $this->redirect('video/video/list')->with('global_tips', '保存成功');
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
        $search['type'] = 2;

        $data['datalist'] = Comment::getList($search,$pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Comment::getCount($search);
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('comment-list', $data);
    }
}