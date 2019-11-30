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
final class VideoGroup extends Model implements IModel
{

    public static function getClassName()
	{
		return __CLASS__;
	}

	//后台
	public static function getList($pageIndex=1,$pageSize=20)
	{
		$tb = self::db();
		return $tb->orderBy('created_at','desc')->forPage($pageIndex,$pageSize)->get();
	}

	public static function getCount()
	{
		$tb = self::db();
		return $tb->count();
	}

	public static function getInfo($vgid)
	{
		$batch = self::db()->where('vgid','=',$vgid)->first();
		if(!$batch) return array();
		return $batch;
	}

	public static function save($data)
	{
		if(isset($data['vgid']) && $data['vgid']){
			$vgid = $data['vgid'];
			unset($data['vgid']);
			$data['updated_at'] = time();
			return self::db()->where('vgid','=',$vgid)->update($data);
		}else{
			unset($data['vgid']);
			$data['created_at'] = time();
			$data['updated_at'] = time();
			return self::db()->insertGetId($data);
		}
	}

	public static function del($vgid)
	{
		if($vgid > 0){
			$re = self::db()->where('vgid','=',$vgid)->delete();
		}
		return $re;
	}

	public static function getNameList()
	{
		$groups = self::db()->orderBy('vgid','asc')->lists('title','vgid');

		return $groups;
	}
}