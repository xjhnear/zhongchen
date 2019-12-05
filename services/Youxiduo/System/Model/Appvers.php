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
namespace Youxiduo\System\Model;

use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;

use Youxiduo\Helper\Utility;
/**
 * 版本模型类
 */
final class Appvers extends Model implements IModel
{		
    public static function getClassName()
	{
		return __CLASS__;
	}
	
	public static function getInfoByPlatform($platform)
	{
		$info = self::db()->where('platform','=',$platform)->first();
		return $info;
	}
	
	public static function getInfoById($ver_id)
	{
		$info = self::db()->where('id','=',$ver_id)->first();
		return $info;
	}
	
	public static function getList($search,$pageIndex=1,$pageSize=20)
	{
		$tb = self::db();
		if(isset($search['platform']) && $search['platform']>0) $tb = $tb->where('platform','=',$search['platform']);
		return $tb->orderBy('id','asc')->forPage($pageIndex,$pageSize)->get();
	}
	
	public static function getCount($search)
	{
		$tb = self::db();
		if(isset($search['platform']) && $search['platform']>0) $tb = $tb->where('platform','=',$search['platform']);
		return $tb->count();
	}
	
	public static function saveInfo($data)
	{
		if(isset($data['id']) && $data['id']){
			$id = $data['id'];
			unset($data['id']);
			$data['updateTime'] = time();
			self::db()->where('id','=',$id)->update($data);
			return $id;
		}else{
			$data['createTime'] = time();
			$data['updateTime'] = time();
			return self::db()->insertGetId($data);
		}
	}
	
	public static function modifyForce($ver_id,$force)
	{
		return self::db()->where('id','=',$ver_id)->update(array('force'=>$force));
	}

	public static function del($ver_id)
	{
		if($ver_id > 0){
			$re = self::db()->where('id','=',$ver_id)->delete();
		}
		return $re;
	}
}