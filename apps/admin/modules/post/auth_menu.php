<?php
return array(
    'module_name'  => 'post',
    'module_alias' => '论坛管理',
    'module_icon'  => 'all',
    'default_url'=>'post/post/list',
    'child_menu' => array(
        array('name'=>'帖子管理','url'=>'post/post/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部论坛管理模块','url'=>'post/*'),
        array('name'=>'帖子管理','url'=>'post/post/*'),
    )
);