<?php
/**
 * @package Youxiduo
 * @category Base 
 * @link http://dev.youxiduo.com
 * @copyright Copyright (c) 2008 Youxiduo.com 
 * @license http://www.youxiduo.com/license
 * @since 4.0.0
 *
 */
namespace Youxiduo\User;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\BaseService;
use Youxiduo\User\Model\Feedback;
use Youxiduo\User\Model\Comment;
use Youxiduo\User\Model\User;
use Youxiduo\User\Model\UserMobile;
use Youxiduo\Company\Model\Company;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class UserService extends BaseService
{

	public static function checkPassword($urid,$password,$register=1)
	{
		$user = User::doLocalLogin($urid,User::IDENTIFY_FIELD_URID,$password,$register);
		$exists = $user ? true : false;
		return array('result'=>$exists,'data'=>$user);
	}

	public static function checkPasswordbyMobile($mobile,$password)
	{
		$user = User::doLocalLogin($mobile,User::IDENTIFY_FIELD_MOBILE,$password);
		$exists = $user ? true : false;
		return array('result'=>$exists,'data'=>$user);
	}

	/**
	 * 发送手机验证码
	 * @param string $mobile 手机号
	 */
	public static function sendPhoneVerifyCode($mobile,$type,$udid,$sms=true)
	{
		if(Utility::validateMobile($mobile)===true){
			$verifycode = Utility::random(6,'alnum');
//			$verifycode = '123456';
			$result = UserMobile::saveVerifyCodeByPhone($mobile,$type,$verifycode,false,$udid);
			$result==true && Utility::sendVerifyAllSMS($mobile,$verifycode,$sms);
			return array('result'=>true,'data'=>$result);
		}
		return array('result'=>false,'msg'=>"手机号无效");
	}
	
	/**
	 * 验证手机验证码
	 * @param string $mobile
	 * @param string $verifycode
	 */
	public static function verifyPhoneVerifyCode($mobile,$type,$verifycode)
	{
//		if ($verifycode == '123456') return array('result'=>true);
		if(Utility::validateMobile($mobile)===true && !empty($verifycode)){
			$num = 0;	
			$result = UserMobile::verifyPhoneVerifyCode($mobile,$type,$verifycode,$num);
			if($result===true){
				return array('result'=>true);
			}else{
				if($num >= 3){
					return array('result'=>false,'msg'=>"验证码已失效,请重新获取");
				}
				return array('result'=>false,'msg'=>"验证码无效");
			}
		}
		return array('result'=>false,'msg'=>"验证码无效");
	}

	/**
	 * 手机注册
	 */
	public static function createUserByPhone($mobile,$password,$register)
	{
		if(Utility::validateMobile($mobile)===true && !empty($password)){
			if(User::isExistsByField($mobile,User::IDENTIFY_FIELD_MOBILE)===true){
				return array('result'=>false,'msg'=>"该手机号已经存在");
			}else{
//				if(UserMobile::phoneVerifyStatus($mobile,true)===false) return array('result'=>false,'msg'=>"手机未验证");
				$uid = User::createUserByPhone($mobile,$password,$register);
			}
			if($uid>0){
				return array('result'=>true,'data'=>$uid);
			}
			return array('result'=>false,'msg'=>"注册失败");
		}
		return array('result'=>false,'msg'=>"手机号无效");
	}

	/**
	 * 修改密码
	 *
	 */
	public static function modifyUserPwd($mobile,$password)
	{
		$res = User::modifyUserPwd($mobile,User::IDENTIFY_FIELD_MOBILE,$password);
		if($res){
			return array('result'=>true,'data'=>$res);
		}else{
			return array('result'=>false,'msg'=>"密码修改失败");
		}
	}
	/**
	 * 修改手机号
	 *
	 */
	public static function modifyUserMobile($urid,$mobile)
	{
		$res = User::modifyUserMobile($urid,User::IDENTIFY_FIELD_URID,$mobile);
		if($res){
			return array('result'=>true,'data'=>$res);
		}else{
			return array('result'=>false,'msg'=>"密码修改失败");
		}
	}

	public static function saveFeedback($urid, $contact, $content)
	{
		$res = Feedback::saveFeedback($urid,$contact,$content);
		if($res){
			return array('result'=>true);
		}else{
			return array('result'=>false,'msg'=>"意见反馈提交失败");
		}
	}
	/**
	 * 获取用户信息
	 */
	public static function getUserInfo($urid)
	{
		$user = User::getUserInfoById($urid);
		if($user){
//			if($user['mobile']){
//				$user['mobile'] = preg_replace('/(1[3578]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$user['mobile']);
//			}
			return array('result'=>true,'data'=>$user);
		}
		return array('result'=>false,'msg'=>"用户不存在");
	}

    public static function getUserInfobyMobile($mobile)
    {
        $user = User::getUserInfoByMobile($mobile);
        if($user){
//			if($user['mobile']){
//				$user['mobile'] = preg_replace('/(1[3578]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$user['mobile']);
//			}
            return array('result'=>true,'data'=>$user);
        }
        return array('result'=>false,'msg'=>"用户不存在");
    }
	/**
	 * 修改用户资料
	 */
	public static function modifyUserInfo($urid,$input)
	{
		if(!$urid) return false;

		$fields = array('username','image','sex','type','companyId','companyName','companyAddress','password','state','parentId','register');
		$data = array();
		//过滤非法字段
		foreach($fields as $field){
			isset($input[$field]) && !empty($input[$field]) && $data[$field] = $input[$field];
		}
        $data['updateTime'] = time();
		if($data){
		    if (isset($data['companyName'])) {
//		        $company_data = [];
//		        if ($data['companyId'] > 0) {
//                    $company_data['id'] = $data['companyId'];
//                }
//                $company_data['companyName'] = $data['companyName'];
//                $company_data['address'] = $data['companyAddress'];
//		        Company::save($company_data);
                unset($data['companyId']);
            }
			$res = User::modifyUserInfo($urid, $data);
			if($res){
				return array('result'=>true,'data'=>$res);
			}else{
				return array('result'=>false,'msg'=>"资料修改失败");
			}
		}
		return array('result'=>false,'msg'=>"资料修改失败");
	}
	/**
	 * 获取用户状态
	 */
	public static function getUseridentify($urid)
	{
		$user = User::getUserInfoById($urid,'short');
		if($user){
			$result = array();
            $result['result'] = $user['identify'];
			return array('result'=>true,'data'=>$result);
		}
		return array('result'=>false,'msg'=>"用户不存在");
	}

	/**
	 * 视频上传
	 * @param $videoinfo - 视频的资源，数组类型。['视频类型','视频大小','视频进行base64加密后的字符串']
	 * @return mixed
	 */
	public static function uploadVideo($videoinfo) {
		$video_type = strip_tags($videoinfo[0]);  //视频类型
		$video_size = intval($videoinfo[1]);  //视频大小
		$video_base64_content = strip_tags($videoinfo[2]); //视频进行base64编码后的字符串

		$upload = new UploaderService();
		$upconfig = $upload->upconfig;

		if(($video_size > $upconfig['maxSize']) || ($video_size == 0)) {
			$array['result'] = 13;
			$array['comment'] = "文件大小不符合要求！";
			return $array;
		}

		if(!in_array($video_type,$upconfig['exts'])) {
			$array['result'] = 14;
			$array['comment'] = "文件格式不符合要求！";
			return $array;
		}

		// 设置附件上传子目录
		$savePath = public_path().'/downloads/info/';
		$upload->upconfig['savePath'] = $savePath;

		//视频保存的名称
		$new_videoname = uniqid().mt_rand(100,999).'.'.$video_type;

		//base64解码后的视频字符串
		$string_video_content = base64_decode($video_base64_content);

		// 保存上传的文件
		$array = $upload->upload($string_video_content,$new_videoname);

		return $array;
	}

	public static function saveComment($urid,$type,$pid,$content)
	{
		$res = Comment::saveComment($urid,$type,$pid,$content);
		if($res){
			return array('result'=>true);
		}else{
			return array('result'=>false,'msg'=>"评论提交失败");
		}
	}

    public static function getUserList()
    {
        $search = $out = [];
        $user = User::getList($search,1,2000);
        foreach ($user as $item) {
            $out_itme = [];
            $out_itme['username'] = $item['username'];
            $out_itme['mobile'] = $item['mobile'];
            $out_itme['companyName'] = $item['companyName'];
            $out[] = $out_itme;
        }
        return $out;
    }

	public static function getSubUserList($pageIndex=1,$pageSize=20,$urid=0)
	{
		$search['parentId'] = $urid;
		$subuser = User::getList($search,$pageIndex,$pageSize);
		if($subuser){
			foreach ($subuser as &$item) {
				unset($item['password']);
				unset($item['salt']);
				unset($item['email']);
				unset($item['regTime']);
				unset($item['regIp']);
				unset($item['lastLoginTime']);
				unset($item['lastLoginIp']);
				unset($item['updateTime']);
				unset($item['tuid']);
				unset($item['score']);
				unset($item['scoreTotal']);
				unset($item['type']);
				$item['img'] = Utility::getImageUrl($item['img']);
			}
			return array('result'=>true,'data'=>$subuser);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

	public static function saveSubUser($input)
	{
		if (!$input['mobile']) return array('result'=>false,'msg'=>"保存失败");
		if(User::isExistsByField($input['mobile'],User::IDENTIFY_FIELD_MOBILE)===true){
			return array('result'=>false,'msg'=>"该手机号已经存在");
		}else{
			$result = User::save($input);
			if($result){
				return array('result'=>true);
			}else{
				return array('result'=>false,'msg'=>"保存失败");
			}
		}
	}
}