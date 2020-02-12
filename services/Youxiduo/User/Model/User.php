<?php
/**
 * @package Youxiduo
 * @category Android 
 * @link http://dev.youxiduo.com
 * @copyright Copyright (c) 2008 Youxiduo.com 
 * @license http://www.youxiduo.com/license
 * @since 4.0.0
 *
 */
namespace Youxiduo\User\Model;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;

use Youxiduo\Helper\Utility;
/**
 * 账号模型类
 */
final class User extends Model implements IModel
{	
	const IDENTIFY_FIELD_URID      = 'urid';
	const IDENTIFY_FIELD_MOBILE   = 'mobile';
	const IDENTIFY_FIELD_NAME = 'username';
	
	
    public static function getClassName()
	{
		return __CLASS__;
	}

	/**
	 * 账号登录
	 */
	public static function doLocalLogin($identify,$identify_field,$password)
	{
		if(!in_array($identify_field,array('urid','mobile'))) return false;
//		if(strlen($password) != 32){
//			$password = Utility::cryptPwd($password);
//		}
		$user = self::db();
		$user = $user->where($identify_field,'=',$identify)->where('password','=',md5($password));
		$user = $user->first();
		return $user;
	}

	public static function isExistsByField($identify,$identify_field,$urid=0)
	{
		if(!in_array($identify_field,array('mobile','name'))) return true;
		$tb = self::db()->where($identify_field,'=',$identify);
		if($urid){
			$tb = $tb->where('urid','!=',$urid);
		}
		$user = $tb->first();
		return $user ? true : false;
	}

	/**
	 * 创建用户通过手机号
	 */
	public static function createUserByPhone($mobile,$password,$register)
	{
		$data = array();
		$data['mobile'] = $mobile;
        $data['username'] = '用户'.Utility::random(4,'alnum');
        $data['salt'] = Utility::random(6,'alnum');;
		$data['password'] = Utility::cryptPwd($password);
		$data['regTime'] = time();
		$data['updateTime'] = time();
		$data['register'] = $register;
		$uid = self::db()->insertGetId($data);
		return $uid;
	}

	/**
	 * 修改密码
	 */
	public static function modifyUserPwd($identify,$identify_field,$password)
	{
		if(!in_array($identify_field,array('mobile','urid'))) return false;
		$user = self::getUserInfoByField($identify, $identify_field);
		if($user){
//			if(strlen($password)!=32){
//				$password = Utility::cryptPwd($password);
//			}
			$res = self::db()->where($identify_field,'=',$identify)->update(array('password'=>$password));
			return $user['urid'];
		}
		return false;
	}

	public static function getUserInfoByField($identify,$identify_field)
	{
		if(!in_array($identify_field,array('mobile','urid'))) return false;
		$user = self::db()->where($identify_field,'=',$identify)->first();

		return $user;
	}

	public static function modifyUserMobile($identify,$identify_field,$mobile)
	{
		if(!in_array($identify_field,array('mobile','urid'))) return false;
		$user = self::getUserInfoByField($identify, $identify_field);
		if($user){
			$res = self::db()->where($identify_field,'=',$identify)->update(array('mobile'=>$mobile));
			return $user['urid'];
		}
		return false;
	}
	/**
	 * 获取用户信息
	 * @param $uid
	 * @param string $filter
	 * @return array
	 */
	public static function getUserInfoById($urid,$filter='info')
	{
		$info = self::db()->where('urid','=',$urid)->first();
		if(!$info) return null;
//		$info && $info['avatar'] = Utility::getImageUrl($info['avatar']);
//		$info && $info['homebg'] = Utility::getImageUrl($info['homebg']);
		return self::filterUserFields($info,$filter);
	}

    public static function getUserInfoByMobile($mobile,$filter='info')
    {
        $info = self::db()->where('mobile','=',$mobile)->first();
        if(!$info) return null;
//		$info && $info['avatar'] = Utility::getImageUrl($info['avatar']);
//		$info && $info['homebg'] = Utility::getImageUrl($info['homebg']);
        return self::filterUserFields($info,$filter);
    }

	/**
	 * 过滤用户隐私信息
	 * @param array $user 用户信息
	 * @param string|array 过滤器,默认值:short
	 * 根据不同的需求显示用户字段的信息不同
	 */
	public static function filterUserFields($user,$filter='short')
	{
		if(!$user) return $user;
		//默认的fields的字段列表是全部的字段
		$fields = array(
			'urid','mobile','username','sex',
			'image','email','regTime','regIp','lastLoginTime','lastLoginIp','updateTime','tuid','score','scoreTotal',
			'state','companyId','companyName','companyAddress','type','parentId','register'
		);

		if(is_string($filter)){
			if($filter === 'short'){
				$fields = array('urid','mobile','username','image','sex','parentId','register');
			}elseif($filter === 'info'){
				$fields = array('urid','mobile','username','image','sex','regTime','companyId','companyName','companyAddress','type','parentId','register','state');
			}
		}
		$out = array();
		//检测获取到的用户的字段是否在$fields中，如果存在的话，把这个字段存入$out数组中，然后销毁$user数组，返回$out这个数组
		foreach($user as $field=>$value){
			if(in_array($field,$fields)){
				$out[$field] = $value;
			}
		}
		unset($user);
		return $out;
	}
	/**
	 * 修改资料
	 */
	public static function modifyUserInfo($uid,$data)
	{
		$res = self::db()->where('urid','=',$uid)->update($data);
		return $res;
	}

	//后台
	public static function getList($search,$pageIndex=1,$pageSize=20)
	{
		$tb = self::db();
		if(isset($search['username']) && !empty($search['username'])) $tb = $tb->where('username','like','%'.$search['username'].'%');
		if(isset($search['mobile']) && !empty($search['mobile'])) $tb = $tb->where('mobile','=',$search['mobile']);
		if(isset($search['parentId']) && !empty($search['parentId'])) $tb = $tb->where('parentId','=',$search['parentId']);
		return $tb->orderBy('urid','desc')->forPage($pageIndex,$pageSize)->get();
	}

	public static function getCount($search)
	{
		$tb = self::db();
		if(isset($search['username']) && !empty($search['username'])) $tb = $tb->where('username','like','%'.$search['username'].'%');
		if(isset($search['mobile']) && !empty($search['mobile'])) $tb = $tb->where('mobile','=',$search['mobile']);
		if(isset($search['parentId']) && !empty($search['parentId'])) $tb = $tb->where('parentId','=',$search['parentId']);
		return $tb->count();
	}

	public static function getInfo($urid)
	{
		$batch = self::db()->where('urid','=',$urid)->first();
		if(!$batch) return array();
		return $batch;
	}
	
	public static function m_search($search)
	{
		$tb = self::m_buildSearch($search);
		return $tb->orderBy('urid','desc')->get();
	}

	protected static function m_buildSearch($search)
	{
		$tb = self::db();
		if(isset($search['username'])){
			$tb = $tb->where('username','like','%'.$search['username'].'%');
		}
		return $tb;
	}

	public static function save($data)
	{
		if(isset($data['urid']) && $data['urid']){
			$urid = $data['urid'];
			unset($data['urid']);
			$data['updateTime'] = time();
			return self::db()->where('urid','=',$urid)->update($data);
		}else{
			unset($data['urid']);
			$data['regTime'] = time();
			$data['updateTime'] = time();
			return self::db()->insertGetId($data);
		}
	}

	public static function del($urid)
	{
		if($urid > 0){
			$re = self::db()->where('urid','=',$urid)->delete();
		}
		return $re;
	}

}