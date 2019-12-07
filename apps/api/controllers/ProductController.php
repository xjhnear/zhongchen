<?php
use Yxd\Services\ThreadService;
use Illuminate\Support\Facades\Response;
use Yxd\Services\AtmeService;
use Yxd\Services\UserFeedService;
use Yxd\Services\RelationService;
use Illuminate\Support\Facades\Input;
use Youxiduo\Product\ProductService;
use Youxiduo\User\UserService;
use Youxiduo\Helper\MyHelp;

use PHPImageWorkshop\ImageWorkshop;

class ProductController extends BaseController
{

	public function getlist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);
        $search = Input::only('name');

		$result = ProductService::getProductList($search,$pageIndex,$pageSize);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

    public function getdetail()
    {
        $prid = Input::get('prid',0);

        $result = ProductService::getProductInfo($prid);
        if($result['result']){
            return $this->success(array('result'=>$result['data']));
        }else{
            return $this->fail(201,$result['msg']);
        }
    }
}