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
use Youxiduo\System\Model\Config;

use PHPImageWorkshop\ImageWorkshop;

class OrderController extends BaseController
{

	public function getlist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);
        $search = Input::only('urid','type','keyword');
        if ($search['urid'] > 0) {
            $userInfo = UserService::getUserInfo($search['urid']);
            $userType = $userInfo['data']['type'];
            if ($userType == 3) {
                unset($search['urid']);
            }
        }
		$result = OrderService::getOrderList($search,$pageIndex,$pageSize);
        $hotline = Config::getInfoByType(1);
		if($result['result']){
			return $this->success(array('result'=>$result['data'],'hotline'=>$hotline['content']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

    public function getdetail()
    {
        $orid = Input::get('orid',0);

        $result = OrderService::getOrderInfo($orid);
        $hotline = Config::getInfoByType(1);
        if($result['result']){
            return $this->success(array('result'=>$result['data'],'hotline'=>$hotline['content']));
        }else{
            return $this->fail(201,$result['msg']);
        }
    }

}