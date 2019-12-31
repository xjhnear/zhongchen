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
use Youxiduo\Product\Model\Product;
use Youxiduo\User\Model\Comment;

class OrderController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'order';
        //付款状态 0-未付款 1-已付定金 2-已付全款
        $this->payStatus_arr = [
            0 => '未付款',
            1 => '已付定金',
            2 => '已付全款',
        ];
        //1-新建 2-到款 3-开始生产 4-生产完成 5-已发货 6-已签收 7-尾款结清 8-已安排调试 9-完成
        $this->status_arr = [
            1 => '新建',
            2 => '到款',
            3 => '开始生产',
            4 => '生产完成',
            5 => '已发货',
            6 => '已签收',
            7 => '尾款结清',
            8 => '已安排调试',
            9 => '完成',
        ];
    }

    public function getList()
    {
		$data = array();
        $pageIndex = Input::get('page',1);
        $pageSize = 10;
		$search = array();

        $data['datalist'] = Order::getList($search,$pageIndex,$pageSize);
        $data['search'] = $search;
        $total = Order::getCount($search);
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['payStatus_arr'] = $this->payStatus_arr;
        $data['status_arr'] = $this->status_arr;
        $data['pagelinks'] = $pager->links();
        return $this->display('order-list', $data);
    }

    public function getAdd()
    {
        $data = array();
        $data['payStatus_arr'] = $this->payStatus_arr;
        $data['status_arr'] = $this->status_arr;
        $data['pr_arr'] =  Product::getList([],1,100);
        return $this->display('order-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('urid', 'orderNo', 'tel','price','status');

        $data['title'] = $input['title'];
//        $data['summary'] = $input['summary'];
//        $data['content'] = $input['content'];
//        $data['gid'] = $input['gid'];
//        if(Input::hasFile('img')){
//            $img = MyHelp::save_img_no_url(Input::file('img'),'order_img');
//            $data['img'] = $img;
//        }

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
        $data['payStatus_arr'] = $this->payStatus_arr;
        $data['status_arr'] = $this->status_arr;
        $data['pr_arr'] =  Product::getList([],1,100);
        return $this->display('order-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'urid', 'orderNo', 'tel','price','status');
        
        $data['orid'] = $input['id'];
        $data['orderNo'] = $input['orderNo'];
        $data['tel'] = $input['tel'];
        $data['price'] = $input['price'];
//        $data['gid'] = $input['gid'];
//        $img = $input['old_img'];unset($input['old_img']);
//        if(Input::hasFile('img')){
//            $img = MyHelp::save_img_no_url(Input::file('img'),'order_img');
//        }
//        $data['img'] = $img;

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

    public function postAjaxDel()
    {
        $id = Input::get('orid');
        if($id){
            Order::del($id);
        }
        return json_encode(array('state'=>1,'msg'=>'删除成功'));
    }
}