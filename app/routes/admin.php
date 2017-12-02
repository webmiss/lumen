<?php
// 登录
$app->get('/',['uses'=>'IndexController@index']);
$app->get('/index',['uses'=>'IndexController@index']);
$app->get('/index/vcode/{v}',['uses'=>'IndexController@vcode']);
$app->post('/index/login',['uses'=>'IndexController@login']);
$app->get('/index/loginOut',['uses'=>'IndexController@loginOut']);

$app->get('/Welcome',['uses'=>'WelcomeController@index']);
$app->get('/Desktop',['uses'=>'DesktopController@index']);

$app->get('/System',['uses'=>'SystemController@index']);

$app->get('/SysMenus',['uses'=>'SysMenusController@index']);
$app->get('/SysMenus/index',['uses'=>'SysMenusController@index']);
$app->get('/SysMenus/search',['uses'=>'SysMenusController@search']);
$app->get('/SysMenus/add',['uses'=>'SysMenusController@add']);
$app->post('/SysMenus/addData',['uses'=>'SysMenusController@addData']);
$app->post('/SysMenus/edit',['uses'=>'SysMenusController@edit']);
$app->post('/SysMenus/editData',['uses'=>'SysMenusController@editData']);
$app->post('/SysMenus/del',['uses'=>'SysMenusController@del']);
$app->post('/SysMenus/delData',['uses'=>'SysMenusController@delData']);
$app->post('/SysMenus/getMenu',['uses'=>'SysMenusController@getMenu']);

$app->get('/SysMenusAction',['uses'=>'SysMenusActionController@index']);
$app->get('/SysMenusAction/index',['uses'=>'SysMenusActionController@index']);
$app->get('/SysMenusAction/search',['uses'=>'SysMenusActionController@search']);
$app->get('/SysMenusAction/add',['uses'=>'SysMenusActionController@add']);
$app->post('/SysMenusAction/addData',['uses'=>'SysMenusActionController@addData']);
$app->post('/SysMenusAction/edit',['uses'=>'SysMenusActionController@edit']);
$app->post('/SysMenusAction/editData',['uses'=>'SysMenusActionController@editData']);
$app->post('/SysMenusAction/del',['uses'=>'SysMenusActionController@del']);
$app->post('/SysMenusAction/delData',['uses'=>'SysMenusActionController@delData']);

$app->get('/SysAdmins',['uses'=>'SysAdminsController@index']);
$app->get('/SysAdmins/index',['uses'=>'SysAdminsController@index']);
$app->get('/SysAdmins/search',['uses'=>'SysAdminsController@search']);
$app->get('/SysAdmins/add',['uses'=>'SysAdminsController@add']);
$app->post('/SysAdmins/addData',['uses'=>'SysAdminsController@addData']);
$app->post('/SysAdmins/edit',['uses'=>'SysAdminsController@edit']);
$app->post('/SysAdmins/editData',['uses'=>'SysAdminsController@editData']);
$app->post('/SysAdmins/del',['uses'=>'SysAdminsController@del']);
$app->post('/SysAdmins/delData',['uses'=>'SysAdminsController@delData']);
$app->post('/SysAdmins/audit',['uses'=>'SysAdminsController@audit']);
$app->post('/SysAdmins/auditData',['uses'=>'SysAdminsController@auditData']);
$app->post('/SysAdmins/perm',['uses'=>'SysAdminsController@perm']);
$app->post('/SysAdmins/permData',['uses'=>'SysAdminsController@permData']);
$app->post('/SysAdmins/isUname',['uses'=>'SysAdminsController@isUname']);

$app->get('/Web',['uses'=>'WebController@index']);