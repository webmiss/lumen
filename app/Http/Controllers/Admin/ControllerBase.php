<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller;

/* 公共控制器 */
class ControllerBase extends Controller{

	static private $var=[];

	/* 设置参数 */
	static function setVar($name,$value=''){
		self::$var[$name] = $value;
	}

	/* 获取参数 */
	static function getVar($name){
		return self::$var[$name];
	}

	/* 模板视图 */
	static function setTemplate($template='',$file=''){
		// 中间内容
		self::$var['__CONTENT__'] = view(MODULE.'/'.$file,self::$var);
		// 总视图
		return view(MODULE.'/layouts/'.$template,self::$var);
	}

	/* 视图 */
	static function view($file=''){
		return view(MODULE.'/'.$file,self::$var);
	}

}
