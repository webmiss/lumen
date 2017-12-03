<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\SysMenu;
use App\Http\Model\SysMenuAction;

/* 用户控制器 */
class UserBase extends ControllerBase{

	static private $perm = '';
	static private $cid=[];

	/* 构造函数 */
	function __construct(){
		// 赋值权限
		self::$perm = $_SESSION['Admin']['perm'];
		// 用户信息
		$this->setVar('Uinfo',$_SESSION['Admin']);
	}

	/* 获取菜单 */
	function getMenus(){
		// CID
		$C = SysMenu::where('url',CONTROLLER)->first(['id','fid','title']);
		self::$cid[] = $C->id;
		self::_getCid($C->fid);
		krsort(self::$cid);
		self::$cid = array_values(self::$cid);
		// 数据
		return [
			'Ctitle'=>$C->title,
			'CID'=>self::$cid,
			// 获取菜单动作
			'action'=>self::_actionMenus(self::$perm[end(self::$cid)]),
			'Data'=>self::_getMenu()
		];
	}
	// 递归菜单
	static private function _getMenu($fid=0){
		$data=[];
		$M = SysMenu::where('fid',$fid)->get(['id','fid','title','url','ico'])->toArray();
		foreach($M as $val){
			if(isset(self::$perm[$val['id']])){
				$val['menus'] = self::_getMenu($val['id']);
				$data[] = (object)$val;
			}
		}
		return (object)$data;
	}
	// 动作菜单
	static private function _actionMenus($perm=''){
		$data = array();
		// 全部动作菜单
		$aMenus = SysMenuAction::get(['name','ico','perm']);
		foreach($aMenus as $val){
			// 匹配权限值
			if(intval($perm)&intval($val->perm)){
				$data[] = array('name'=>$val->name,'ico'=>$val->ico);
			}
		}
		return $data;
	}
	// 递归CID
	static private function _getCid($fid){
		if($fid!=0){
			$m = SysMenu::where('id',$fid)->first(['id','fid']);
			self::$cid[] = $m->id;
			self::_getCid($m->fid);
		}
	}
}