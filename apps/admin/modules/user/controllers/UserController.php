<?php
namespace modules\user\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\User\Model\User;

use Illuminate\Support\Facades\DB;

class UserController extends BackendController
{
	public function _initialize(){
		$this->current_module = 'user';
	}
	
	public function getList()
	{
		$pageIndex = Input::get('page',1);
		$search = Input::only('mobile','name');
		$pageSize = 10;
		$data = array();
		$data['datalist'] = User::getList($search,$pageIndex,$pageSize);
		$data['search'] = $search;
		$total = User::getCount($search);
		$pager = Paginator::make(array(),$total,$pageSize);
		$pager->appends($search);
		$data['pagelinks'] = $pager->links();
		return $this->display('user_list',$data);
	}
	
	public function getAdd()
	{
		$data = array();
		return $this->display('user_info',$data);
	}
	
	public function getEdit($urid)
	{
		$data = array();
		$data['info'] = User::getInfo($urid);
		$data['info']['head_img'] = Config::get('app.img_url').$data['info']['head_img'];
		return $this->display('user_info',$data);
	}
	
	public function postSave()
	{
		$input = Input::only('urid','mobile','card_name','card_sex','card_address','card_id','head_img','old_head_img');
		$head_img = $input['old_head_img'];unset($input['old_head_img']);
        if(Input::hasFile('head_img')){
            $head_img = MyHelp::save_img_no_url(Input::file('head_img'),'head_img');
        }
        $input['head_img'] = $head_img;
		$result = User::save($input);
		if($result){
			return $this->redirect('user/user/list','用户保存成功');
		}else{
			return $this->back('用户保存成功');
		}
	}

	public function postAjaxDel()
	{
		$urid = Input::get('urid');
		if($urid){
			User::del($urid);
		}
		return json_encode(array('state'=>1,'msg'=>'用户删除成功'));
	}

	public function postAjaxReset()
	{
		$urid = Input::get('urid');
		if($urid){
			User::modifyUserInfo($urid,array('identify'=>0));
		}
		return json_encode(array('state'=>1,'msg'=>'用户重新年审成功'));
	}

    public function getVideo($urid)
    {
        $data = array();
        $data['info'] = User::getInfo($urid);
        return $this->display('user_video',$data);
    }

    public function postAjaxOk()
    {
        $urid = Input::get('urid');
        if($urid){
            User::modifyUserInfo($urid,array('identify'=>1));
        }
        return json_encode(array('state'=>1,'msg'=>'用户审核提交成功'));
    }

    public function postAjaxRemove()
    {
        $urid = Input::get('urid');
        if($urid){
            User::modifyUserInfo($urid,array('identify'=>0));
        }
        return json_encode(array('state'=>1,'msg'=>'用户审核提交成功'));
    }

}