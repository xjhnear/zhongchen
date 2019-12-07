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
namespace Youxiduo\Order\Model;

use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;

use Youxiduo\Helper\Utility;
use Youxiduo\Helper\MyHelp;
/**
 * 应用配置模型类
 */
final class Order extends Model implements IModel
{		
    public static function getClassName()
	{
		return __CLASS__;
	}

    public static function getList($search,$pageIndex=1,$pageSize=20)
    {
        $tb = self::db();
        if(isset($search['urid']) && !empty($search['urid'])) $tb = $tb->where('urid','=',$search['urid']);
        return $tb->orderBy('id','desc')->forPage($pageIndex,$pageSize)->get();
    }

    public static function getCount($search)
    {
        $tb = self::db();
        if(isset($search['urid']) && !empty($search['urid'])) $tb = $tb->where('urid','=',$search['urid']);
        return $tb->count();
    }

    public static function getOrderInfoById($orid)
    {
        $info = self::db()->where('orid','=',$orid)->first();
        if(!$info) return null;
        return $info;
    }

	public static function getInfo($id)
	{
		$batch = self::db()->where('id','=',$id)->first();
		if(!$batch) return array();
		return $batch;
	}
	
    public static function m_search($search)
	{
		$tb = self::m_buildSearch($search);
		return $tb->orderBy('id','desc')->get();
	}
	
	protected static function m_buildSearch($search)
	{
		$tb = self::db();
		if(isset($search['orderNo'])){
			$tb = $tb->where('orderNo','like','%'.$search['orderNo'].'%');
		}
		return $tb;
	}

    public static function save($data)
    {
        if(isset($data['id']) && $data['id']){
            $id = $data['id'];
            unset($data['id']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            return self::db()->where('id','=',$id)->update($data);
        }else{
            unset($data['id']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            $data['updateTime'] = date('Y-m-d H:i:s',time());
            return self::db()->insertGetId($data);
        }
    }

    public static function delById($id)
    {
        if($id > 0){
            $re = self::db()->where('id','=',$id)->delete();
        }
        return $re;
    }
}