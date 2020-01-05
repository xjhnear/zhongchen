<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 11:25
 */
namespace modules\config\controllers;

use Yxd\Modules\Core\BackendController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
//use Illuminate\Support\Facades\Config;
use Youxiduo\Helper\MyHelp;
use Youxiduo\System\Model\Config;

class ConfigController extends BackendController
{

    public function _initialize()
    {
        $this->current_module = 'config';
    }

    public function getInfo()
    {
        $data = array();
        $data['data'] = Config::getInfoByType(1);
        return $this->display('config_info', $data);
    }

    public function postSave()
    {
        $input = Input::only('id', 'content');
        
        $data['id'] = $input['id'];
        $data['type'] = 1;
        $data['content'] = $input['content'];
        $result = Config::saveInfo($data);
        
        if ($result) {
            return $this->redirect('config/config/info')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

}