<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\product\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\Product\Model\Product;
use Youxiduo\Product\Model\ProductGroup;
use Youxiduo\User\Model\Comment;

class ProductController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'product';
    }

    public function getList()
    {
        $pageIndex = Input::get('page',1);
        $search = Input::only('group_id','name');
        $orderby = Input::only('key','value');
        $pageSize = 10;
        $data = array();
        $data['datalist'] = Product::getList($search,$pageIndex,$pageSize,$orderby);
        $groups = ProductGroup::getNameList();
        $groups[0] = '全部';
        $data['groups'] = $groups;
        $data['search'] = $search;
        $data['keys'] = array('1'=>'ID','2'=>'价格');
        $data['values'] = array('1'=>'降序','2'=>'升序');
        $data['orderby'] = $orderby;
        $total = Product::getCount($search);
        $pager = Paginator::make(array(),$total,$pageSize);
        $pager->appends($search);
        $data['pagelinks'] = $pager->links();
        return $this->display('product-list', $data);
    }

    public function getAdd()
    {
        $data = array();
        $data['state'] = 1;
        $groups = ProductGroup::getNameList();
        $data['groups'] = $groups;
        return $this->display('product-add', $data);
    }
    
    public function postAdd()
    {
        $input = Input::only('name', 'gid', 'content', 'specs','img','price','remarks','extrainfo','state','keys','values');

        $data['name'] = $input['name'];
        $data['gid'] = $input['gid'];
        $data['specs'] = $input['specs'];
        $data['content'] = $input['content'];
        $data['price'] = $input['price'];
        $data['remarks'] = $input['remarks'];
        $extrainfo = [];
        foreach ($input['keys'] as $k=>$v) {
            if (strlen($v) > 0) {
                $item = [];
                $item['title'] = $v;
                $item['content'] = $input['values'][$k];
                $extrainfo[] = $item;
            }
        }
        $data['extrainfo'] = json_encode($extrainfo);
        $data['state'] = $input['state'];
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'product_img');
            $data['img'] = $img;
        }

        $result = Product::save($data);
        
        if ($result) {
            return $this->redirect('product/product/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function getEdit($id)
    {
        $data = array();
        $groups = ProductGroup::getNameList();
        $data['groups'] = $groups;
        $data['data'] = Product::getProductInfoById($id);
        return $this->display('product-edit', $data);
    }

    public function postEdit()
    {
        $input = Input::only('id', 'name', 'content', 'gid', 'specs','img','price','remarks','extrainfo','state','old_img','keys','values');
        
        $data['prid'] = $input['id'];
        $data['name'] = $input['name'];
        $data['gid'] = $input['gid'];
        $data['specs'] = $input['specs'];
        $data['content'] = $input['content'];
        $data['price'] = $input['price'];
        $data['remarks'] = $input['remarks'];
        $extrainfo = [];
        foreach ($input['keys'] as $k=>$v) {
            if (strlen($v) > 0) {
                $item = [];
                $item['title'] = $v;
                $item['content'] = $input['values'][$k];
                $extrainfo[] = $item;
            }
        }
        $data['extrainfo'] = json_encode($extrainfo);
        $img = $input['old_img'];unset($input['old_img']);
        if(Input::hasFile('img')){
            $img = MyHelp::save_img_no_url(Input::file('img'),'product_img');
        }
        $data['img'] = $img;

        $result = Product::save($data);
        
        if ($result) {
            return $this->redirect('product/product/list')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

    public function postAjaxDel()
    {
        $id = Input::get('prid');
        if($id){
            Product::delById($id);
        }
        return json_encode(array('state'=>1,'msg'=>'删除成功'));
    }
}