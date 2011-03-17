<?php
/*
 * Created on 2010-12-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class TaobaoUtil {
	/**
	* 获得指定淘宝图片
	*/
	public static function convertTaobaoPic($basicImg,$suffix,$tolocal=false) {
		if(is_array($basicImg)) {
			return TAOBAO_NO_PIC;
		}
		if($tolocal){
			return convert_taobaopic_tolocal($basicImg . $suffix);
		}
		return $basicImg . $suffix;
	}
	
	/**
	*图片数组转换
	*/
	public static function convertTopImages($images) {
		if(!is_array($images) || sizeof($images) < 1) return "[]";
		
		$imgs = '[';
		foreach($images as $id => $img) {
				$img = str_replace('taobao.net','taobao.com',$img);
				$img = str_replace('taobao.com','taobaocdn.com',$img);
				$imgs .= '"'.$id . '|' . $img . '",';
			}
		$imgs .='""]';
		
		return $imgs;
	}
	
	/**
	 * 删除title中的span
	 */
	public static function removeSpan($title) {
		$name = str_replace("<span class=H>","",$title);
		$name = str_replace("</span>","",$name);
		return trim(strip_tags($name));
	}
	
	/**
	 * 属性名称转换
	 */
	public static function convert_props_name($props_name,$hasId = true) {
		if(!$props_name) return "";
		$props = explode(';',$props_name);
		
		// 包含id的处理 不包含ID的处理
		if($hasId) {
			$len = 4;
			$k_p = 2;
			$v_p = 3;
		} else {
			$len = 2;
			$k_p = 0;
			$v_p = 1;
		}
		$props_array = array();
		if($props && sizeof($props) > 0){
			foreach($props as $val) {
				$tmp = explode(':',$val);
				if(sizeof($tmp) == $len){
					if(array_key_exists($tmp[$k_p],$props_array)) {
						$props_array[$tmp[$k_p]] .= ','. $tmp[$v_p]; 	
					} else {
						$props_array[$tmp[$k_p]] = $tmp[$v_p];	
					}
				}
			}
		}
		
		return $props_array;
	}
	
	/**
	 * 属性转换
	 */
	public static function convert_props($props) {
		if(!$props) return "";
		$props = explode(';',$props);
		
		$props_array = array();
		if($props && sizeof($props) > 0){
			foreach($props as $val) {
				$tmp = explode(':',$val);
				if(sizeof($tmp) == 4){
				  $props_array[] = array('pid'=>$tmp[0],'vid'=>$tmp[1],'pname'=>$tmp[2],'vname'=>$tmp[3]);					
				}
			}
		}
		
		return $props_array;
	}
	
	// 淘宝网图片地址转换到本地
	public static function convert_taobaopic_tolocal($picurl){
		return str_replace('http://', 'http://'. $_SERVER['HTTP_HOST'] . URLROOT ,str_replace('.taobaocdn.com','',$picurl));
	}
	
	/**
	* 淘客推推广链接转换
	*/
	public static function convertTaokeUrl2GotoUrl($url,$iid=null,$type=null) {
		$gotoUrl = null;
		if(is_array($url) || $url == null) {
			$gotoUrl = 'to.php?id=' . urlencode($iid) . '&type=' . $type;
		} else {
			$urlArray = parse_url($url);
			
			$gotoUrl = 'to/9/x?';
			if(is_array($urlArray)) {
				$gotoUrl .= 'host=' . self::getDefaultHostId(urlencode($urlArray['host']));
				$gotoUrl .= '&path=' . urlencode($urlArray['path']);
				$gotoUrl .= '&' . $urlArray['query'];
			}
		}
		
		return $gotoUrl;
	}
	
	/**
	* 淘客推推广链接转换
	*/
	public static function convertGotoUrl2TaokeUrl($query) {
		if($query == null) {return null;}
		$urlArray = self::parse_url_query($query);
	
		if(!isset($urlArray['host']) || $urlArray['host'] == null ) return null;
		$taokeUrl = 'http://';
		$taokeUrl .= self::getDefaultHost(urldecode($urlArray['host']));
		$taokeUrl .= urldecode($urlArray['path']) . '?';
		
		$cnt = sizeof($urlArray);
		foreach($urlArray as $k => $v) {
		$cnt--;
		if($k == 'host' || $k == 'path' || $k == '' || $v == '') continue;
		
		$taokeUrl .= $k . '=' . $v . '&';
		}
		
		return $taokeUrl;
	}
	
	
	// Originally written by xellisx
	public static function parse_url_query($var){
	  /**
	   *  Use this function to parse out the query array element from
	   *  the output of parse_url().
	   */
	  $var  = parse_url($var, PHP_URL_QUERY);
	
	  return self::parse_query($var);
	}
	
	// Originally written by xellisx
	public static function parse_query($query){
	  /**
	   *  Use this function to parse out the query array element from
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
	* host默认值
	*/
	public static function getDefaultHost($id) {
		if($id == '1') {
			return 's.click.taobao.com';
		}
		
		return $id;
	}
	
	/**
	* host默认值
	*/
	public static function getDefaultHostId($value) {
		if($value = 's.click.taobao.com') {
			return '1';
		}
		
		return $value;
	}
	
	/**
	 * 从商品属性中获得关键字
	 */
	static public function get_keywords_props($props_name,$hasId=true,$length=6){
		$props = self::convert_props_name($props_name,$hasId);
		
		$keywords = array();
		$i = 1;
		if($props && sizeof($props) > 0){
			foreach($props as $val){
				if(strlen($val) > 3 && !is_numeric($val)){
					$keywords[] = $val;
					if($i++ >= $length) {
						break;
					}
				}
			}
		}
		return $keywords;
	}
}
?>
