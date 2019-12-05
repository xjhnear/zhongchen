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

use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;

use Youxiduo\Helper\Utility;
use Youxiduo\Helper\MyHelp;
/**
 * 应用配置模型类
 */
final class Product extends Model implements IModel
{
    public static function getClassName()
    {
        return __CLASS__;
    }

    public static function getList($search,$pageIndex=1,$pageSize=20)
    {
        $tb = self::db();
        if(isset($search['name']) && !empty($search['name'])) $tb = $tb->where('name','like','%'.$search['name'].'%');
        return $tb->orderBy('prid','desc')->forPage($pageIndex,$pageSize)->get();
    }

    public static function getCount($search)
    {
        $tb = self::db();
        if(isset($search['name']) && !empty($search['name'])) $tb = $tb = $tb->where('name','like','%'.$search['name'].'%');
        return $tb->count();
    }

    public static function save($data)
    {
        if(isset($data['prid']) && $data['prid']){
            $id = $data['prid'];
            unset($data['prid']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            return self::db()->where('prid','=',$id)->update($data);
        }else{
            unset($data['prid']);
            $data['createTime'] = date('Y-m-d H:i:s',time());
            $data['updateTime'] = date('Y-m-d H:i:s',time());
            return self::db()->insertGetId($data);
        }
    }

    public static function delById($id)
    {
        if($id > 0){
            $re = self::db()->where('prid','=',$id)->delete();
        }
        return $re;
    }
}