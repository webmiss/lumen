<?php

namespace App\Http\Controllers\Admin;

class WebController extends UserBase{
	/* 首页 */
	function index(){
		// 获取菜单
		$menus = $this->getMenus();
		$this->setVar('Menus',$menus);
		// 视图
		return $this->setTemplate('main','web/index');
	}
}