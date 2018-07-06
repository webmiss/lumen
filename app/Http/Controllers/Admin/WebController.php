<?php

namespace App\Http\Controllers\Admin;

class WebController extends UserBase{
	/* 首页 */
	function index(){
		// 获取菜单
		$menus = $this->getMenus();
		self::setVar('Menus',$menus);
		// 视图
		return self::setTemplate('main','web/index');
	}
}