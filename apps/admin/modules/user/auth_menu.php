<?php
use Illuminate\Support\Facades\Lang;
return array(
    'module_name'  => 'user',
    'module_alias' => '用户管理',
    'module_icon'  => 'all',
    'default_url'=>'user/user/list',
    'child_menu' => array(
        array('name'=>'用户管理','url'=>'user/user/list'),

    ),
    'extra_node'=>array(
        array('name'=>'全部用户模块权限','url'=>'user/*'),
        array('name'=>'用户管理','url'=>'user/user/*')
    )
);