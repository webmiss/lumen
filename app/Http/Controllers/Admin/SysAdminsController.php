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
		$this->setVar('List',$this->page(array(
			'model'=>'SysAdmin',
			'where'=>$where,
			'getUrl'=>$getUrl
		)));

		// 获取菜单
		$this->setVar('Menus',$this->getMenus());

		// 视图
		$this->setVar('LoadJS', array('system/sys_admin.js'));
		return $this->setTemplate('main','system/admin/index');
	}

	/* 搜索 */
	function search(){
		return $this->view('system/admin/sea');
	}

	/* 添加 */
	function add(){
		return $this->view('system/admin/add');
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
		$this->setVar('edit',SysAdmin::where('id',$_POST['id'])->first());
		return $this->view('system/admin/edit');
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
		return $this->view('system/admin/del');
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
		return $this->view('system/admin/audit');
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
		// 权限数组
		$permArr = $this->splitPerm($_POST['perm']);
		// 所有动作
		$actionM = SysMenuAction::get(['name','perm']);
		// 所有菜单
		$html = '';
		// 一级菜单
		$menu1 = SysMenu::where('fid','0')->get(['id','title']);
		foreach($menu1 as $m1){
			$ck = isset($permArr[$m1->id])?'checked':'';
			$html .= '<div id="oneMenuPerm" class="perm">'."\n";
			$html .= '	<span class="text1"><input type="checkbox" value="'.$m1->id.'" '.@$ck.' /></span>'."\n";
			$html .= '	<span>[<a href="#">-</a>] '.$m1->title.'</span>'."\n";
			$html .= '</div>'."\n";
			// 二级菜单
			$menu2 = SysMenu::where('fid',$m1->id)->get(['id','title']);
			foreach($menu2 as $m2){
				$ck = isset($permArr[$m2->id])?'checked':'';
				$html .= '<div id="twoMenuPerm" class="perm">'."\n";
				$html .= '	<span class="text2"><input type="checkbox" value="'.$m2->id.'" '.@$ck.' /></span>'."\n";
				$html .= '	<span>[<a href="#">-</a>] '.$m2->title.'</span>'."\n";
				$html .= '</div>';
				// 二级菜单
				$menu3 = SysMenu::where('fid',$m2->id)->get(['id','title','perm']);
				foreach($menu3 as $m3){
					$ck = isset($permArr[$m3->id])?'checked':'';
					$html .= '<div id="threeMenuPerm" class="perm perm_action">'."\n";
					$html .= '	<span class="text3"><input type="checkbox" name="threeMenuPerm" value="'.$m3->id.'" '.@$ck.' /></span>'."\n";
					$html .= '	<span>[<a href="#">-</a>] '.$m3->title.'</span>'."\n";
					$html .= '	<span id="actionPerm_'.$m3->id.'"> ( ';
					// 动作菜单
					foreach($actionM as $val){
						if(intval($m3->perm) & intval($val->perm)){
							$ck = @$permArr[$m3->id]&intval($val->perm)?'checked':'';
							$html .= '<span><input type="checkbox" value="'.$val->perm.'" '.@$ck.' /></span><span class="text">'.$val->name.'</span>';
						}
					}
					$html .= ')</span>';
					$html .= '</div>';
				}
			}
		}
		// 视图
		$this->setVar('permHtml', $html);
		return $this->view('system/admin/perm');
	}
	/* 拆分权限 */
	private function splitPerm($perm){
		if($perm){
			$arr = explode(' ', $perm);
			foreach($arr as $val) {
				$num = explode(':', $val);
				$permArr[$num[0]]= $num[1];
			}
			return $permArr;
		}else{return FALSE;}
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

}