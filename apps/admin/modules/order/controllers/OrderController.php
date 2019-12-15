<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\order\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\Order\Model\Order;
use Youxiduo\User\Model\Comment;

class OrderController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'order';
    }

    public function getList()
    {
		$data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
		$search = array();

        $data['datalist'] = Order::getList($pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Order::getCount();
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('order-list', $data);
    }

    public function getAdd()
    {
        $data = array();
//        $groups = OrderGroup::getNameList();
//        $data['groups'] = $groups;
        return $this->display('order-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('urid', 'name', 'tel','address','gid');

        $data['title'] = $input['title'];
//        $data['summary'] = $input['summary'];
//        $data['content'] = $input['content'];
//        $data['gid'] = $input['gid'];
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'order_img');
            $data['img'] = $img;
        }

        $result = Order::save($data);
        
        if ($result) {
            return $this->redirect('order/order/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function getEdit($id)
    {
        $data = array();
        $data['data'] = Order::getInfo($id);
//        $groups = OrderGroup::getNameList();
//        $data['groups'] = $groups;
        $data['data']['img'] = Config::get('app.img_url').$data['data']['img'];
        return $this->display('order-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'title', 'content', 'summary','img','old_img','gid');
        
        $data['arid'] = $input['id'];
        $data['title'] = $input['title'];
//        $data['summary'] = $input['summary'];
//        $data['content'] = $input['content'];
//        $data['gid'] = $input['gid'];
        $img = $input['old_img'];unset($input['old_img']);
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'order_img');
        }
        $data['img'] = $img;

        $result = Order::save($data);
        
        if ($result) {
            return $this->redirect('order/order/list')->with('global_tips', '保存成功');
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
}