<?php

namespace App\Http\Controllers\Home;

class IndexController extends ControllerBase{

	/* 首页 */
	function index(){
		// 模板视图
		return $this->setTemplate('main','index',['ct'=>'ct']);
	}
}