<?php
use Yxd\Services\ThreadService;
use Illuminate\Support\Facades\Response;
use Yxd\Services\AtmeService;
use Yxd\Services\UserFeedService;
use Yxd\Services\RelationService;
use Illuminate\Support\Facades\Input;
use Youxiduo\Order\OrderService;
use Youxiduo\User\UserService;
use Youxiduo\Helper\MyHelp;

use PHPImageWorkshop\ImageWorkshop;

class OrderController extends BaseController
{

	public function getlist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);
        $search = Input::only('urid','type');

		$result = OrderService::getOrderList($search,$pageIndex,$pageSize);
		if($result['result']){
			return $this->success(array('result'=>$result['data'],'hotline'=>'4006800000'));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

    public function getdetail()
    {
        $orid = Input::get('orid',0);

        $result = OrderService::getOrderInfo($orid);
        if($result['result']){
            return $this->success(array('result'=>$result['data'],'hotline'=>'4006800000'));
        }else{
            return $this->fail(201,$result['msg']);
        }
    }

}