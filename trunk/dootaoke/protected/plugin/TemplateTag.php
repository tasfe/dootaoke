<?php
//register functions to be used with your template files
Doo::conf()->TEMPLATE_GLOBAL_TAGS = array('links', 'shorten', 'month', 'formatDate', 
	'debug', 'url', 'url2', 'function_deny', 'isset', 'empty','upper','tofloat', 'sample_with_args','display_slider_ad');
//$template_tags = array('upper', 'tofloat', 'sample_with_args', 'debug', 'url', 'url2', 'function_deny', 'isset', 'empty');

// the 1st argument must be the variable passed in from template, the other args should NOT be variables
function upper($str){
    return strtoupper($str);
}

function tofloat($str){
    return sprintf("%.2f", $str);
}

function links($str){
    Doo::loadHelper('DooTextHelper');
    return DooTextHelper::convertUrl($str);
}

function month($str){
    $months = array('January','February','March','April','May','Jun','July','August','September','October','November','December');
    return $months[intval($str)-1];
}

function formatDate($date, $format='jS F, Y h:i:s A'){
    return date($format, strtotime($date));
}

function shorten($str, $limit=120){
    Doo::loadHelper('DooTextHelper');
    return DooTextHelper::limitWord($str, $limit);
}

function sample_with_args($str, $prefix){
    return $str .' with args: '. $prefix;
}

