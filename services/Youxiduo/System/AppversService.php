<?php
/**
 * @package Youxiduo
 * @category Android 
 * @link http://dev.youxiduo.com
 * @copyright Copyright (c) 2008 Youxiduo.com 
 * @license http://www.youxiduo.com/license
 * @since 4.0.0
 *
 */
namespace Youxiduo\System;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

use Youxiduo\Base\BaseService;
use Youxiduo\System\Model\Appvers;

class AppversService extends BaseService
{
	public static function getAppversList($search,$pageIndex=1,$pageSize=20)
	{
		$appvers = Appvers::getList($search,$pageIndex,$pageSize);
		if($appvers){
			return array('result'=>true,'data'=>$appvers);
		}
		return array('result'=>false,'msg'=>"暂无数据");
	}

    public static function fetchByPlatform($platform)
    {
        $appvers = Appvers::getInfoByPlatform($platform);
        if($appvers){
            return $appvers;
        }
        return false;
    }
}