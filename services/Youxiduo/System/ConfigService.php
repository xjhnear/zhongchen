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
//use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

use Youxiduo\Base\BaseService;
use Youxiduo\System\Model\Config;

class ConfigService extends BaseService
{

    public static function fetchByType($type)
    {
        $config = Config::getInfoByType($type);
        if($config){
            return $config;
        }
        return false;
    }
}