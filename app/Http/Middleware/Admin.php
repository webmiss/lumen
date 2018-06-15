<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Library\Inc;
use App\Http\Model\SysAdmin;
use App\Http\Model\SysMenu;

class Admin{

	/* 构造函数 */
	public function __construct(){
		// 参数
		Inc::getUrlName('admin');
	}

	/* 是否登陆 */
	public function handle($request, Closure $next){
		// 是否登录
		$admin = isset($_SESSION['Admin'])?$_SESSION['Admin']:'';
		if(!$admin){return redirect()->route('index');}
		// 是否超时
		$ltime = $admin['ltime'];
		$ntime = time();
		if(!$admin['login'] || $admin['ltime']<time()){
			abort(404);
			return redirect()->route('logout');
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
			abort(404);
			return redirect()->route('logout');
		}
		// 权限
		$_SESSION['Admin']['perm'] = $data;
		// 继续
		return $next($request);
	}
}