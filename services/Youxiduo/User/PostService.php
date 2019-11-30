<?php
/**
 * @package Youxiduo
 * @category Base 
 * @link http://dev.youxiduo.com
 * @copyright Copyright (c) 2008 Youxiduo.com 
 * @license http://www.youxiduo.com/license
 * @since 4.0.0
 *
 */
namespace Youxiduo\User;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\BaseService;
use Youxiduo\User\Model\Post;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class PostService extends BaseService
{

	public static function getPostList($urid,$pageIndex=1,$pageSize=20)
	{
		if ($urid > 0) {
			$post = Post::getList($pageIndex,$pageSize,$urid);
		} else {
			$post = Post::getList($pageIndex,$pageSize);
		}
		if($post){
			return array('result'=>true,'data'=>$post);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

	public static function save($urid,$title,$content)
	{
		if ($urid > 0) {
			$data['title'] = $title;
			$data['content'] = $content;
			$data['urid'] = $urid;
			$result = Post::save($data);
			if($result){
				return array('result'=>true,'data'=>$result);
			}
		}
		return array('result'=>false,'msg'=>"用户不存在");
	}

}