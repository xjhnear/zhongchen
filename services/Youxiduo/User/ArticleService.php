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
use Youxiduo\User\Model\Article;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class ArticleService extends BaseService
{

	public static function getArticleList($pageIndex=1,$pageSize=20,$gid=0)
	{
		$article = Article::getList($pageIndex,$pageSize,$gid);
		if($article){
		    foreach ($article as &$item) {
                unset($item['gid']);
                unset($item['summary']);
                unset($item['content']);
                $item['img'] = Utility::getImageUrl($item['img']);
            }
			return array('result'=>true,'data'=>$article);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

	public static function getArticleInfo($arid)
	{
		$article = Article::getInfo($arid);
		if($article){
			unset($article['gid']);
			unset($article['summary']);
			$article['img'] = Utility::getImageUrl($article['img']);
			return array('result'=>true,'data'=>$article);
		}
		return array('result'=>false,'msg'=>"数据不存在");
	}
}