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
final class Video extends Model implements IModel
{

    public static function getClassName()
	{
		return __CLASS__;
	}

	//后台
	public static function getList($pageIndex=1,$pageSize=20,$gid=0)
	{
		$tb = self::db();
		if ($gid > 0) {
			$tb->where('gid','=',$gid);
		}
		return $tb->orderBy('created_at','desc')->forPage($pageIndex,$pageSize)->get();
	}

	public static function getCount()
	{
		$tb = self::db();
		return $tb->count();
	}

	public static function getInfo($vid)
	{
		$batch = self::db()->where('vid','=',$vid)->first();
		if(!$batch) return array();
		return $batch;
	}

	public static function save($data)
	{
		if(isset($data['vid']) && $data['vid']){
			$vid = $data['vid'];
			unset($data['vid']);
			$data['updated_at'] = time();
			return self::db()->where('vid','=',$vid)->update($data);
		}else{
			unset($data['vid']);
			$data['created_at'] = time();
			$data['updated_at'] = time();
			return self::db()->insertGetId($data);
		}
	}

	public static function del($vid)
	{
		if($vid > 0){
			$re = self::db()->where('vid','=',$vid)->delete();
		}
		return $re;
	}

}