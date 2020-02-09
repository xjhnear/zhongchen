<?php
use Yxd\Modules\Core\CacheService;
use Illuminate\Support\Facades\Input;
use Youxiduo\System\AppversService;
use Youxiduo\System\Model\Config;
use Youxiduo\Helper\Utility;

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

    public function config()
    {
        $version = Input::get('version','');
        $platform = Input::get('platform','');
        $udid = Input::get('udid','');
        $urid = Input::get('urid','');

        $result = [];
        $startupPage =  Config::getInfoByType(2);
        if ($startupPage) {
            $result['startupPage'] = Utility::getImageUrl($startupPage['content']);
        } else {
            $result['startupPage'] = '';
        }
        $startupSecond =  Config::getInfoByType(3);
        if ($startupSecond) {
            $result['startupSecond'] = $startupSecond['content'];
        } else {
            $result['startupSecond'] = '';
        }
        return $this->success($result);
    }
}