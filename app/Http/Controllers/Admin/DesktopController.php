<?php

namespace App\Http\Controllers\Admin;

class DesktopController extends UserBase{
	/* 首页 */
	function index(){
		// 获取菜单
		$this->setVar('Menus',$this->getMenus());
		// 视图
		return $this->setTemplate('main','desktop/index');
	}
}