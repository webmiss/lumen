<?php

namespace App\Http\Controllers\Admin;

use Laravel\Lumen\Routing\Controller;
use App\Http\Library\Inc;

/* 公共控制器 */
class ControllerBase extends Controller{

	private $var = array();

	/* 设置参数 */
	function setVar($name,$value=''){
		$this->var[$name] = $value;
	}

	/* 获取参数 */
	function getVar($name){
		return $this->var[$name];
	}

	/* 模板视图 */
	function setTemplate($template='',$file=''){
		// 中间内容
		$this->var['__CONTENT__'] = view(MODULE.'/'.$file,$this->var);
		// 总视图
		return view(MODULE.'/layouts/'.$template,$this->var);
	}

	/* 视图 */
	function view($file=''){
		return view(MODULE.'/'.$file,$this->var);
	}

	/* 分页 */
	function page($config=array()){

		// 必须参数
		if(!isset($config['model'])){die('请传入模型');}
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

		// 计算页数
		$page_count = ceil($rows/$limit);
		if($page >= $page_count){
			$page = $page_count;
		}

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
