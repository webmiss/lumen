<?php

namespace App\Http\Library;

class Inc{

	/* URL */
	static function getUrl($url=''){
		$base_url = $_SERVER['SERVER_PORT']=='443'?'https://':'http://';
		$base_url .= $_SERVER['HTTP_HOST'].'/'.$url;
		return $base_url;
	}

	/* URL NAME */
	static function getUrlName($module='home',$controller='index',$action='index'){
		// Url
		$url = array_values(array_filter(explode('/',$_SERVER['REQUEST_URI'])));
		// 默认值
		$h = isset($url[0])?$url[0]:$module;
		$c = isset($url[1])?$url[1]:$controller;
		$a = isset($url[2])?explode('?',$url[2])[0]:$action;
		// 常量
		define('MODULE',$h);
		define('CONTROLLER',$c);
		define('ACTION',$a);
	}

	/* Key */
	static function getKey($str){
		return md5($str.'e33e907621123d2bf01b7f580f316ade');
	}
	static function getKeyArr($parameter=''){
		ksort($parameter);
		reset($parameter);
		$parameter['sign'] = 'e33e907621123d2bf01b7f580f316ade';
		return md5(http_build_query($parameter));
	}

	/* 关键字高亮 */
	static function keyHH($str='', $phrase, $tag_open = '<span style="color:#FF6600">', $tag_close = '</span>'){
		if ($str == ''){return FALSE;}
		if ($phrase != ''){return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);}
		return $str;
	}

	/* 截取中文字符串 */
	static function sysSubStr($String,$Length,$Append = false){
		if(strlen($String) <= $Length ){
			return $String;
		}else{
			$I = 0;
			while ($I < $Length){
				$StringTMP = substr($String,$I,1);
				if( ord($StringTMP) >=224 ){
					$StringTMP = substr($String,$I,3);
					$I = $I + 3;
				}elseif( ord($StringTMP) >=192 ){
					$StringTMP = substr($String,$I,2);
					$I = $I + 2;
				}else{
					$I = $I + 1;
				}
				$StringLast[] = $StringTMP;
			}
			$StringLast = implode("",$StringLast);
			
			if($Append){$StringLast .= "...";}
			
			return $StringLast;
		}
	}

}