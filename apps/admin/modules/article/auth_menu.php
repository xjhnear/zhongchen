<?php
return array(
    'module_name'  => 'article',
    'module_alias' => '文章管理',
    'module_icon'  => 'all',
    'default_url'=>'article/article/list',
    'child_menu' => array(
        array('name'=>'分类管理','url'=>'article/group/list'),
        array('name'=>'文章管理','url'=>'article/article/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部文章管理模块','url'=>'article/*'),
        array('name'=>'分类管理','url'=>'article/group/*'),
        array('name'=>'文章管理','url'=>'article/article/*'),
    )
);