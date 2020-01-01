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
use Youxiduo\Order\OrderService;
use Youxiduo\Product\Model\Product;
use Youxiduo\User\UserService;
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
        $data['pr_arr'] =  Product::getList(array(),1,100);
        return $this->display('order-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('urid', 'orderNo', 'name','tel','address','idCard','price','contractTime','payTime','payStatus','status','receiptType','receiptTitle','receiptContent','keys','values','prids','numbers');

        $data['orderNo'] = $input['orderNo'];
        $data['name'] = $input['name'];
        $data['tel'] = $input['tel'];

        $result_pwd = UserService::getUserInfobyMobile($input['tel']);
        if($result_pwd['result']){
            $urid = $result_pwd['data']['urid'];
        } else {
            $user = UserService::createUserByPhone($input['tel'], 123456, 0);
            $urid = $user['data'];
        }
        $data['urid'] = $urid;

        $data['address'] = $input['address'];
        $data['idCard'] = $input['idCard'];
        $data['createUrid'] = '';
        $data['contractTime'] = $input['contractTime'];
        $data['payTime'] = $input['payTime'];
        $data['price'] = $input['price'];
        $data['payStatus'] = $input['payStatus'];
        $data['status'] = $input['status'];
        $data['receiptType'] = $input['receiptType'];
        $data['receiptTitle'] = $input['receiptTitle'];
        $data['receiptContent'] = $input['receiptContent'];
        $extrainfo = [];
        foreach ($input['keys'] as $k=>$v) {
            if (strlen($v) > 0) {
                $item = [];
                $item['title'] = $v;
                $item['content'] = $input['values'][$k];
                $extrainfo[] = $item;
            }
        }
        $data['contacts'] = json_encode($extrainfo);

        $result = Order::save($data);
        foreach ($input['prids'] as $k=>$v) {
            if (strlen($v) > 0) {
                $data_pr = [];
                $data_pr['orid'] = $result;
                $data_pr['prid'] = $v;
                $data_pr['number'] = $input['numbers'][$k];
                OrderService::createOrderProduct($data_pr);
            }
        }

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
        $data['data_orderproduct'] = OrderService::getOrderProduct($id);
        $data['pr_arr'] =  Product::getList(array(),1,100);
        return $this->display('order-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'urid', 'orderNo', 'name','tel','address','idCard','price','contractTime','payTime','payStatus','status','receiptType','receiptTitle','receiptContent','keys','values','prids','numbers');

        $data['orid'] = $input['id'];
        $data['orderNo'] = $input['orderNo'];
        $data['name'] = $input['name'];
        $data['tel'] = $input['tel'];

        $result_pwd = UserService::getUserInfobyMobile($input['tel']);
        if($result_pwd['result']){
            $urid = $result_pwd['data']['urid'];
        } else {
            $user = UserService::createUserByPhone($input['tel'], 123456, 0);
            $urid = $user['data'];
        }
        $data['urid'] = $urid;

        $data['address'] = $input['address'];
        $data['idCard'] = $input['idCard'];
        $data['createUrid'] = '';
        $data['contractTime'] = $input['contractTime'];
        $data['payTime'] = $input['payTime'];
        $data['price'] = $input['price'];
        $data['payStatus'] = $input['payStatus'];
        $data['status'] = $input['status'];
        $data['receiptType'] = $input['receiptType'];
        $data['receiptTitle'] = $input['receiptTitle'];
        $data['receiptContent'] = $input['receiptContent'];
        $extrainfo = [];
        foreach ($input['keys'] as $k=>$v) {
            if (strlen($v) > 0) {
                $item = [];
                $item['title'] = $v;
                $item['content'] = $input['values'][$k];
                $extrainfo[] = $item;
            }
        }
        $data['contacts'] = json_encode($extrainfo);

        $result = Order::save($data);
        foreach ($input['prids'] as $k=>$v) {
            if (strlen($v) > 0) {
                $data_pr = [];
                $data_pr['orid'] = $data['orid'];
                $data_pr['prid'] = $v;
                $data_pr['number'] = $input['numbers'][$k];
                OrderService::createOrderProduct($data_pr);
            }
        }
        
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