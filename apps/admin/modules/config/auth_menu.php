<?php
return array(
    'module_name'  => 'config',
    'module_alias' => '系统管理',
    'module_icon'  => 'all',
    'default_url'=>'config/config/info',
    'child_menu' => array(
        array('name'=>'系统管理','url'=>'config/config/info'),
    ),
    'extra_node'=>array(
        array('name'=>'全部系统管理模块','url'=>'config/*'),
        array('name'=>'系统管理','url'=>'config/config/*'),
    )
);