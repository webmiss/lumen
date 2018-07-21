<?php

namespace App\Http\Controllers\Admin;

use App\Http\Library\Page;
use App\Http\Model\SysMenuAction;

class SysMenusActionController extends UserBase{

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
			'model'=>'SysMenuAction',
			'where'=>$where,
			'getUrl'=>$getUrl
		]));

		// 获取菜单
		self::setVar('Menus',$this->getMenus());

		// 视图
		self::setVar('LoadJS', array('system/sys_menus_action.js'));
		return self::setTemplate('main','system/action/index');
	}

	/* 搜索 */
	function search(){
		return self::view('system/action/sea');
	}

	/* 添加 */
	function add(){
		return self::view('system/action/add');
	}
	function addData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'name'=>trim($_POST['name']),
				'perm'=>trim($_POST['perm']),
				'ico'=>trim($_POST['ico']),
			];
			// 返回信息
			if(SysMenuAction::insert($data)===true){
				return json_encode(array('state'=>'y','url'=>'SysMenusAction/index','msg'=>'添加成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'添加失败！'));
			}
		}
	}

	/* 编辑 */
	function edit(){
		// 视图
		self::setVar('edit',SysMenuAction::where('id',$_POST['id'])->first());
		return self::view('system/action/edit');
	}
	function editData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'name'=>trim($_POST['name']),
				'perm'=>trim($_POST['perm']),
				'ico'=>trim($_POST['ico']),
			];
			// 返回信息
			if(SysMenuAction::where('id',$_POST['id'])->update($data)){
				return json_encode(array('state'=>'y','url'=>'SysMenusAction/index','msg'=>'编辑成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'编辑失败！'));
			}
		}
	}

	/* 删除 */
	function del(){
		return self::view('system/action/del');
	}
	function delData(){
		// 是否有数据提交
		if($_POST){
			// 获取ID
			$id = json_decode($_POST['id']);
			$data = array();
			foreach ($id as $val){
				SysMenuAction::where('id',$val)->delete();
			}
			// 返回信息
			return json_encode(array('state'=>'y','url'=>'SysMenusAction/index','msg'=>'删除成功！'));
		}
	}

}