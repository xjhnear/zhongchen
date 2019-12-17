<?php
use Yxd\Services\ThreadService;
use Illuminate\Support\Facades\Response;
use Yxd\Services\AtmeService;
use Yxd\Services\UserFeedService;
use Yxd\Services\RelationService;
use Illuminate\Support\Facades\Input;
use Youxiduo\User\UserService;
use Youxiduo\Helper\MyHelp;

use PHPImageWorkshop\ImageWorkshop;

class UserController extends BaseController
{

	public function login()
	{
		$mobile = Input::get('mobile');
		$verifycode = Input::get('verifycode');
		if(!$mobile){
			return $this->fail(202,'手机号不能为空');
		}
		if(!$verifycode){
			return $this->fail(202,'验证码不能为空');
		}
		$result = UserService::verifyPhoneVerifyCode($mobile, 0, $verifycode);
		if($result['result']){
			$result_pwd = UserService::getUserInfobyMobile($mobile);
			if($result_pwd['result']){
				if ($result_pwd['data']['state'] == 0) {
					return $this->fail(202,'账号已被禁用');
				}
				$urid = $result_pwd['data']['urid'];
				if ($result_pwd['data']['register'] == 0) {
					UserService::modifyUserInfo($urid,['register'=>1]);
				}
				$result = array('urid'=>$urid);
				return $this->success($result);
			} else {
				$user = UserService::createUserByPhone($mobile, $verifycode, 1);
				if($user['result']){
					$urid = array('urid'=>$user['data']);
					return $this->success($urid);
				}else{
					return $this->fail(201,$user['msg']);
				}
			}
		} else {
			return $this->fail(201,$result['msg']);
		}
	}

	public function smsVerify()
	{
		$mobile = Input::get('mobile');
		$type = Input::get('type',0);
		$udid = Input::get('udid');
		if(!$mobile){
			return $this->fail(202,'手机号不能为空');
		}
		$result = UserService::sendPhoneVerifyCode($mobile, $type, $udid);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,'发送验证码错误');
		}
	}

	/**
	 * 换绑手机
	 */
	public function changemobile()
	{
		$mobile = Input::get('mobile');
		$verifycode = Input::get('verifycode');
		$urid = Input::get('urid',0);
		if(!$mobile){
			return $this->fail(202,'手机号不能为空');
		}
		if(!$verifycode){
			return $this->fail(202,'验证码不能为空');
		}
		$result = UserService::verifyPhoneVerifyCode($mobile, 1, $verifycode);
		if($result['result']){
			//更换绑定手机
			$user = UserService::modifyUserMobile($urid, $mobile);
			if($user['result']){
				$urid = array('urid'=>$user['data']);
				return $this->success($urid);
			}else{
				return $this->fail(201,$user['msg']);
			}
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function feedback()
	{
		$contact = Input::get('contact');
		$content = Input::get('content');
		$urid = Input::get('urid',0);
		$result = UserService::saveFeedback($urid, $contact, $content);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function info()
	{
		$urid = Input::get('urid',0);
		if($urid <= 0){
			return $this->fail(202,'参数异常');
		}
		$result = UserService::getUserInfo($urid);
		if (!empty($result['data']['image'])) {
            $result['data']['image'] = Config::get('app.img_url','').$result['data']['image'];
        }
		if($result['result']){
			return $this->success($result['data']);
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function edit()
	{
		$urid = Input::get('urid',0);
		$input = array();
		$input['username'] = Input::get('username');
        if(Input::hasFile('imgkey')){
            $avatar = MyHelp::save_img_no_url(Input::file('imgkey'),'img');
            $input['img'] = $avatar;
        }
		$input['sex'] = Input::get('sex');
        $input['companyId'] = Input::get('companyId',0);
        $input['companyName'] = Input::get('companyName');
		if($urid <= 0){
			return $this->fail(202,'参数异常');
		}
		$result = UserService::modifyUserInfo($urid,$input);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function identification()
	{
		$urid = Input::get('urid',0);
		if($urid <= 0){
			return $this->fail(202,'参数异常');
		}
		$result = UserService::getUseridentify($urid);
		if($result['result']){
			return $this->success($result['data']);
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function identifyrefresh()
	{
		$urid = Input::get('urid',0);
		$input = array();
		$input['identify'] = Input::get('result');
		if($urid <= 0){
			return $this->fail(202,'参数异常');
		}
		$result = UserService::modifyUserInfo($urid,$input);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function identify()
	{
		$urid = Input::get('urid',0);
		$numbers = Input::get('numbers');
		if($urid <= 0 || !$numbers){
			return $this->fail(202,'参数异常');
		}

        $fullPath = public_path().'/downloads/info/';

        $myfile = fopen($fullPath.'newfile.txt', "w+") or die("Unable to open file!");
        fwrite($myfile, json_encode($_FILES));
        fclose($myfile);

        if(Input::hasFile('video')){
            $video = MyHelp::save_img_no_url(Input::file('video'),'video');
        }
		$input['numbers'] = $numbers;
		$input['video'] = $video;
        $input['identify'] = 2;
		$result_user = UserService::modifyUserInfo($urid,$input);
		$result = UserService::getUseridentify($urid);
		if($result['result']){
			return $this->success($result['data']);
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function subuserlist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);
		$urid = Input::get('urid',0);

		$result = UserService::getSubUserList($pageIndex,$pageSize,$urid);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function subuseradd()
	{
		$input = Input::only('mobile','username','companyId','companyName','sex');
		$input['parentId'] = Input::get('urid',0);
		$input['register'] = 0;

		$result = UserService::saveSubUser($input);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function subuseredit()
	{
		$input = Input::only('mobile','username','companyId','companyName','sex');
		$input['urid'] = Input::get('suburid',0);
		$input['parentId'] = Input::get('urid',0);

		$data['info'] = User::getInfo($input['urid']);
		if (!$data['info']) {
			return $this->fail(201,'参数异常');
		}
		if ($data['info']['parentId'] != $input['parentId']) {
			return $this->fail(201,'无权操作');
		}
		$result = UserService::saveSubUser($input);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function subuserdel()
	{
		$input['urid'] = Input::get('suburid',0);
		$input['parentId'] = Input::get('urid',0);

		$data['info'] = User::getInfo($input['urid']);
		if (!$data['info']) {
			return $this->fail(201,'参数异常');
		}
		if ($data['info']['parentId'] != $input['parentId']) {
			return $this->fail(201,'无权操作');
		}
		$input['parentId'] = 0;
		$result = UserService::saveSubUser($input);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}
}