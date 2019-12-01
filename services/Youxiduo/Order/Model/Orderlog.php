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
final class Orderlog extends Model implements IModel
{
    public static function getClassName()
    {
        return __CLASS__;
    }

    public static function getList($search,$pageIndex=1,$pageSize=20)
    {
        $tb = self::db();
        if(isset($search['orid']) && !empty($search['orid'])) $tb = $tb->where('orid','=',$search['orid']);
        return $tb->orderBy('id','desc')->forPage($pageIndex,$pageSize)->get();
    }

    public static function getCount($search)
    {
        $tb = self::db();
        if(isset($search['orid']) && !empty($search['orid'])) $tb = $tb->where('orid','=',$search['orid']);
        return $tb->count();
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