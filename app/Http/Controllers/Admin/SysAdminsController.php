<?php

namespace App\Http\Controllers\Admin;

use App\Library\Safety;

use App\Http\Model\SysAdmin;
use App\Http\Model\SysMenu;
use App\Http\Model\SysMenuAction;

class SysAdminsController extends UserBase{

	/* 首页 */
	function index(){
		// 分页
		if(isset($_GET['search'])){
			$like = $this->pageWhere();
			// 生成搜索条件
			$where = array();
			foreach ($like['data'] as $key => $val){
				$where[] = [$key,'LIKE','%'.$val.'%'];
			}
			$getUrl = $like['getUrl'];
		}else{
			$where = '';
			$getUrl = '';
		}
		// 数据
		self::setVar('List',$this->page(array(
			'model'=>'SysAdmin',
			'where'=>$where,
			'getUrl'=>$getUrl
		)));

		// 获取菜单
		self::setVar('Menus',$this->getMenus());

		// 视图
		self::setVar('LoadJS', array('system/sys_admin.js'));
		return self::setTemplate('main','system/admin/index');
	}

	/* 搜索 */
	function search(){
		return self::view('system/admin/sea');
	}

	/* 添加 */
	function add(){
		return self::view('system/admin/add');
	}
	function addData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'uname'=>trim($_POST['uname']),
				'password'=>md5($_POST['passwd']),
				'email'=>trim($_POST['email']),
				'tel'=>trim($_POST['tel']),
				'name'=>trim($_POST['name']),
				'department'=>trim($_POST['department']),
				'position'=>trim($_POST['position']),
				'rtime'=>date('Y-m-d H:i:s'),
			];
			// 验证
			$res = Safety::isRight('uname',$data['uname']);
			if($res!==true){return json_encode(array('state'=>'n','msg'=>$res));}
			$res = Safety::isRight('passwd',$_POST['passwd']);
			if($res!==true){return json_encode(array('state'=>'n','msg'=>$res));}
			$res = Safety::isRight('email',$data['email']);
			if($res!==true){return json_encode(array('state'=>'n','msg'=>$res));}
			$res = Safety::isRight('tel',$data['tel']);
			if($res!==true){return json_encode(array('state'=>'n','msg'=>$res));}
			// 是否存在用户
			$isNull = SysAdmin::orWhere('uname',$data['uname'])
				->orWhere('tel',$data['tel'])
				->orWhere('email',$data['email'])
				->first(['uname']);
			if($isNull){
				return json_encode(array('state'=>'n','msg'=>'该用户名已经存在！'));
			}
			// 返回信息
			if(SysAdmin::insert($data)===true){
				return json_encode(array('state'=>'y','url'=>'SysAdmins/index','msg'=>'添加成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'添加失败！'));
			}
		}
	}

	/* 编辑 */
	function edit(){
		// 视图
		self::setVar('edit',SysAdmin::where('id',$_POST['id'])->first());
		return self::view('system/admin/edit');
	}
	function editData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data = [
				'name'=>trim($_POST['name']),
				'department'=>trim($_POST['department']),
				'position'=>trim($_POST['position']),
			];
			// 是否修改密码
			if(!empty($_POST['passwd'])){
				$res = Safety::isRight('passwd',$_POST['passwd']);
				if($res!==true){return json_encode(array('state'=>'n','msg'=>$res));}
				// 原密码判断
				$isNull = SysAdmin::where('id',$_POST['id'])
					->where('password',md5($_POST['passwd1']))
					->first(['id']);
				if($isNull){
					$data['password'] = md5($_POST['passwd']);
				}else{
					return json_encode(array('state'=>'n','msg'=>'原密码错误！'));
				}
			}
			// 返回信息
			if(SysAdmin::where('id',$_POST['id'])->update($data)){
				return json_encode(array('state'=>'y','url'=>'SysAdmins/index','msg'=>'编辑成功！'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'编辑失败！'));
			}
		}
	}

	/* 删除 */
	function del(){
		return self::view('system/admin/del');
	}
	function delData(){
		// 是否有数据提交
		if($_POST){
			// 获取ID
			$id = json_decode($_POST['id']);
			$data = array();
			foreach ($id as $val){
				SysAdmin::where('id',$val)->delete();
			}
			// 返回信息
			return json_encode(array('state'=>'y','url'=>'SysAdmins/index','msg'=>'删除成功！'));
		}
	}

	/* 审核 */
	function audit(){
		return self::view('system/admin/audit');
	}
	function auditData(){
		// 是否有数据提交
		if($_POST){
			// 获取ID
			$arr = json_decode($_POST['id']);
			foreach ($arr as $val){
				SysAdmin::where('id',$val)->update(['state'=>$_POST['state']]);
			}
			return json_encode(array('state'=>'y','url'=>'SysAdmins/index','msg'=>'审核成功！'));
		}
	}

	/* 是否存在 */
	function isUname(){
		// 是否提交
		if(!isset($_POST['name']) || !isset($_POST['val'])){return false;}
		// 条件
		$where = '';
		if($_POST['name']=='uname'){
			$where = 'uname';
		}elseif($_POST['name']=='tel'){
			$where = 'tel';
		}elseif($_POST['name']=='email'){
			$where = 'email';
		}
		// 查询
		if($where){
			$data = SysAdmin::where($where,trim($_POST['val']))->first(['id']);
			return $data?json_encode(['state'=>'y']):json_encode(['state'=>'n']);
		}
	}

	/* 权限 */
	function perm(){
		// 拆分权限
		$permArr=[];
		$arr = explode(' ',$_POST['perm']);
		foreach($arr as $val){
			$a=explode(':',$val);
			$permArr[$a[0]]=$a[1];
		}
		self::setVar('permArr',$permArr);
		self::setVar('Perm',SysMenuAction::get(['name','perm']));
		self::setVar('Menus',$this->Menus());
		return self::view('system/admin/perm');
	}
	function permData(){
		// 是否有数据提交
		if($_POST){
			// 采集数据
			$data['perm'] = trim($_POST['perm']);
			// 返回信息
			if(SysAdmin::where('id',$_POST['id'])->update($data)){
				return json_encode(array('state'=>'y','url'=>'SysAdmins/index'));
			}else{
				return json_encode(array('state'=>'n','msg'=>'权限编辑失败！'));
			}
		}
	}
	// 递归全部菜单
	private function Menus($fid='0'){
		$data=[];
		$M = SysMenu::where('fid',$fid)->get(['id','fid','title','perm'])->toArray();
		foreach($M as $val){
			$val['menus'] = $this->Menus($val['id']);
			$data[] = (object)$val;
		}
		return (object)$data;
	}

}