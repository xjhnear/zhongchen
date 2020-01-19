<?php
namespace modules\product\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Youxiduo\Product\Model\ProductGroup;

class GroupController extends BackendController
{	
	public function _initialize()
	{
		$this->current_module = 'product';
	}
	
	public function getList()
	{
		$data = array();
		$data['datalist'] = ProductGroup::getList(1,20);
		return $this->display('group_list',$data);
	}
	
	public function getAdd()
	{
		$data = array();
		return $this->display('group_info',$data);
	}
	
	public function getEdit($group_id=0)
	{
		$data = array();
		$data['group'] = ProductGroup::getInfo($group_id);
		return $this->display('group_info',$data);
	}
	
	public function postSave()
	{
		$input = Input::only('id','title');
		$data['pgid'] = $input['id'];
		$data['title'] = $input['title'];

		$group_id = ProductGroup::save($data);
		
		return $this->redirect('product/group/list','分类修改成功');
	}

	public function getDelete($pgid=0)
	{
		if($pgid){
			ProductGroup::del($pgid);
		}
		return $this->redirect('product/group/list','分类删除成功');
	}

}