<?php

namespace App\Http\Controllers\Admin;

use App\Http\Library\Page;
use App\Http\Model\SysMenu;
use App\Http\Model\SysMenuAction;

class SysMenusController extends UserBase{

	/* 首页 */
	function index(){
		// 分页
		if(isset($_GET['search'])){
			$like = Page::where();
			// 生成搜索条件
			$where = array();
			foreach ($like['data'] as $key => $val){
				$where[] = [$key,'LIKE','%'.$val.'%'];
			}
			$getUrl = $like['getUrl'];
			self::setVar('getUrl',$like['search']);
		}else{
			$where = '';
			$getUrl = '';
		}
		// 数据
		self::setVar('List',Page::get([
			'model'=>'SysMenu',
			'where'=>$where,
			'getUrl'=>$getUrl
		]));

		// 获取菜单
		self::setVar('Menus',$this->getMenus());
		
		// 视图
		self::setVar('LoadJS', array('system/sys_menus.js'));
		return self::setTemplate('main','system/menus/index');
	}

	/* 搜索 */
	function search(){
		return self::view('system/menus/sea');
	}

	/* 添加 */
	function add(){
		// 所有权限
		self::setVar('perm',SysMenuAction::get(['name','perm']));
		return self::view('system/menus/add');
	}
	function addData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'fid'=>trim($_POST['fid']),
				'title'=>trim($_POST['title']),
				'url'=>trim($_POST['url']),
				'perm'=>trim($_POST['perm']),
				'ico'=>trim($_POST['ico']),
				'sort'=>trim($_POST['sort']),
				'remark'=>trim($_POST['remark']),
			];
			// 返回信息
			if(SysMenu::insert($data)===true){
				return json_encode(array('state'=>'y','url'=>'SysMenus/index','msg'=>'添加成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'添加失败！'));
			}
		}
	}

	/* 编辑 */
	function edit(){
		// 所有权限
		self::setVar('perm',SysMenuAction::get(['name','perm']));
		// 视图
		self::setVar('edit',SysMenu::where('id',$_POST['id'])->first());
		return self::view('system/menus/edit');
	}
	function editData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'fid'=>trim($_POST['fid']),
				'title'=>trim($_POST['title']),
				'url'=>trim($_POST['url']),
				'perm'=>trim($_POST['perm']),
				'ico'=>trim($_POST['ico']),
				'sort'=>trim($_POST['sort']),
				'remark'=>trim($_POST['remark']),
			];
			// 返回信息
			if(SysMenu::where('id',$_POST['id'])->update($data)){
				return json_encode(array('state'=>'y','url'=>'SysMenus/index','msg'=>'编辑成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'编辑失败！'));
			}
		}
	}

	/* 删除 */
	function del(){
		return self::view('system/menus/del');
	}
	function delData(){
		// 是否有数据提交
		if($_POST){
			// 获取ID
			$id = json_decode($_POST['id']);
			$data = array();
			foreach ($id as $val){
				SysMenu::where('id',$val)->delete();
			}
			// 返回信息
			return json_encode(array('state'=>'y','url'=>'SysMenus/index','msg'=>'删除成功！'));
		}
	}

	/* 联动菜单数据 */
	function getMenu(){
		// 实例化
		$menus = SysMenu::where('fid',$_POST['fid'])->get(['id','title']);
		$data = [];
		foreach($menus as $val){
			$data[] = [$val->id,$val->title];
		}
		// 返回数据
		return json_encode($data);
	}
	
}