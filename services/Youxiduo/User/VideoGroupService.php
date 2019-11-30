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
use Youxiduo\User\Model\VideoGroup;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class VideoGroupService extends BaseService
{

	public static function getVideoGroupList($pageIndex=1,$pageSize=20)
	{
		$videogroup = VideoGroup::getList($pageIndex,$pageSize);
		if($videogroup){
			return array('result'=>true,'data'=>$videogroup);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

}