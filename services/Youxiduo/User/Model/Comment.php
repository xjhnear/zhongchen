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

use Youxiduo\Base\Model;
use Youxiduo\Base\IModel;
/**
 * 用户反馈模型类
 */
final class Comment extends Model implements IModel
{	
    public static function getClassName()
	{
		return __CLASS__;
	}	
	
	public static function saveComment($urid,$type,$pid,$content)
	{
		$data = array();
		$data['urid'] = $urid;
		$data['type'] = $type;
		$data['pid'] = $pid;
		$data['content'] = $content;
		$data['created_at'] = time();
		$data['updated_at'] = time();
		$res = self::db()->insertGetId($data);
		return $res ? true : false;
	}

	public static function getList($search,$pageIndex=1,$pageSize=20)
	{
		$tb = self::db();
		if(isset($search['content']) && !empty($search['content'])) $tb = $tb->where('content','like','%'.$search['content'].'%');
		if(isset($search['type']) && !empty($search['type'])) $tb = $tb->where('type','=',$search['type']);
		if(isset($search['pid']) && !empty($search['pid'])) $tb = $tb->where('pid','=',$search['pid']);
		return $tb->orderBy('created_at','desc')->forPage($pageIndex,$pageSize)->get();
	}

	public static function getCount($search)
	{
		$tb = self::db();
		if(isset($search['content']) && !empty($search['content'])) $tb = $tb->where('content','like','%'.$search['content'].'%');
		if(isset($search['type']) && !empty($search['type'])) $tb = $tb->where('type','=',$search['type']);
		if(isset($search['pid']) && !empty($search['pid'])) $tb = $tb->where('pid','=',$search['pid']);
		return $tb->count();
	}

}