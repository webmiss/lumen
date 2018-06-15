<?php

namespace App\Http\Controllers\Admin;

use App\Http\Library\Inc;
use App\Http\Model\SysAdmin;
use App\Http\Library\Images;

class IndexController extends ControllerBase{

	/* 构造函数 */
	public function __construct(){
		// 参数
		Inc::getUrlName('admin');
	}

	/* 首页 */
	function index(){
		return $this->view('layouts/login');
	}

	/* 登录 */
	function login(){
		// 是否有提交
		if(!empty($_POST)){
			$uname = trim($_POST['uname']);
			$password = md5($_POST['passwd']);
			$vcode = strtolower($_POST['vcode']);
			$remember = $_POST['remember'];
			// 判断验证码
			if($vcode != $_SESSION['V_CODE']){
				return json_encode(array('status'=>'v','msg'=>'验证码错误！'));
			}else{
				$_SESSION['V_CODE'] = rand(1000,9999);
			}

			// 查询用户
			$data = SysAdmin::orWhere('uname',$uname)
			->orWhere('tel',$uname)
			->orWhere('email',$uname)
			->where('password',$password)
			->first(['id','name','department','position','state','perm']);
			// 判断结果
			if(isset($data)){
				// 是否禁用
				if($data['state']=='1'){
					// 记住用户名
					if($remember=='true'){
						setcookie("uname", $uname);
					}
					// 保存用户信息到Session
					$this->_registerSession($data,$uname);
					// 返回跳转URL
					return json_encode(array('status'=>'y','url'=>'Welcome'));
				}else{
					return json_encode(array('status'=>'n','msg'=>'该用户已被禁用！'));
				}
			}else{
				return json_encode(array('status'=>'n','msg'=>'用户名或密码错误！'));
			}
		}
	}
	// 保存Session
	private function _registerSession($data,$uname){
		// 保存用户信息
		$_SESSION['Admin'] = array(
			'id'=>$data['id'],
			'uname'=>$uname,
			'name'=>$data['name'],
			'department'=>$data['department'],
			'position'=>$data['position'],
			'ltime'=>time()+1800,
			'login'=>TRUE
		);
	}

	/* 退出 */
	public function logout(){
		unset($_SESSION['Admin']);
		return redirect()->route('index');
	}

	/* 验证码 */
	function vcode(){
		Images::getCode(90,36);
	}
}