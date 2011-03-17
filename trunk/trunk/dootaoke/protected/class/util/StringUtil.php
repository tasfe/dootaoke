<?php
/**
*  字符串处理函数
*/

class StringUtil {
	/**
	* 转码
	*/
	static public function iconv_gb2312_utf8($target,$ignore = true){
		$ignore_str = "";
		if($ignore){
			$ignore_str = "//IGNORE";
		}
		return iconv('gb2312', 'UTF-8'.$ignore_str,$target);
	}
	
	/**
	* 转码
	*/
	static public function iconv_utf8_gb2312($target,$ignore = true){
		$ignore_str = "";
		if($ignore){
			$ignore_str = "//IGNORE";
		}
		return iconv('UTF-8', 'gb2312'.$ignore_str,$target);
	}
	
	/**
	* 判空
	*/
	static public function isEmpty($value) {
		if(!isset($value) || empty($value)){
			return true;
		}
		
		return false;
	}

	/**
	* 商品名称去掉span
	*/
	static public function convertTitle($title) {
		$name = str_replace("<span class=H>","",$title);
		$name = str_replace("</span>","",$name);
		return trim(replaceSpecialChars(strip_tags($name)));
	}

	// 商品价格单位
	static public function convertPrice($price) {
		return $price . "元";
	}

	// 去掉字符串重的 超连接
	static public function removeSuperlink($input){
	  //$tmp = preg_replace("/(<\/a>)|(<a\s*href[^>]*>)/i",   "",   $input);
	  $tmp = preg_replace("/(<\/a>)|(<a\s*[^>]*>)/i",   "",   $input);
	  
	  return $tmp;
	}

	/**
	* 清除img元素
	*/
	static public function clean_img($target) {
	   return preg_replace("/<img[^>]*src\s*=\s*[^>]*taobao[^>]*\/?>/i", '',$target);
	}

	/**
	* 清除img元素以及超链接
	*/
	static public function removelinkImg($target) {
		return preg_replace("/(<img[^>]*src\s*=\s*[^>]*taobao[^>]*\/?>)|(<\/a>)|(<a\s*[^>]*>)|(<\/embed>)|(<embed\s*[^>]*>)/i", '',$target);
	}

	// 分类数组转成字符串，用“，”分割
	static public function convertCategoryLists2String($cLists,$s = ',') {
		if(!isset($cLists) || sizeof($cLists) < 1) {
			return null;
		}
		
		$result = '';
		foreach($cLists as $k => $v) {
			$result .= $v['category_id'] . $s;
		}
		
		return $result;
	}

	// Originally written by xellisx
	static public function parse_url_query($var){
	  /**
	   *  Use this static public function to parse out the query array element from
	   *  the output of parse_url().
	   */
	  $var  = parse_url($var, PHP_URL_QUERY);

	  return self::parse_query($var);
	}

	// Originally written by xellisx
	static public function parse_query($query){
	  /**
	   *  Use this static public function to parse out the query array element from
	   *  the output of parse_url().
	   */
	  $var  = html_entity_decode($query);
	  $var  = explode('&', $var);
	  $arr  = array();

	  foreach($var as $val)
	   {
	    $x          = explode('=', $val);
	    $arr[$x[0]] = $x[1];
	   }
	  unset($val, $x, $var);
	  return $arr;
	}

	/**
	 * 替换特殊字符
	 */
	static public function replaceSpecialChars($target,$replace=" "){
		$str = preg_replace(SPECIAL_CHARS_1, $replace, $target);
		$str = preg_replace(SPECIAL_CHARS_2, "-", $str);
		
		return trim($str);
	}
	
	/**
	 * 将字符串的url参数转换成键值对
	 */
	static public function convert_urlparams_array($urlparams,$keys=null,$split="-",$containKey=true){
		if(!$urlparams) return '';
		$params = split($split,$urlparams);
		
		$result = array();
		if($keys && sizeof($params) == sizeof($keys) && !$containKey) {
			sort($keys);
			array_walk($params,'StringUtil::urldecode_array');
			$result = array_combine($keys,$params);
		} else {
			$key = array();
			$val = array();
			$cnt = 0;
			foreach($params as $v) {
				if($cnt%2 == 0) {
					$key[] = $v;
				} else {
					$val[] = $v;
				}
				$cnt++;
			}
			array_walk($val,'StringUtil::urldecode_array');
			$result = array_combine($key,$val);
		}
		 
		
		return $result;
	}
	
	/**
	 * 对数组的值编码,配合array_walk
	 */
	static public function urlencode_array(&$val,$key) {
		$val = urlencode($val);
	}
	
	/**
	 * 对数组的值解码,配合array_walk
	 */
	static public function urldecode_array(&$val,$key) {
		$val = urldecode($val);
	}
}
?>