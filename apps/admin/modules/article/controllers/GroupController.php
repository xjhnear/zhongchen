<?php
namespace modules\article\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Youxiduo\User\Model\ArticleGroup;

class GroupController extends BackendController
{	
	public function _initialize()
	{
		$this->current_module = 'article';
	}
	
	public function getList()
	{
		$data = array();
		$data['datalist'] = ArticleGroup::getList(1,20);
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
		$data['group'] = ArticleGroup::getInfo($group_id);
		return $this->display('group_info',$data);
	}
	
	public function postSave()
	{
		$input = Input::only('id','title');
		$data['agid'] = $input['id'];
		$data['title'] = $input['title'];

		$group_id = ArticleGroup::save($data);
		
		return $this->redirect('article/group/list','分类修改成功');
	}

	public function getDelete($agid=0)
	{
		if($agid){
			ArticleGroup::del($agid);
		}
		return $this->redirect('article/group/list','分类删除成功');
	}

}