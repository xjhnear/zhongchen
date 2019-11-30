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
use Youxiduo\User\Model\ArticleGroup;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class ArticleGroupService extends BaseService
{

	public static function getArticleGroupList($pageIndex=1,$pageSize=20)
	{
		$articlegroup = ArticleGroup::getList($pageIndex,$pageSize);
		if($articlegroup){
			return array('result'=>true,'data'=>$articlegroup);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

}