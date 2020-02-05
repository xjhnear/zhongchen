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
use Youxiduo\Order\Model\Orderproduct;
use Youxiduo\Order\Model\Orderuser;
use Youxiduo\Order\Model\Orderrepair;
use Youxiduo\Product\ProductService;
use Youxiduo\User\UploaderService;
use Youxiduo\Helper\Utility;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class OrderService extends BaseService
{

	public static function getOrderList($search=[],$pageIndex=1,$pageSize=20)
	{
	    if ($search['type']) {
	        switch ($search['type']) {
                case 1:
                    $search['status'] = [1,2,7];
                    break;
                case 2:
                    $search['status'] = [3,4];
                    break;
                case 3:
                    $search['status'] = [5,6];
                    break;
                case 4:
                    $search['status'] = [8];
                    break;
                case 5:
                    $search['status'] = [9];
                    break;
                default:
                    $search['status'] = [1];
                    break;
            }
            unset($search['type']);
        }
		$order = Order::getList($search,$pageIndex,$pageSize);
		if($order){
		    foreach ($order as &$item) {
                unset($item['name']);
                unset($item['tel']);
                unset($item['address']);
                unset($item['pay']);
                unset($item['companyId']);
                unset($item['companyName']);
                unset($item['createUrid']);
                unset($item['contractTime']);
                unset($item['testUrid']);
                unset($item['contacts']);
                unset($item['payTime']);
                unset($item['receiptType']);
                unset($item['receiptTitle']);
                unset($item['receiptContent']);
                $productList = Orderproduct::getListByOrid($item['orid']);
                foreach ($productList as &$item_sub) {
                    unset($item_sub['orid']);
                    unset($item_sub['createUrid']);
                    unset($item_sub['createTime']);
                    unset($item_sub['updateTime']);
                    unset($item_sub['transportName']);
                    unset($item_sub['transportNo']);
                    unset($item_sub['content']);
                    unset($item_sub['remarks']);
                }
                $item['productList'] = $productList;
            }
			return array('result'=>true,'data'=>$order);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}


    public static function getOrderInfo($orid)
    {
        $order = Order::getOrderInfoById($orid);
        if($order){
            $order['payType'] = 1;
            unset($order['pay']);
            unset($order['companyId']);
            unset($order['companyName']);
            unset($order['createUrid']);
            unset($order['contractTime']);
            unset($order['testUrid']);
            unset($order['contacts']);
            $order['receipt']['type'] = $order['receiptType'];
            $order['receipt']['title'] = $order['receiptTitle'];
            $order['receipt']['content'] = $order['receiptContent'];
            unset($order['receiptType']);
            unset($order['receiptTitle']);
            unset($order['receiptContent']);
            $productList = Orderproduct::getListByOrid($order['orid']);
            foreach ($productList as &$item_sub) {
                unset($item_sub['orid']);
                unset($item_sub['createUrid']);
                unset($item_sub['createTime']);
                unset($item_sub['updateTime']);
                unset($item_sub['transportName']);
                unset($item_sub['transportNo']);
                unset($item_sub['content']);
                unset($item_sub['remarks']);
            }
            $order['productList'] = $productList;
            $search['orid'] = $order['orid'];
            $pairList = Orderrepair::getList($search);
            $order['pairList'] = $pairList;
            return array('result'=>true,'data'=>$order);
        }
        return array('result'=>false,'msg'=>"订单不存在");
    }

    public static function getOrderProduct($orid)
    {
        $productList = Orderproduct::getListByOrid($orid);
        return $productList;
    }

    public static function createOrderProduct($data)
    {
        $opid = Orderproduct::createOrderProduct($data);
        return $opid;
    }

    public static function createOrderUser($data)
    {
        $opid = Orderuser::createOrderUser($data);
        return $opid;
    }
}