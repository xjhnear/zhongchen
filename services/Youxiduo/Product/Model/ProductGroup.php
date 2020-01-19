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
namespace Youxiduo\Product\Model;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;

use Youxiduo\Helper\Utility;
/**
 * 账号模型类
 */
final class ProductGroup extends Model implements IModel
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

	public static function getInfo($pgid)
	{
		$batch = self::db()->where('pgid','=',$pgid)->first();
		if(!$batch) return array();
		return $batch;
	}

	public static function save($data)
	{
		if(isset($data['pgid']) && $data['pgid']){
			$pgid = $data['pgid'];
			unset($data['pgid']);
			$data['updated_at'] = time();
			return self::db()->where('pgid','=',$pgid)->update($data);
		}else{
			unset($data['pgid']);
			$data['created_at'] = time();
			$data['updated_at'] = time();
			return self::db()->insertGetId($data);
		}
	}

	public static function del($pgid)
	{
		if($pgid > 0){
			$re = self::db()->where('pgid','=',$pgid)->delete();
		}
		return $re;
	}

	public static function getNameList()
	{
		$groups = self::db()->orderBy('pgid','asc')->lists('title','pgid');

		return $groups;
	}
}