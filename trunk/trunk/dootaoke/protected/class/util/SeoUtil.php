<?php
require_once dirname(__FILE__) . "/../Util.php";
require_once dirname(__FILE__) . "/../data/seo_data.php";

/**
*  seo优化
*/
class SeoUtil {
/**
* 转换url
*/
static public function href_link($page,$params=null,$extention = '.html'){
	global $seo_pages;
	$hrefLink = '';
	
	if(SEO_HREF_LINK) {
		if(in_array($page,$seo_pages)) {
			$static_url = self::create_static_url($page,$params,$extention);
			$params = self::delAnchors($page,$params);
			$hrefLink = self::make_href_link($static_url,$params);
		} else {
			$hrefLink = self::make_href_link($page,$params);
		}
	} else {
		$hrefLink = self::make_href_link($page,$params);
	}
	
	if($hrefLink[strlen($hrefLink) - 1] == '&') {
		$hrefLink = substr($hrefLink,0,(strlen($hrefLink) - 1));
	}
	
	return $hrefLink;
}

/**
* 创建带参数的url
*/
static public function make_href_link($url,$params=null) {
	$ps = Util::createStrParam($params);
	$hrefLink = $url;
	if($ps != null && $ps != '') {
		$hrefLink  .=  '?' . $ps;
	}
	
	return $hrefLink;
}

/**
* 创建静态url
*/
static public function create_static_url($page,$params=null,$extention = '.html') {
	global $seo_anchors;
	if ($params == null || $params == '' || !is_array($params) || (sizeof($params) < 1)) {
		return str_replace('.php','.html',$page);
	}
	
	$static_url = '';
	foreach($seo_anchors[$page] as $k => $v) {
		if(isset($params[$k]) && $params[$k] != null && $params[$k] != '') {
			$static_url .= $v . $params[$k];
		}
	}
	
	return $static_url . $extention;
}

/**
* 删除所有的$seo_anchors中的键值
*/
static public function delAnchors($page,$params) {
	global $seo_anchors;
	$keys =  array();
	foreach($seo_anchors[$page] as $k => $v) {
		$keys[] = $k;
	}
	return Util::deleteKeyValueOfArray($params,$keys);
}
}
?>