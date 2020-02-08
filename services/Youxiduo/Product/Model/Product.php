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

    public static function getList($search,$pageIndex=1,$pageSize=20,$orderby=[])
    {
        $tb = self::db();
        if(isset($search['name']) && !empty($search['name'])) $tb = $tb->where('name','like','%'.$search['name'].'%');
        if(isset($search['group_id']) && $search['group_id']>0) $tb = $tb->where('gid','=',$search['group_id']);
        if (isset($orderby['key']) && $orderby['key']>0) {
            $orderby_key = 'prid';
            switch ($orderby['key']) {
                case 1:
                    $orderby_key = 'prid';
                    break;
                case 2:
                    $orderby_key = 'price';
                    break;
            }
            if ($orderby['value'] == 2) {
                $orderby_value = 'asc';
            } else {
                $orderby_value = 'desc';
            }
            $tb->orderBy($orderby_key,$orderby_value);
        } else {
            $tb->orderBy('prid','desc');
        }
        return $tb->forPage($pageIndex,$pageSize)->get();
    }

    public static function getCount($search)
    {
        $tb = self::db();
        if(isset($search['name']) && !empty($search['name'])) $tb = $tb = $tb->where('name','like','%'.$search['name'].'%');
        if(isset($search['group_id']) && $search['group_id']>0) $tb = $tb->where('gid','=',$search['group_id']);
        return $tb->count();
    }

    public static function getProductInfoById($prid)
    {
        $info = self::db()->where('prid','=',$prid)->first();
        if(!$info) return null;
		$info && $info['img'] = Utility::getImageUrl($info['img']);
        $info && $info['extrainfo'] = json_decode($info['extrainfo'],true);
        return $info;
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