<?php
use Yxd\Services\ThreadService;
use Illuminate\Support\Facades\Response;
use Yxd\Services\AtmeService;
use Yxd\Services\UserFeedService;
use Yxd\Services\RelationService;
use Illuminate\Support\Facades\Input;
use Youxiduo\User\PostService;
use Youxiduo\User\UserService;
use Youxiduo\Helper\MyHelp;

use PHPImageWorkshop\ImageWorkshop;

class PostController extends BaseController
{

	public function getlist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);

		$result = PostService::getPostList(0,$pageIndex,$pageSize);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function getmylist()
	{
		$pageIndex = Input::get('pageIndex',1);
		$pageSize = Input::get('pageSize',20);
		$urid = Input::get('urid',0);

		$result = PostService::getPostList($urid,$pageIndex,$pageSize);
		if($result['result']){
			return $this->success(array('result'=>$result['data']));
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function add()
	{
		$title = Input::get('title');
		$content = Input::get('content');
		$urid = Input::get('urid',0);
		$result = PostService::save($urid,$title,$content);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

	public function comment()
	{
		$pid = Input::get('pid');
		$content = Input::get('content');
		$urid = Input::get('urid',0);
		$result = UserService::saveComment($urid,3,$pid,$content);
		if($result['result']){
			return $this->success();
		}else{
			return $this->fail(201,$result['msg']);
		}
	}

}