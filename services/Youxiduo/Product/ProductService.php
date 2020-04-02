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
use Youxiduo\Product\Model\Product;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class ProductService extends BaseService
{

	public static function getProductList($search=[],$pageIndex=1,$pageSize=20)
	{
		$product = Product::getList($search,$pageIndex,$pageSize);
		if($product){
            foreach ($product as &$item) {
                $item && $item['img'] = Utility::getImageUrl($item['img']);
                unset($item['content']);
                unset($item['remarks']);
                unset($item['extrainfo']);
            }
			return array('result'=>true,'data'=>$product);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

    public static function getProductInfo($prid)
    {
        $product = Product::getProductInfoById($prid);
        if($product){
            return array('result'=>true,'data'=>$product);
        }
        return array('result'=>false,'msg'=>"产品不存在");
    }

}