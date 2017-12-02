<?php

namespace App\Http\Controllers\Home;

use Laravel\Lumen\Routing\Controller;

/* 公共控制器 */
class ControllerBase extends Controller{

	/* 构造函数 */
	function __construct(){
		// 常量
		define('MODULE','home');
	}

	/* 模板视图 */
	function setTemplate($template='',$file='',$data=array()){
		// 中间内容
		$data['__CONTENT__'] = view(MODULE.'/'.$file,$data);
		// 总视图
		return view(MODULE.'/layouts/'.$template,$data);
	}

	/* 视图 */
	function view($file='',$data=array()){
		return view(MODULE.'/'.$file,$data);
	}
}
