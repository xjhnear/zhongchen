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
		$order = Order::getList($search,$pageIndex,500);
        $orderproductArr = [];
		if($order){
            $order_out = [];
            foreach ($order as $item) {
                $order_tmp = $productList = [];
                if ($item['id2'] > 0) {
                    if (isset($orderproductArr[$item['orid']][$item['prid']])) continue;
                    $productList['id'] = $item['id2'];
                    $productList['prid'] = $item['prid'];
                    $productList['number'] = $item['number'];
                    $productList['state'] = $item['state'];
                    $productList['gid'] = $item['gid'];
                    $productList['name'] = $item['name'];
                    $productList['img'] = Utility::getImageUrl($item['img']);
                    $productList['specs'] = $item['specs'];
                    $productList['price'] = $item['price2'];
                    $productList['extrainfo'] = $item['extrainfo'];
                    $orderproductArr[$item['orid']][$item['prid']] = 1;
                }
                if (isset($order_out[$item['orid']])) {
                    $order_out[$item['orid']]['productList'][] = $productList;
                } else {
                    $order_tmp['orid'] = $item['orid'];
                    $order_tmp['orderNo'] = $item['orderNo'];
                    $order_tmp['urid'] = $item['urid'];
                    $order_tmp['createTime'] = $item['createTime'];
                    $order_tmp['updateTime'] = $item['updateTime'];
                    $order_tmp['finishTime'] = $item['finishTime'];
                    $order_tmp['price'] = $item['price'];
                    $order_tmp['payStatus'] = $item['payStatus'];
                    $order_tmp['status'] = $item['status'];
                    $order_tmp['state'] = $item['state'];
                    $order_tmp['id'] = $item['id'];
                    $order_tmp['productList'][] = $productList;
                    $order_out[$item['orid']] = $order_tmp;
                }
            }
            $order = array_merge($order_out);
            usort($order, function ($a, $b){
                $criteria = array(
                    'orid'=>'desc'
                );
                foreach($criteria as $what => $order){
                    if($a[$what] == $b[$what]){
                        continue;
                    }
                    return (($order == 'desc')?-1:1) * (($a[$what] < $b[$what]) ? -1 : 1);
                }
                return 0;
            });
            $order = self::pagerProcess($order, $pageIndex, $pageSize);
//		    foreach ($order as &$item) {
//                unset($item['name']);
//                unset($item['tel']);
//                unset($item['address']);
//                unset($item['pay']);
//                unset($item['companyId']);
//                unset($item['companyName']);
//                unset($item['createUrid']);
//                unset($item['contractTime']);
//                unset($item['testUrid']);
//                unset($item['contacts']);
//                unset($item['payTime']);
//                unset($item['receiptType']);
//                unset($item['receiptTitle']);
//                unset($item['receiptContent']);
//                $productList = Orderproduct::getListByOrid($item['orid']);
//                foreach ($productList as &$item_sub) {
//                    unset($item_sub['orid']);
//                    unset($item_sub['createUrid']);
//                    unset($item_sub['createTime']);
//                    unset($item_sub['updateTime']);
//                    unset($item_sub['transportName']);
//                    unset($item_sub['transportNo']);
//                    unset($item_sub['content']);
//                    unset($item_sub['remarks']);
//                }
//                $item['productList'] = $productList;
//            }
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
                $item_sub && $item_sub['img'] = Utility::getImageUrl($item_sub['img']);
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

    public static function deleteOrderProduct($orid)
    {
        $opid = Orderproduct::deleteOrderProduct($orid);
        return $opid;
    }

    public static function createOrderUser($data)
    {
        $opid = Orderuser::createOrderUser($data);
        return $opid;
    }

    public static function checkOrderNo($orderNo)
    {
        $order = Order::getOrderInfoByorderNo($orderNo);
        if($order){
            return true;
        }
        return false;
    }

    public static function pagerProcess($out, $page=1, $pagesize=20)
    {
        $totalItems = count($out);
        $totalPages = ceil(count($out) / $pagesize);
        if (($page > $totalPages)) {
            return [];
        }
        $start = $pagesize * ($page - 1);
        $end = $start + $pagesize;
        $out_thispage = [];
        for ($i=$start;$i<$end;$i++) {
            if (isset($out[$i])) {
                $out_thispage[] = $out[$i];
            }
        }
        return $out_thispage;
    }
}