function debug($var){
    if(!empty($var)){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

//This will be called when a function NOT Registered is used in IF or ElseIF statment
function function_deny($var=null){
   echo '<span style="color:#ff0000;">Function denied in IF or ElseIF statement!</span>';
   exit;
}

//Build URL based on route id
function url($addRootUrl, $id, $param=null){
    Doo::loadHelper('DooUrlBuilder');
    // param pass in as string with format
    // 'param1=>this_is_my_value, param2=>something_here'

    if($param!=null){
    	if(is_string($param)){
	        $param = explode(', ', $param);
	        $param2 = null;
	        foreach($param as $p){
	            $splited = explode('=>', $p);
	            $param2[$splited[0]] = $splited[1];
	        }
        } elseif(is_array($param)) {
    		$param2 = $param;
    	}
        return DooUrlBuilder::url($id, $param2, $addRootUrl);
    }

    return DooUrlBuilder::url($id, null, $addRootUrl);
}


//Build URL based on controller and method name
function url2($addRootUrl, $controller, $method, $param=null){
    Doo::loadHelper('DooUrlBuilder');
    // param pass in as string with format
    // 'param1=>this_is_my_value, param2=>something_here'

    if($param!=null){
    	if(is_string($param)){
	        $param = explode(',', $param);
	        $param2 = null;
	        foreach($param as $p){
	            $splited = explode('=>', $p);
	            $param2[trim($splited[0])] = trim($splited[1]);
	        }    		
    	} elseif(is_array($param)) {
    		$param2 = $param;
    	}

        return DooUrlBuilder::url2($controller, $method, $param2, $addRootUrl);
    }

    return DooUrlBuilder::url2($controller, $method, null, $addRootUrl);
}

/*--------------函数 start---------------------------------------------*/
/**
 * 将键值对数组转化成有序的字符串URL参数
 */
function convert_array_urlparams($paramArr,$keys=null,$containKey=true,$split='-') {
	if(!$paramArr || !is_array($paramArr)) return;
	$strParam = '';

	// 按照key排序
	ksort($paramArr);
	if(!$containKey && $keys) {
		sort($keys);
		foreach($keys as $v) {
			$strParam .= urlencode($paramArr[$v]) . $split;
		}
	} else {
		foreach ($paramArr as $key => $val) {
			if ($key != '' && $val !='' && ($keys == null || in_array($key,$keys))) {
				// url默认格式
				if($split=='=') {
					$strParam .= $key.'='.urlencode($val).'&';
				} else {
					$strParam .= $key .$split .urlencode($val) . $split;
				} 
			}
		}
	}
	
	$lastchar = substr($strParam,strlen($strParam)-1,strlen($strParam)); 
	if($lastchar == '&' || $lastchar == $split) {
		$strParam = substr($strParam,0,strlen($strParam)-1);
	}
	
	return $strParam;
}

/**
 * 通过当前参数，键值对以及指定的参数构建目标url参数
 */
function construct_urlparams($params,$allkeys,$keys=null,$containKey=true,$split='-') {
	if($keys) {
		$tmp = array();
		foreach($keys as $key) {
			$tmp[$key] = $params[$key];
		}
		return convert_array_urlparams($tmp,$allkeys,$containKey,$split);
	} else {
		return convert_array_urlparams($params,$allkeys,$containKey,$split);
	}
}

/**
 * 处理多个商品属性
 */
function construct_props($props,$pid,$vid) {
	if(!$pid || !$vid) return;
	if(!$props) return $pid . ':' . $vid;
	// 是否已经存在
	if(stristr($props,$pid)) return $props;
	
	return order_props($props . ';' . $pid .  ':' . $vid);
}

/**
 * 获得已选择的属性
 */
function selected_params($params_props,$all_props) {
	$selected_params = array();
	if($params_props) {
	$props = split(';',$params_props);
	foreach($props as $p) {
		$pv = split(':',$p);
		if(sizeof($pv) != 2) continue;
		$find = false;// 是否找到对应属性名
		foreach($all_props as $prop) {
			$find = false;
			if($prop['pid'] != $pv[0]) continue;
			if($prop['prop_values']['@attributes']['list']) {
				$propVals = $prop['prop_values']['prop_value'];
				foreach($propVals as $pval) {
					if($pv[1] == $pval['vid']) {
						$selected_params[] = array('pid'=>$prop['pid'],'pname'=>$prop['name'],'vid'=>$pval['vid'],'name'=>$pval['name']);
						$find = true;
						break;
					}
				}
			}
			if($find) break;
			}
			
			// 如果没有找到对应的属性名，使用其他属性代替
			if(!$find) {
				$pname = $name = '其他属性';
				if($pv[0] == '20000'){
					$pname = '品牌';
					$name = '其他品牌';
				}
				$selected_params[] = array('pid'=>$pv[0],'pname'=>$pname,'vid'=>$pv[1],'name'=>$name);
			}
		}
	}
	
	return $selected_params;
}

/**
 * 删除一条属性
 */
 function del_a_prop($props,$pid,$vid) {
 	if(!$props) return;
 	
 	$p_array = array();
 	$ps = split(';',$props);
 	foreach($ps as $p) {
 		$pv = split(':',$p);
 		if(sizeof($pv) != 2) continue;
 		if($pv[0] == $pid && $pv[1] == $vid) continue;
 		$p_array[$pv[0]] = $pv[0] . ':' . $pv[1];
 	}
 	
 	return order_props($p_array);
 }
 
/**
 * 对属性按照pid排序后，组成字符串
 */
function order_props($props) {
	if(!$props) return ;
	
	if(is_string($props)) {
	 	$p_array = array();
	 	$ps = split(';',$props);
	 	foreach($ps as $p) {
	 		$pv = split(':',$p);
	 		if(sizeof($pv) != 2) continue;
	 		$p_array[$pv[0]] = $pv[0] . ':' . $pv[1];
	 	}
	} else {
		$p_array = $props;
	}

 	ksort($p_array);
 	return implode(';',$p_array);
}

/*--------------函数 end---------------------------------------------*/
/**
* 判断访问者是不是搜索引擎
*/
function is_robot(){
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $botchar = "/(bot|crawl|spider|slurp|yahoo|sohu-search|lycos|robozilla)/i";
    if(preg_match($botchar, $ua)) {
        return true;
    }else{
        return false;
    }
}

/**
 * 生成面包屑导航
 */
function display_breadcrumb($bread_array,$before_array=null) {
	if($before_array) {
		if(isset($before_array[0])) {
			foreach($before_array as $b) {
				array_unshift($bread_array,$b);				
			}
		} else {
			array_unshift($bread_array,$before_array);	
		}
	}

	$result = '<div class="cbs"><ul>';
	$result .=	'<li class="first_crumb">您的位置：</li>';
	$breads = sizeof($bread_array);
	for($i = 0;$i < $breads;$i++) {
		$bread = $bread_array[$i];
		
		if($i == $breads-1){
			$result .=	'<li class="last_crumb">'.$bread['name'].'</li>';
		} else {
			$result .=	'<li><a href="'.$bread['url'].'">'.$bread['name'].'</a><s></s></li>';
		}
	}
	$result .= '</ul></div>';
	
	return $result;
}

//从开端截取
function substring($text, $limit=12, $ext='') {
    if ($limit) {
        $val = csubstr($text, 0, $limit);
        return $val[1] ? $val[0].$ext : $val[0];
    } else {
        return $text;
    }
}
//截取
function csubstr($text, $start=0, $limit=12) {
    $charset = CHARSET;
    if (function_exists('mb_substr')) {
        $more = (mb_strlen($text, $charset) > $limit) ? true : false;
        $text = mb_substr($text, 0, $limit, $charset);
        return array($text, $more);
    } elseif (function_exists('iconv_substr')) {
        $more = (iconv_strlen($text) > $limit) ? true : false;
        $text = iconv_substr($text, 0, $limit, $charset);
        return array($text, $more);
    } elseif (strtolower($charset) == "utf-8") {
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
        if(func_num_args() >= 3) {
            if (count($ar[0])>$limit) {
                $more = true;
                $text = join("",array_slice($ar[0],0,$limit))."...";
            } else {
                $more = false;
                $text = join("",array_slice($ar[0],0,$limit));
            }
        } else {
            $more = false;
            $text = join("",array_slice($ar[0],0));
        }
        return array($text, $more);
    } else {
        $fStart = 0;
        $fStart = $fStart * 2; 
        $limit = $limit * 2; 
        $strlen = strlen($text);
        for ( $i = 0; $i < $strlen; $i++ ) { 
            if ($i >= $fStart && $i < ($fStart + $limit ) ) { 
                if (ord(substr($text, $i, 1)) > 129) $tmpstr .= substr($text, $i, 2); 
                else $tmpstr .= substr($text, $i, 1); 
            } 
            if (ord(substr($text, $i, 1)) > 129 ) $i++; 
        } 
        $more = strlen($tmpstr) < $strlen; 
        return array($tmpstr, $more);
    }
}

//获取星星个数
function get_star($point, $scoretype) {
    if($scoretype==5) return round($point*2);
    if($scoretype==10) return round($point);
    if($scoretype==100) return round($point/10);
}

/**
 * 显示旺旺联系
 */
function display_wangwang($nick,$gid=null) {
	$encodeNick = urlencode($nick);
	$amos = "http://amos.im.alisoft.com/msg.aw?v=2&uid=" . $encodeNick ."&site=cntaobao&s=2&charset=utf-8&s=1&gid=" . $gid;
	$img = 'http://amos.im.alisoft.com/online.aw?v=2&uid='. $encodeNick .'&site=cntaobao&s=1&charset=utf-8&s=1'; 
	
	$wwang = '<a href="'. $amos .'" id="amos" target="_blank"><img src="'.$img.'" title="点击这里给我发消息" /></a>';
	
	return $wwang;
}

/**
 * 省份城市
 */
function state_city($state,$city) {
	if(is_array($state) || is_array($city)) {
		return '';
	}elseif($state == $city) {
		return $state;
	} else {
		return $state . ' '. $city;
	}
}

/**
 * 头部导航 列表
 */
function head_nav_block($pps,$type=1) {
	$default = $pps['default'];
	$result_props ='<div>';
	$result_props .='<b class="up_arrow"></b>';
	
	foreach($pps as $k => $pp) {
		if($k=='default') continue;
		$result_props .='<ul>';
		$cnt = 0;
		foreach($pp as $key => $val) {
			$result_props .='<li ' ;
			if($cnt == 0) {
				$result_props .= 'class="oe_heading"';	
			}
			$result_props .= '><a href="' ;
			
			if(!isset($val['params']) || sizeof($val['params']) < 1) {
				$result_props .= '#';
			} else {
				$ct = $default['controller'];
				$id = $default['id'];
				if(isset($val['controller'])) $ct = $val['controller'];
				if(isset($val['id'])) $id = $val['id'];
				if($type==1){
					$result_props .=url2(true,$ct,$id,'p=>'.construct_urlparams($val['params']));	
				} else {
					$result_props .=url2(true,$ct,$id,$val['params']);
				}
			}
			
			
			$result_props .='">' ;
			$result_props .= $val['name'] ;
			$result_props .='</a></li>';
			$cnt++;	
		}
		$result_props .='</ul>';	
	}
	
	$result_props .='</div>';
	
	return $result_props;
}

//显示广告组
function display_slider_ad($slider_ads,$id,$c_container="container",$c_slides="slides",$replace_id=null) {
	if(!isset($slider_ads[$id]) || sizeof($slider_ads[$id]) < 1) return "";
	
	$div_id = $id;
	if($replace_id){
		$div_id = $replace_id;
	}
	
	//if($c_container!="container") $c_container = 'container '.$c_container;
	//if($c_slides!="slides") $c_slides = 'slides '.$c_slides;
	Doo::loadClass('util/TaobaoUtil');
	$ad = '<div id="'.$div_id.'" class="loopedSlider"><div class="'.$c_container.'"><div class="'.$c_slides.'">';
	foreach($slider_ads[$id] as $k => $v) {
		$ad .= '<div><a href="'.TaobaoUtil::convertTaokeUrl2GotoUrl($v['link']).'" target="_blank" rel="external nofollow"><img src="'.Doo::conf()->SUBFOLDER . $v['img'].'" title="'.$v['title'].'" /></a></div>';
	}
	$ad .= '</div></div><div class="page"><ul class="pagination">';
	$cnt = 1;
	foreach($slider_ads[$id] as $k =>  $v) {
		$ad .= '<li><a href="#">'.$cnt++.'</a></li>';
	}
	$ad .= "</ul></div></div>";
	return $ad;
}

/**
 * 
 * 检查缓存时间
 * @param unknown_type $cachetime
 * @param unknown_type $cachePath
 * @param unknown_type $clearPattern
 */
function checkClearTime($cachetime,$cachePath,$clearPattern = '* * * *')
	{
		$CacheParam = explode(" ",$clearPattern);

		if(!$clearPattern || count($CacheParam) !== 4)
		{
			return false;
		}

		if($CacheParam[3] != "*")
		{
			$CacheParam[3] = explode(",",$CacheParam[3]);

			if(!in_array(date('m'),$CacheParam[3]))
			{
				return false;
			}
		}

		if($CacheParam[2] != "*")
		{
			$CacheParam[2] = explode(",",$CacheParam[2]);

			if(!in_array(date('d'),$CacheParam[2]))
			{
				return false;
			}
		}
		if($CacheParam[1] != "*")
		{
			$CacheParam[1] = explode(",",$CacheParam[1]);

			if(!in_array(date('H'),$CacheParam[1]))
			{
				return false;
			}
		}

		if($CacheParam[0] != "*")
		{
			$CacheParam[0] = explode(",",$CacheParam[0]);

			if(!in_array(date('i'),$CacheParam[0]))
			{
				return false;
			}
		}

		$cachetag = $cachePath."autoclear.tag";

         if (file_exists($cachetag)) {
                $filetime = date('U', filemtime($cachetag));

                // 每天更新
			/*	if(date("d") == date("d",$filetime))
				{
					return false;
				 }*/
				 
				 // 根据缓存时间
				 if ($cachetime == 0 || (time() - $filetime) < $cachetime) {
				     return false;
				 }
		}
		file_put_contents($cachetag,date("Y-m-d H:i:s"));

		return true;
	}
	
/**
 * 
 * 清理缓存
 * @param unknown_type $path
 * @param unknown_type $cachetime
 * @param unknown_type $notclearPath
 */
 function autoClearCache($path,$cachetime,$notclearPath=array())
	{
		if(empty($path))
		{
			return false;
		}

		if($cachetime)
		{
			if(!is_dir($path))
			{
				return false;
			}
			
			if($fdir = opendir($path))
			{
				$old_cwd = getcwd();
				chdir($path);
				$path = getcwd().'/';
				while(($file = readdir($fdir)) !== false)
				{
					if(in_array($file,array('.','..')))
					{
						continue;
					}

					if(is_dir($path.$file))
					{
						if(!in_array($file,$notclearPath)) {
							autoClearCache($path.'/'.$file.'/',$cachetime,$notclearPath); 
						}
					}else{
						$filetime = date('U', filemtime($path.$file));
						if ($cachetime != 0 && (time() - $filetime) > $cachetime) {
								@unlink($path.$file);
						}
					}
				}				
				closedir($fdir);
				chdir($old_cwd);
			}
		}

	}
?>