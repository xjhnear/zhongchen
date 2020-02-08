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
        $tb = $tb->join('orderuser','orderuser.orid','=','order.orid');
        if(isset($search['urid']) && !empty($search['urid'])) $tb = $tb->where('orderuser.urid','=',$search['urid']);
        if(isset($search['orderNo']) && !empty($search['orderNo'])) $tb = $tb->where('orderNo','like','%'.$search['orderNo'].'%');
        if(isset($search['status']) && !empty($search['status'])) $tb = $tb->whereIn('status',$search['status']);
        $tb = $tb->where('orderuser.state','=',1);
        return $tb->orderBy('order.orid','desc')->forPage($pageIndex,$pageSize)->get();
    }

    public static function getListBackend($search,$pageIndex=1,$pageSize=20)
    {
        $tb = self::db();
        if(isset($search['urid']) && !empty($search['urid'])) $tb = $tb->where('urid','=',$search['urid']);
        if(isset($search['orderNo']) && !empty($search['orderNo'])) $tb = $tb->where('orderNo','like','%'.$search['orderNo'].'%');
        if(isset($search['status']) && !empty($search['status'])) $tb = $tb->whereIn('status',$search['status']);
        return $tb->orderBy('order.orid','desc')->forPage($pageIndex,$pageSize)->get();
    }

    public static function getCount($search)
    {
        $tb = self::db();
        if(isset($search['urid']) && !empty($search['urid'])) $tb = $tb->where('urid','=',$search['urid']);
        if(isset($search['orderNo']) && !empty($search['orderNo'])) $tb = $tb->where('orderNo','like','%'.$search['orderNo'].'%');
        if(isset($search['status']) && !empty($search['status'])) $tb = $tb->where('status','in','('.implode(',',$search['status']).')');
        return $tb->count();
    }

    public static function getOrderInfoById($orid)
    {
        $info = self::db()->where('orid','=',$orid)->first();
        if(!$info) return null;
        return $info;
    }

    public static function getOrderInfoByorderNo($orderNo)
    {
        $info = self::db()->where('orderNo','=',$orderNo)->first();
        if(!$info) return null;
        return $info;
    }

	public static function getInfo($id)
	{
		$batch = self::db()->where('orid','=',$id)->first();
		if(!$batch) return array();
        $batch && $batch['contacts'] = json_decode($batch['contacts'],true);
		return $batch;
	}
	
    public static function m_search($search)
	{
		$tb = self::m_buildSearch($search);
		return $tb->orderBy('orid','desc')->get();
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
        if(isset($data['orid']) && $data['orid']){
            $id = $data['orid'];
            unset($data['orid']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            return self::db()->where('orid','=',$id)->update($data);
        }else{
            unset($data['orid']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            $data['updateTime'] = date('Y-m-d H:i:s',time());
            return self::db()->insertGetId($data);
        }
    }

    public static function delById($id)
    {
        if($id > 0){
            $re = self::db()->where('orid','=',$id)->delete();
        }
        return $re;
    }
}