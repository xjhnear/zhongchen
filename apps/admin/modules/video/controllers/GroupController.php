<?php
namespace modules\video\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Youxiduo\User\Model\VideoGroup;

class GroupController extends BackendController
{	
	public function _initialize()
	{
		$this->current_module = 'video';
	}
	
	public function getList()
	{
		$data = array();
		$data['datalist'] = VideoGroup::getList(1,20);
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
		$data['group'] = VideoGroup::getInfo($group_id);
		return $this->display('group_info',$data);
	}
	
	public function postSave()
	{
		$input = Input::only('id','title');
		$data['vgid'] = $input['id'];
		$data['title'] = $input['title'];

		$group_id = VideoGroup::save($data);
		
		return $this->redirect('video/group/list','分类修改成功');
	}

	public function getDelete($vgid=0)
	{
		if($vgid){
			VideoGroup::del($vgid);
		}
		return $this->redirect('video/group/list','分类删除成功');
	}

}