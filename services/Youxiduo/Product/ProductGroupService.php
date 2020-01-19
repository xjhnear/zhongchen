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
namespace Youxiduo\Product;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\BaseService;
use Youxiduo\Product\Model\ProductGroup;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class ProductGroupService extends BaseService
{

	public static function getProductGroupList($pageIndex=1,$pageSize=20)
	{
		$productgroup = ProductGroup::getList($pageIndex,$pageSize);
		if($productgroup){
			return array('result'=>true,'data'=>$productgroup);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

}