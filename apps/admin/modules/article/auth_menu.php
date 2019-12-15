<?php
return array(
    'module_name'  => 'article',
    'module_alias' => '首页广告管理',
    'module_icon'  => 'all',
    'default_url'=>'article/article/list',
    'child_menu' => array(
        array('name'=>'文章管理','url'=>'article/article/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部广告管理模块','url'=>'article/*'),
        array('name'=>'广告管理','url'=>'article/article/*'),
    )
);