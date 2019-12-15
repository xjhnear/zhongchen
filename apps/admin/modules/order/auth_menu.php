<?php
return array(
    'module_name'  => 'order',
    'module_alias' => '订单管理',
    'module_icon'  => 'all',
    'default_url'=>'order/order/list',
    'child_menu' => array(
        array('name'=>'订单管理','url'=>'order/order/list'),
    ),
    'extra_node'=>array(
        array('name'=>'全部订单管理模块','url'=>'order/*'),
        array('name'=>'订单管理','url'=>'order/order/*'),
    )
);