<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\SysAdmin;
use App\Http\Model\SysMenu;
use App\Http\Model\SysMenuAction;

/* 用户控制器 */
class UserBase extends ControllerBase{

	static private $perm = '';
	static private $cid=[];

	/* 构造函数 */
	function __construct(){
		parent::__construct();
		// 是否登录
		$admin = @$_SESSION['Admin'];
		$ltime = $admin['ltime'];
		$ntime = time();
		if(!$admin['logged_in'] || $ltime<$ntime){
			$this->redirect('index/loginOut');
			return false;
		}else{
			$_SESSION['Admin']['ltime'] = time()+1800;
		}
		// 菜单权限
		$perm = SysAdmin::where('id',$admin['id'])->first(['perm']);
		$data = [];
		$arr = explode(' ',$perm->perm);
		foreach($arr as $val){
			$a = explode(':',$val);
			$data[$a[0]] = $a[1];
		}
		// 判断权限
		$mid =  SysMenu::where('url',CONTROLLER)->first(['id']);
		if(!isset($data[$mid->id])){
			return $this->redirect('index/loginOut');
		}
		// 赋值权限
		self::$perm = $data;
		// 用户信息
		$this->setVar('Uinfo',$admin);
	}

	/* 菜单权限 */
	function getPerm($uid){
		$perm = SysAdmin::where('id',$uid)->first(['perm']);
		// 拆分用户权限
		$data = array();
		$arr = explode(' ', $perm->perm);
		foreach($arr as $val) {
			$num = explode(':', $val);
			$data[$num[0]] = $num[1];
		}
		// 菜单ID
		$mid = SysMenu::where('url',CONTROLLER)->first(['id']);
		// 判断权限
		if(!isset($data[$mid->id])){
			$this->redirect('index/loginOut');
		}
		// 保存菜单
		$_SESSION['Admin']['perm'] = $data;
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