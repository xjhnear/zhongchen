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
use Youxiduo\User\Model\Video;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class VideoService extends BaseService
{

	public static function getVideoList($pageIndex=1,$pageSize=20,$gid=0)
	{
		$video = Video::getList($pageIndex,$pageSize,$gid);
		if($video){
			return array('result'=>true,'data'=>$video);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

}