<?php
return array(
    'module_name'  => 'video',
    'module_alias' => '视频管理',
    'module_icon'  => 'all',
    'default_url'=>'video/video/list',
    'child_menu' => array(
        array('name'=>'分类管理','url'=>'video/group/list'),
        array('name'=>'视频管理','url'=>'video/video/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部视频管理模块','url'=>'video/*'),
        array('name'=>'分类管理','url'=>'video/group/*'),
        array('name'=>'视频管理','url'=>'video/video/*'),
    )
);