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
		$platform = Input::get('platform','');
		$udid = Input::get('udid','');
		$urid = Input::get('urid','');
		$result = AppversService::fetchByPlatform($platform);
		if($result){
            if(version_compare($version, $result['appver'], '<')){
                $hasNew = 1;
            } else {
                $hasNew = 0;
            }
            $result = [
                'hasNew' => $hasNew,
                'latest_ver' => $result['appver'],
                'info' => $result['info'],
                'force' => $result['force'],
                'link' => $result['link'],
            ];
            return $this->success($result);
        }else{
            return $this->fail(500,'暂无数据');
        }
	}	
}