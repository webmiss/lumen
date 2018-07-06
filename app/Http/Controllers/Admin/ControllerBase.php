<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller;
use App\Http\Library\Inc;

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

	/* 分页 */
	function page($config=array()){
		// 必须参数
		if(!isset($config['model']))die('请传入模型');
		// 命名空间
		$namespace = isset($config['namespace'])?$config['namespace']:'App\\Http\\Model\\';
		$config['model'] = $namespace.$config['model'];
		// 默认值
		$field = isset($config['field'])?$config['field']:'*';
		$where = isset($config['where'])&&!empty($config['where'])?$config['where']:[];
		$order = isset($config['order'])?$config['order']:'id';
		$limit = isset($config['limit'])?$config['limit']:15;
		$getUrl = isset($config['getUrl'])?$config['getUrl']:'';
		// Page
		$rows = isset($config['where'])&&!empty($config['where'])?$config['model']::where($config['where'])->count():$config['model']::count();
		$page = empty($_GET['page'])?1:$_GET['page'];
		$page_count = ceil($rows/$limit);
		$page = $page>=$page_count?$page_count:$page;
		// 数据
		$start=$limit*($page-1);
		$data = $config['model']::select($field)->where($where)->orderBy($order)->skip($start)->take($limit)->get();

		// 分页菜单
		$html = '';
		if($page==1 || $page==0){
			$html .= '<span>首页</span>';
			$html .= '<span>上一页</span>';
		}else{
			$html .= '<a href="'.Inc::getUrl(MODULE.'/'.CONTROLLER.'/'.ACTION.'?page=1'.$getUrl).'">首页</a>';
			$html .= '<a href="'.Inc::getUrl(MODULE.'/'.CONTROLLER.'/'.ACTION.'?page='.($page-1).$getUrl).'">上一页</a>';
		}
		if($page==$page_count){
			$html .= '<span>下一页</span>';
			$html .= '<span>末页</span>';
		}else{
			$html .= '<a href="'.Inc::getUrl(MODULE.'/'.CONTROLLER.'/'.ACTION.'?page='.($page+1).$getUrl).'">下一页</a>';
			$html .= '<a href="'.Inc::getUrl(MODULE.'/'.CONTROLLER.'/'.ACTION.'?page='.$page_count.$getUrl).'">末页</a>';
		}
		$html .= '第'.$page.'/'.$page_count.'页, 共'.$rows.'条';

		return array('data'=>$data,'page'=>$html);
	}
	// 分页条件
	function pageWhere(){
		$getUrl = '';
		$like = $_GET;
		$page = isset($like['page'])?$like['page']:1;
		unset($like['_url']);
		unset($like['page']);
		// 条件字符串
		foreach($like as $key=>$val){
			if($val==''){
				unset($like[$key]);
			}else{
				$getUrl .= '&'.$key.'='.$val;
			}
		}
		unset($like['search']);
		// 传递搜索条件
		$this->setVar('getUrl','?search&page='.$page.$getUrl);
		// 返回数据
		return array('getUrl'=>$getUrl,'data'=>$like);
	}
}
