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
final class Post extends Model implements IModel
{

    public static function getClassName()
	{
		return __CLASS__;
	}

	//后台
	public static function getList($pageIndex=1,$pageSize=20,$urid=0)
	{
		$tb = self::db();
		$tb->select('post.*','user.name');
		if ($urid > 0) {
			$tb->where('urid','=',$urid);
		}
		$tb->leftJoin('user', function ($join) {
			$join->on('user.urid', '=', 'post.urid');
		});
		return $tb->orderBy('created_at','desc')->forPage($pageIndex,$pageSize)->get();
	}

	public static function getCount()
	{
		$tb = self::db();
		return $tb->count();
	}

	public static function getInfo($ptid)
	{
		$batch = self::db()->where('ptid','=',$ptid)->first();
		if(!$batch) return array();
		return $batch;
	}

	public static function save($data)
	{
		if(isset($data['ptid']) && $data['ptid']){
			$ptid = $data['ptid'];
			unset($data['ptid']);
			$data['updated_at'] = time();
			return self::db()->where('ptid','=',$ptid)->update($data);
		}else{
			unset($data['ptid']);
			$data['created_at'] = time();
			$data['updated_at'] = time();
			return self::db()->insertGetId($data);
		}
	}

	public static function del($ptid)
	{
		if($ptid > 0){
			$re = self::db()->where('ptid','=',$ptid)->delete();
		}
		return $re;
	}

}