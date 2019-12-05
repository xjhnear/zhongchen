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
namespace Youxiduo\Order;

use Illuminate\Support\Facades\Config;
use Youxiduo\Base\BaseService;
use Youxiduo\Order\Model\Order;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class OrderService extends BaseService
{

	public static function getOrderList($search=[],$pageIndex=1,$pageSize=20)
	{
		$order = Order::getList($search,$pageIndex,$pageSize);
		if($order){
			return array('result'=>true,'data'=>$order);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

}