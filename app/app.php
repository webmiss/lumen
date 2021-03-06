<?php

// 编码
header('Content-type: text/html; charset=utf-8');

// 开启session
@session_start();

// 自动加载
require_once __DIR__.'/../vendor/autoload.php';

try {
	(new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
}

/* 创建项目 */
$app = new Laravel\Lumen\Application(
	realpath(__DIR__.'/../')
);

/* 注册：中间件 */
$app->routeMiddleware([
	'admin' => App\Http\Middleware\Admin::class,
]);

/* 路由群组 */
$app->router->group(['namespace' => 'App\Http\Controllers'], function ($app) {
	// 网站前台
	$app->group(['namespace' => 'Home'], function ($app) {
		require __DIR__.'/Http/Routes/home.php';
	});
	// 网站后台
	$app->group(['prefix' => 'admin','namespace' => 'Admin'], function ($app) {
		require __DIR__.'/Http/Routes/admin.php';
	});
});

/* Model */
$app->withFacades();
$app->withEloquent();

return $app;
