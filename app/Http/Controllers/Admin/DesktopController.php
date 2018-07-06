<?php

namespace App\Http\Controllers\Admin;

class DesktopController extends UserBase{
	/* 首页 */
	function index(){
		// 获取菜单
		self::setVar('Menus',$this->getMenus());
		// 视图
		return self::setTemplate('main','desktop/index');
	}
}