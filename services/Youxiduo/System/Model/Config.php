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
final class Config extends Model implements IModel
{		
    public static function getClassName()
	{
		return __CLASS__;
	}
	
	public static function getInfoByType($type)
	{
		$info = self::db()->where('type','=',$type)->first();
		return $info;
	}
	
	public static function getInfoById($id)
	{
		$info = self::db()->where('id','=',$id)->first();
		return $info;
	}

	public static function saveInfo($data)
	{
		if(isset($data['id']) && $data['id']){
			$id = $data['id'];
			unset($data['id']);
			$data['updated_at'] = time();
			self::db()->where('id','=',$id)->update($data);
			return $id;
		}else{
			$data['created_at'] = time();
			$data['updated_at'] = time();
			return self::db()->insertGetId($data);
		}
	}
	
	public static function modifyForce($id,$force)
	{
		return self::db()->where('id','=',$id)->update(array('force'=>$force));
	}

	public static function del($id)
	{
		if($id > 0){
			$re = self::db()->where('id','=',$id)->delete();
		}
		return $re;
	}
}