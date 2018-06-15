<?php
// 登录
$app->get('/',['as'=>'index','uses'=>'IndexController@index']);
$app->get('/index',['uses'=>'IndexController@index']);
$app->get('/index/vcode/{v}',['uses'=>'IndexController@vcode']);
$app->post('/index/login',['uses'=>'IndexController@login']);
$app->get('/index/logout',['as'=>'logout','uses'=>'IndexController@logout']);

$app->get('/Welcome',['middleware' => 'admin','uses'=>'WelcomeController@index']);
$app->get('/Desktop',['middleware' => 'admin','uses'=>'DesktopController@index']);

$app->get('/System',['middleware' => 'admin','uses'=>'SystemController@index']);

$app->get('/SysMenus',['middleware' => 'admin','uses'=>'SysMenusController@index']);
$app->get('/SysMenus/index',['middleware' => 'admin','uses'=>'SysMenusController@index']);
$app->get('/SysMenus/search',['middleware' => 'admin','uses'=>'SysMenusController@search']);
$app->get('/SysMenus/add',['middleware' => 'admin','uses'=>'SysMenusController@add']);
$app->post('/SysMenus/addData',['middleware' => 'admin','uses'=>'SysMenusController@addData']);
$app->post('/SysMenus/edit',['middleware' => 'admin','uses'=>'SysMenusController@edit']);
$app->post('/SysMenus/editData',['middleware' => 'admin','uses'=>'SysMenusController@editData']);
$app->post('/SysMenus/del',['middleware' => 'admin','uses'=>'SysMenusController@del']);
$app->post('/SysMenus/delData',['middleware' => 'admin','uses'=>'SysMenusController@delData']);
$app->post('/SysMenus/getMenu',['middleware' => 'admin','uses'=>'SysMenusController@getMenu']);

$app->get('/SysMenusAction',['middleware' => 'admin','uses'=>'SysMenusActionController@index']);
$app->get('/SysMenusAction/index',['middleware' => 'admin','uses'=>'SysMenusActionController@index']);
$app->get('/SysMenusAction/search',['middleware' => 'admin','uses'=>'SysMenusActionController@search']);
$app->get('/SysMenusAction/add',['middleware' => 'admin','uses'=>'SysMenusActionController@add']);
$app->post('/SysMenusAction/addData',['middleware' => 'admin','uses'=>'SysMenusActionController@addData']);
$app->post('/SysMenusAction/edit',['middleware' => 'admin','uses'=>'SysMenusActionController@edit']);
$app->post('/SysMenusAction/editData',['middleware' => 'admin','uses'=>'SysMenusActionController@editData']);
$app->post('/SysMenusAction/del',['middleware' => 'admin','uses'=>'SysMenusActionController@del']);
$app->post('/SysMenusAction/delData',['middleware' => 'admin','uses'=>'SysMenusActionController@delData']);

$app->get('/SysAdmins',['middleware' => 'admin','uses'=>'SysAdminsController@index']);
$app->get('/SysAdmins/index',['middleware' => 'admin','uses'=>'SysAdminsController@index']);
$app->get('/SysAdmins/search',['middleware' => 'admin','uses'=>'SysAdminsController@search']);
$app->get('/SysAdmins/add',['middleware' => 'admin','uses'=>'SysAdminsController@add']);
$app->post('/SysAdmins/addData',['middleware' => 'admin','uses'=>'SysAdminsController@addData']);
$app->post('/SysAdmins/edit',['middleware' => 'admin','uses'=>'SysAdminsController@edit']);
$app->post('/SysAdmins/editData',['middleware' => 'admin','uses'=>'SysAdminsController@editData']);
$app->post('/SysAdmins/del',['middleware' => 'admin','uses'=>'SysAdminsController@del']);
$app->post('/SysAdmins/delData',['middleware' => 'admin','uses'=>'SysAdminsController@delData']);
$app->post('/SysAdmins/audit',['middleware' => 'admin','uses'=>'SysAdminsController@audit']);
$app->post('/SysAdmins/auditData',['middleware' => 'admin','uses'=>'SysAdminsController@auditData']);
$app->post('/SysAdmins/perm',['middleware' => 'admin','uses'=>'SysAdminsController@perm']);
$app->post('/SysAdmins/permData',['middleware' => 'admin','uses'=>'SysAdminsController@permData']);
$app->post('/SysAdmins/isUname',['middleware' => 'admin','uses'=>'SysAdminsController@isUname']);

$app->get('/Web',['middleware' => 'admin','uses'=>'WebController@index']);