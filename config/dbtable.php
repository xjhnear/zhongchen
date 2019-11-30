<?php
$v4_tables = array(

    'youxiduo_system_model_admin'=>array('db'=>'system','table'=>'admin'),
    'youxiduo_system_model_module'=>array('db'=>'system','table'=>'module'),
    'youxiduo_system_model_authgroup'=>array('db'=>'system','table'=>'auth_group'),

    'youxiduo_user_model_user'=>array('db'=>'www','table'=>'user'),
    'youxiduo_user_model_article'=>array('db'=>'www','table'=>'article'),
    'youxiduo_user_model_articlegroup'=>array('db'=>'www','table'=>'article_group'),
    'youxiduo_user_model_video'=>array('db'=>'www','table'=>'video'),
    'youxiduo_user_model_videogroup'=>array('db'=>'www','table'=>'video_group'),
    'youxiduo_user_model_comment'=>array('db'=>'www','table'=>'comment'),
    'youxiduo_user_model_usermobile'=>array('db'=>'www','table'=>'user_mobile'),
    'youxiduo_user_model_feedback'=>array('db'=>'www','table'=>'feedback'),
    'youxiduo_user_model_post'=>array('db'=>'www','table'=>'post'),

    'youxiduo_phone_model_phonebatch'=>array('db'=>'www','table'=>'phone_batch'),
    'youxiduo_phone_model_phonenumbers'=>array('db'=>'www','table'=>'phone_numbers')


);
return $v4_tables;