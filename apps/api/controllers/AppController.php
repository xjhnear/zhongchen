<?php
use Yxd\Modules\Core\CacheService;
use Illuminate\Support\Facades\Input;
use Youxiduo\System\AppversService;

class AppController extends BaseController 
{
	/**
	 * 检查版本
	 */
	public function upGrade()
	{
		$version = Input::get('version','');
		$search['platform'] = Input::get('platform','');
		$udid = Input::get('udid','');
		$urid = Input::get('urid','');
		$result = AppversService::getAppversList($search,1,1);
		print_r($result);exit;
		if(version_compare($version, '1.0.0', '<')){
			$hasNew = 1;
		} else {
			$hasNew = 0;
		}
		$result = [
			'hasNew' => $hasNew,
			'latest_ver' => '1.0.0',
		];
		return $this->success($result);
	}	
}