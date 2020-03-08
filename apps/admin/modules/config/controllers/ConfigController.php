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
        $data['data2'] = Config::getInfoByType(2);
        $data['data2']['content'] = Config::get('app.img_url').$data['data2']['content'];
        $data['data3'] = Config::getInfoByType(3);
        return $this->display('config_info', $data);
    }

    public function postSave()
    {
        $input = Input::only('id', 'content','id2', 'content2','id3', 'content3');

        if ($input['id'] > 0) {
            $data['id'] = $input['id'];
        }
        $data['type'] = 1;
        $data['content'] = $input['content'];
        $result = Config::saveInfo($data);

        if ($input['id2'] > 0) {
            $data2['id'] = $input['id2'];
        }
        $data2['type'] = 2;
        $data2['content'] = $input['content2'];
        $result2 = Config::saveInfo($data2);

        if ($input['id3'] > 0) {
            $data3['id'] = $input['id3'];
        }
        $data3['type'] = 3;
        $data3['content'] = $input['content3'];
        $result3 = Config::saveInfo($data3);

        if ($result) {
            return $this->redirect('config/config/info')->with('global_tips', '保存成功');
        } else {
            return $this->back('保存失败');
        }
    }

}