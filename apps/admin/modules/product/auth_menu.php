<?php
return array(
    'module_name'  => 'product',
    'module_alias' => '产品管理',
    'module_icon'  => 'all',
    'default_url'=>'product/product/list',
    'child_menu' => array(
        array('name'=>'产品管理','url'=>'product/product/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部产品管理模块','url'=>'product/*'),
        array('name'=>'产品管理','url'=>'product/product/*'),
    )
);