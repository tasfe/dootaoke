<?php
/**
 * 一些共通的处理函数
 */
 require_once "Util.php";
 /**
  * 淘宝客商品检索条件获得
  */
 function getTaobaokeSearchParams($dataArray){
	// 查询关键字
	$keyword = preg_replace("/([\d]+)|([a-z|A-Z]+)|(-)|(_)/e","' \\1 '.' \\2 '",$dataArray["q"]);
	// 商品排序
	$orderBy = getProductsSort($dataArray["sort"]);
	// 卖家信誉
	$endcredit = 20;
	$startcredit = 6;// 默认
	if(isEmptyOrUnset($dataArray["credit"])){
		
	}else if($dataArray["credit"] == 'all') {
		$startcredit = 1;
	}else if($dataArray["credit"] <= 5) {
		$endcredit = 5;
		$startcredit = 1;
	}else if($dataArray["credit"] > 5) {
		$startcredit = $dataArray["credit"];
	}

	$start_credit = getSellerCreditValue($startcredit);
	$end_credit = getSellerCreditValue($endcredit);

	// 当前页码
	$page = getPageNo($dataArray["page"]);

	//API用户参数
	$searchParamArr = array(
		'fields' => 'iid,num_iid,title,nick,pic_url,price,click_url,shop_click_url,seller_credit_score,item_location,volume',
		'keyword' => $keyword,
		'page_size'=>MAX_PRODUCTS_PER_PAGE,
		'sort'=>$orderBy,
		'start_credit'=>$start_credit,
		'end_credit'=>$end_credit,
		'page_no'=> $page,
		'outer_code' => 's'
	);
	
	// 所在地
	$area = getArea($dataArray["area"]);
	//设置地区
	if(!isEmptyOrUnset($area)){
		$searchParamArr['area'] = $area;
	}

	// 价格区间
	$low = getPrice($dataArray['p1']);
	$high = getPrice($dataArray['p2']);
	
	if(!isEmptyOrUnset($low) || !isEmptyOrUnset($high)){
		//设置高价格
		if(isEmptyOrUnset($high)){
			$high = 100000000;
		}

		// 设置低价格
		if(isEmptyOrUnset($low) || $low == 0){
			$low = 0.01;
		}
		
		// 高低价格交换
		if($low > $high){
			$tmp = $high;
			$high = $low;
			$low = $tmp;
		}
		$searchParamArr['start_price'] = $low;
		$searchParamArr['end_price'] = $high;
	}
	
	// 2010/08/01 服务条件
	$services = array('s1'=>'sevendays_return','s2'=>'onemonth_repair','s3'=>'mall_item','s4'=>'cash_ondelivery','s5'=>'vip_card','s6'=>'cash_coupon',);
	foreach($services as $k => $v){
		if($dataArray[$k] && $dataArray[$k] == '1') {
			$searchParamArr[$v] = 1;
		}
	}
	
	// 2010/09/26 分类条件
	if($dataArray["cid"] && is_numeric($dataArray["cid"])) {
		$searchParamArr['cid'] = trim($dataArray["cid"]);
	}
	
	return $searchParamArr;
 }
 
 /**
  * 淘宝客商品检索条件获得
  */
 function getTaobaoSearchParams($dataArray){
	// 查询关键字
	$keyword = preg_replace("/([\d]+)|([a-z|A-Z]+)|(-)|(_)/e","' \\1 '.' \\2 '",$dataArray["q"]);
	// 商品排序
	$orderBy = getProductsOrderBy($dataArray["sort"]);
	// 卖家信誉
	$endcredit = 20;
	$startcredit = 6;// 默认
	if(isEmptyOrUnset($dataArray["credit"])){
		
	}else if($dataArray["credit"] <= 5) {
		$endcredit = 5;
		$startcredit = 1;
	}else if($dataArray["credit"] > 5) {
		$startcredit = $dataArray["credit"];
	}

	// 当前页码
	$page = getPageNo($dataArray["page"]);

	//API用户参数
	$searchParamArr = array(
		'fields' => 'iid,title,nick,num,pic_path,price,volume,score,location',
		'q' => $keyword,
		'page_size'=>MAX_PRODUCTS_PER_PAGE,
		'order_by'=>$orderBy,
		'start_score'=>$startcredit,
		'end_score'=>$endcredit,
		'page_no'=> $page
	);
	
	// 所在地
	$area = getArea($dataArray["area"]);
	//设置地区
	if(!isEmptyOrUnset($area)){
		if(isProcince($area)){
			$searchParamArr['location.state'] = $area;
		}else{
			$searchParamArr['location.city'] = $area;
		}
	}

	// 价格区间
	$low = getPrice($dataArray['p1']);
	$high = getPrice($dataArray['p2']);
	
	if(!isEmptyOrUnset($low) || !isEmptyOrUnset($high)){
		//设置高价格
		if(isEmptyOrUnset($high)){
			$high = 100000000;
		}

		// 设置低价格
		if(isEmptyOrUnset($low) || $low == 0){
			$low = 0.01;
		}
		
		// 高低价格交换
		if($low > $high){
			$tmp = $high;
			$high = $low;
			$low = $tmp;
		}
		$searchParamArr['start_price'] = $low;
		$searchParamArr['end_price'] = $high;
	}
	
	return $searchParamArr;
 }

/**
* 获得存在的品牌数据文件
*/
function getPinpaiFile($cats,$currentCatId) {
	$pinpaiFile = 'data/pinpai/' . $currentCatId . '.txt';
	if(file_exists($pinpaiFile)) {
		return $pinpaiFile;
	}
	
	if($currentCatId == '0' || $currentCatId == null) {
		return null;
	}
	$parentCat = getParentCat($cats,$currentCatId);
	return getPinpaiFile($cats,$parentCat['cid']);
}
 
 /**
  * 获得带参数的URL
  */
 function getUrlWithParams($url,$dataArray){
 	$params = Util::createStrParam($dataArray);
 	return $url . '?' . $params;
 }
 
 /**
 * 淘宝商品排序
 */
 function getProductsOrderBy($orderBy) {
 	$order = null;
 	
 	if(isEmptyOrUnset($orderBy)){
 		$order = "volume:desc";//成交量由高到低
 		return $order;
 	}
 	
 	if($orderBy == "4"){
 		$order = "volume:desc";//成交量由高到低
 	}else if($orderBy == "5"){
 		$order = "volume:asc";//成交量由低到高
 	}else if($orderBy == "10"){
 		$order = "price:desc";//价格由高到低
 	}else if($orderBy == "11"){
 		$order = "price:asc";//价格由低到高
 	}else {
 		$order = "volume:desc";
 	}
 	
 	return $order;
 }
 
 /**
 * 淘宝商品排序ID
 */
 function getProductsOrderById($orderBy) {
 	 $idArray = split(',',NAV_SORT_ID_ARRAY);
 	if(isEmptyOrUnset($orderBy) || !in_array($orderBy,$idArray)){
 		$orderBy = "4";//成交量由高到低
 		return $orderBy;
 	}

 	return $orderBy;
 }
 
 /**
 * 淘宝商品排序数组
 */
 function splitString2Array($target) {
 	$sortArray = split(',',$target);
	$result = array();
	foreach($sortArray as $k => $v) {
		$kv = split('=',$v);
		$result[$kv[0]] = $kv[1];
	}
	
 	 return $result;
 }
 
 /**
 * 按分类查询关键字
 */
 function getListKeyword($sortId,$keyword) {
 	 $sortKeyValue = splitString2Array(KEYWORD_SORT_ARRAY);
 	 if(isset($sortKeyValue[$sortId])) {
 	 	 if($keyword != null) {
 	 	 	 return $sortKeyValue[$sortId] . ' ' . $keyword;
 	 	 }
 	 	return $sortKeyValue[$sortId];
 	 }

 	return $keyword;
 }
 
 /**
  * 转换淘宝客商品排序方式
  */
 function getProductsSort($orderBy){
 	 $order = null;
 	if(isEmptyOrUnset($orderBy)){
 		$order = "commissionNum_desc";
 		return $order;
 	}
 	
	if($orderBy == "1"){
 		$order = "commissionVolume_desc";//总支出佣金从高到底
 	}else if($orderBy == "2"){
 		$order = "commissionVolume_asc";//总支出佣金从低到高
 	}else if($orderBy == "3"){
 		$order = "credit_desc";//信用等级从高到低
 	}else if($orderBy == "4"){
 		$order = "commissionNum_desc";//成交量成高到低
 	}else if($orderBy == "5"){
 		$order = "commissionNum_asc";//成交量从低到高
 	}else if($orderBy == "6"){
 		$order = "delistTime_desc";//商品下架时间从高到底
 	}else if($orderBy == "7"){
 		$order = "delistTime_asc";//商品下架时间从低到高
 	}else if($orderBy == "8"){
 		$order = "commissionRate_desc";//佣金比率从高到底
 	}else if($orderBy == "9"){
 		$order = "commissionRate_asc";//佣金比率从低到高
 	}else if($orderBy == "10"){
 		$order = "price_desc";//价格从高到低
 	}else if($orderBy == "11"){
 		$order = "price_asc";//价格从低到高
 	}else{//91:打折促销 92:免邮费
 		$order = "commissionNum_desc";
 	}
 	return $order;
 }
 
 /**
   * 卖家信誉转换
   */
 function getSellerCreditValue($creditId) {
 	$credit = "1diamond";
 	
 	if(isEmptyOrUnset($creditId)){
 		return $credit;
 	}

 	if($creditId == "1"){
 		$credit = "1heart";//一心
 	}else if($creditId == "2"){
 		$credit = "2heart";//两心
 	}else if($creditId == "3"){
 		$credit = "3heart";//三心
 	}else if($creditId == "4"){
 		$credit = "4heart";//四心
 	}else if($creditId == "5"){
 		$credit = "5heart";//五心
 	}else if($creditId == "6"){
 		$credit = "1diamond";//一钻
 	}else if($creditId == "7"){
 		$credit = "2diamond";//两钻
 	}else if($creditId == "8"){
 		$credit = "3diamond";//三钻
 	}else if($creditId == "9"){
 		$credit = "4diamond";//四钻
 	}else if($creditId == "10"){
 		$credit = "5diamond";//五钻
 	}else if($creditId == "11"){
 		$credit = "1crown";//一冠
 	}else if($creditId == "12"){
 		$credit = "2crown";//二冠
 	}else if($creditId == "13"){
 		$credit = "3crown";//三冠
 	}else if($creditId == "14"){
 		$credit = "4crown";//四冠
 	}else if($creditId == "15"){
 		$credit = "5crown";//五冠
 	}else if($creditId == "16"){
 		$credit = "1goldencrown";//一黄冠
 	}else if($creditId == "17"){
 		$credit = "2goldencrown";//二黄冠
 	}else if($creditId == "18"){
 		$credit = "3goldencrown";//三黄冠
 	}else if($creditId == "19"){
 		$credit = "4goldencrown";//四黄冠
 	}else if($creditId == "20"){
 		$credit = "5goldencrown";//五黄冠
 	}else {
 		$credit = "1diamond";
 	}
 	
 	return $credit;
 }
 
 /**
   * 获得当前页码
   */
 function getPageNo($pageNo,$maxpage=100){
 	if(isEmptyOrUnset($pageNo) || !isNumeric($pageNo)){
 		return "1";
 	}
 	
 	if(intval($pageNo) > $maxpage){
 		return "1";
 	}
 	
 	return $pageNo;
 }
 
 /**
   * 地区处理
   */
 function getArea($area){
 	if(isEmptyOrUnset($area)){
 		return '';
 	}
 	return $area;
 }
 
 /**
  * 获得产品评价的url
  */
 function getProductRateUrl($userId,$iid,$numIid){
 	$url = "http://rate.taobao.com/baby-rate-00000000000000000--userNumId|";
 	$url .= $userId;
 	$url .= "--auctionId|";
 	$url .= $iid;
 	$url .= "--auctionNumId|";
 	$url .= $numIid;
 	$url .= "--showContent|1.htm";
 	
 	return $url;
 }
 
  /**
   * 价格处理
   */
 function getPrice($price){
 	if(isEmptyOrUnset($price) || !isNumeric(trim($price))){
 		return '';
 	}
 	
 	return trim($price);
 }
 
 /**
   * 判断是否是数字类型
   */
 function isNumeric($target) {
 	return is_numeric($target);
 }
 /**
  * 数据库字符串特殊处理
  */
  function getDbInput($target) {
    return addslashes($target);
  }

/**
* 判空
*/
function isEmptyOrUnset($value) {
	if(!isset($value) || empty($value)){
		return true;
	}
	
	return false;
}

/**
  * 根据当前搜索结果的一组价格，计算最大值，最小值和平均值
  */
function caculatePrices($items) {
	if(sizeof($items) < MAX_PRODUCTS_PER_PAGE){
		return null;
	}

	// 最大值
	$maxVal = 0;
	// 最小值
	$minVal = 0;
	// 和值
	$sumVal = 0;
	
	$num = 0;
	foreach ($items as $key => $val) { 
		$price = $val['price'];
		$sumVal += $price;
		
		// 初始化最大/小值
		if($num == 0) {
			$maxVal = $price;
			$minVal = $price;
		}
		
		// 获得最大/小值
		if($price > $maxVal){
			$maxVal = $price;
		}elseif($price < $minVal){
			$minVal = $price;
		}
		
		$num++;
		
	}
	
	// 平均值
	$averVal = round($sumVal/$num);
	
	$priceArray = array('max' => caculateRoundNumber($maxVal),
						'min' => caculateRoundNumber($minVal), 
						'aver' => caculateRoundNumber($averVal));
	
	return $priceArray;
}

/* 数据取整
*/
function caculateRoundNumber($val) {
	if($val > 10 && $val < 100) {
		$val = round($val/10) * 10;
	}else if ($val > 100 && $val < 1000) {
		$val = round($val/100) * 100;
	}else if ($val > 1000 && $val < 10000) {
		$val = round($val/1000) * 1000;
	} else if ($val > 10000) {
		$val = 10000;
	}
	
	return $val;
}

/*
 根据最大值，最小值，平均值计算参考价格
*/
function constructPricesString($items,$paramArr,$selected = null) {
	$priceArray = caculatePrices($items);
	
	$url = 'search.php?';
	$p1 = $paramArr['p1'];
	$p2 = $paramArr['p2'];
	$url .= Util::createStrParam(Util::deleteKeyValueOfArray($paramArr,array('p1','p2','page')));
	
	if(isEmptyOrUnset($p1) && isEmptyOrUnset($p2) && $priceArray == null){
		return null;
	}
	
	$result = '<UL  class="price-ref">';
	$result .= '<LI><A href="'. $url .'">全部价格</A></LI>';
	if ($priceArray != null) {
		$averVal = $priceArray['aver'];

		if ($averVal <= 1) {
			$result .= '<LI><A href="'. $url . 'p1=0&p2=0.5">低于 0.5元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=0.5&p2=1">0.5元 - 1元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=1">高于 1元</A> </LI>';
		}else if ($averVal < 10) {
			$result .= '<LI><A href="'. $url . 'p1=0&p2=3">低于 3元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=3&p2=6">3元 - 6元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=6&p2=10">6元 - 10元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=10">高于 10元</A> </LI>';
		}else if ($averVal >= 10000) {
			$result .= '<LI><A href="'. $url . 'p1=0&p2=1000">低于 1000元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=1000&p2=3000">1000元 - 3000元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=3000&p2=5000">3000元 - 5000元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=5000&p2=10000">5000元 - 10000元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1=10000">高于 10000元</A> </LI>';
		}else {
			$v1 = $averVal * 0.3;
			$v2 = $averVal * 0.6;
			$v3 = $averVal;
			$v4 = $averVal * 1.5;
			$v5 = $averVal * 2;
			$result .= '<LI><A href="'. $url . 'p1=0&p2='.$v1.'">低于 '.$v1.'元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1='.$v1.'&p2='.$v2.'">'.$v1.'元 - '.$v2.'元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1='.$v2.'&p2='.$v3.'">'.$v2.'元 - '.$v3.'元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1='.$v3.'&p2='.$v4.'">'.$v3.'元 - '.$v4.'元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1='.$v4.'&p2='.$v5.'">'.$v4.'元 - '.$v5.'元</A></LI>';
			$result .= '<LI><A href="'. $url . 'p1='.$v5.'">高于 '.$v5.'元</A> </LI>';
		}
	}
	$result .= '<form method="GET" action="search.php">';
	foreach (Util::deleteKeyValueOfArray($paramArr,array('p1','p2','page')) as $k => $v) {
		$result .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
	}
	$result .= '<input type="text" size="1" name="p1" value="' . $p1 .'" onkeyup="filterInt(this);">-<input type="text" size="1" name="p2" value="' . $p2 .'"  onkeyup="filterInt(this);">';
	$result .= '<input type="submit" value="搜">';
	$result .= '</form>';
	$result .= '</UL>';
	
	return $result;
}

/*
* 信誉列表
*/
function constructCreditsString($paramArr) {
	$url = 'search.php?';
	$selected = $paramArr['credit'];
	$url .= Util::createStrParam(Util::deleteKeyValueOfArray($paramArr,array('credit','page')));
	
	//$defaulCredit = array(6,8,11,13,16,18);
	
	$result = '<UL class="credit-ref">';
	if($selected == 'all') {
		$result .= '<LI>全部信誉</LI>';
	} else {
		$result .= '<LI><A href="'. $url .'credit=all">全部信誉</A></LI>';
	}
	
	if ($selected == null){
		$result .= '<LI><A href="'. $url .'credit=5" >一钻以下</A></LI>';
		$result .= '<LI>一钻以上</LI>';
		$result .= '<LI><A href="'. $url .'credit=8" >三钻以上</A></LI>';
		$result .= '<LI><A href="'. $url .'credit=11" >一冠以上</A></LI>';
		$result .= '<LI><A href="'. $url .'credit=13" >三冠以上</A></LI>';
		$result .= '<LI><A href="'. $url .'credit=16" >一黄冠以上</A></LI>';
		$result .= '<LI><A href="'. $url .'credit=18" >三皇冠以上</A></LI>';
	}else {
		if($selected != 'all' && $selected < 6) {
			$result .= '<LI><font class="selected">一钻以下</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=5" >一钻以下</A></LI>';
		}
		
		if($selected >= 6 && $selected < 8) {
			$result .= '<LI><font class="selected">一钻以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=6" >一钻以上</A></LI>';
		}
		
		if($selected >= 8 && $selected < 11) {
			$result .= '<LI><font class="selected">三钻以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=8" >三钻以上</A></LI>';
		}
		
		if($selected >= 11 && $selected < 13) {
			$result .= '<LI><font class="selected">一冠以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=11" >一冠以上</A></LI>';
		}
		
		if($selected >= 13 && $selected < 16) {
			$result .= '<LI><font class="selected">三冠以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=13" >三冠以上</A></LI>';
		}
		
		if($selected >= 16 && $selected < 18) {
			$result .= '<LI><font class="selected">一黄冠以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=16" >一黄冠以上</A></LI>';
		}
		
		if($selected >= 18) {
			$result .= '<LI><font class="selected">三黄冠以上</font></LI>';
		}else {
			$result .= '<LI><A href="'. $url .'credit=18" >三黄冠以上</A></LI>';
		}
	}

	$result .= '</UL>';
 		 
    return $result;
}

/**
* 构建卖家所在地的列表
*/
function constructAreaString($paramArr) {
   $default_area = array('北京','上海','杭州','广州','深圳','南京','武汉','重庆','长沙','天津');//，哈尔滨，天津，成都，重庆，浙江，广东，江苏，湖南，香港，澳门，台湾
   if($_COOKIE['area'] && !in_array($_COOKIE['area'],$default_area)) {
   		array_unshift($default_area,$_COOKIE['area']);
   }

	$url = 'search.php?';
	$selected = $paramArr['area'];
	$url .= Util::createStrParam(Util::deleteKeyValueOfArray($paramArr,array('area','page')));
	
	$result = '<UL class="area-ref">';
	if(isEmptyOrUnset($selected)) {
		$result .= '<LI>全部地区</LI>';
	} else{
		$result .= '<LI><A href="'. $url .'" title="全部地区">全部地区</A></LI>';
	}
	
	if(!isEmptyOrUnset($selected) && !in_array($selected,$default_area)) {
		array_unshift($default_area,$selected);
	}
	
	foreach ($default_area as $k => $v){
		if($selected == $v) {
			$result .= '<LI><font class="selected">'.$v.'</font></LI>';
		} else {
			$result .= '<LI><A href="'.$url. 'area=' .urlencode($v).'" title="'.$v.'">'.$v.'</A></LI>';
		}
	}
	$result .= '<form method="GET" action="search.php">';
	foreach (Util::deleteKeyValueOfArray($paramArr,array('area','page')) as $k => $v) {
		$result .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
	}
	$result .= '<input type="text" size="4" name="area">';
	$result .= '<input type="submit" value="搜">';
	$result .= '</form>';
    $result .= '</UL>';

	return $result;
}

/*
* 按价格排序
*/
function getOrderByString($paramArr) {
	$selected = $paramArr['sort'];
	
	$result .= '<form method="GET" action="search.php">';
	foreach (Util::deleteKeyValueOfArray($paramArr,array('sort','page')) as $k => $v) {
		$result .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
	}
	$select = array('','','','');
	
	if ($selected == 4){
		$select[0] = 'selected';
	}else if ($selected == 5){
		$select[1] = 'selected';
	}else if ($selected == 10){
		$select[2] = 'selected';
	}else if ($selected == 11){
		$select[3] = 'selected';
	}else {
		$select[0] = 'selected';
	}
	
	$result .= '排序：<select onchange="this.form.submit();" name="sort">';
	$result .= '<option value="4"  '.$select[0].'>按成交量从高到低</option>';
	$result .= '<option value="5"  '.$select[1].'>按成交量从低到高</option>';
	$result .= '<option value="10"  '.$select[2].'>按价格从高到低</option>';
	$result .= '<option value="11"  '.$select[3].'>按价格从低到高</option>';
	$result .= '</select>';
	$result .= '</form>';
	
	return $result;
}

/**
* 搜索条件
*/
function searchContidtions($paramArray) {
	$p1 = $paramArray['p1'];
	$p2 = $paramArray['p2'];
	$area = getArea($paramArray['area']);
	$credit = $paramArray['credit'];
	
	$result = '(条件：';
	if(!isEmptyOrUnset($p2)) {
		$result .= '》';
		if($p1 == 0 || isEmptyOrUnset($p1)) {
			$result .= '低于'. $p2 .'元 ';
		}else {
			$result .= $p1.'元 - '.$p2 . '元 ';
		}
	}else if (!isEmptyOrUnset($p1) && isEmptyOrUnset($p2)) {
		$result .= '高于'. $p1 .'元 ';
	}
	
	if(!isEmptyOrUnset($credit) && $credit != 'all') {
		$result .= '》';
		if($credit < 6) {
			$result .= '一钻以下';
		}
		
		if($credit >= 6 && $credit < 8) {
			$result .= '一钻以上';
		}
		
		if($credit >= 8 && $credit < 11) {
			$result .= '三钻以上';
		}
		
		if($credit >= 11 && $credit < 13) {
			$result .= '一冠以上';
		}
		
		if($credit >= 13 && $credit < 16) {
			$result .= '三冠以上';
		}
		
		if($credit >= 16 && $credit < 18) {
			$result .= '一黄冠以上';
		}
		
		if($credit >= 18) {
			$result .= '三黄冠以上';
		}
	} else if ($credit != 'all'){
		$result .= '一钻以上';
	}
	
	if(!isEmptyOrUnset($area)) {
		$result .= '》' . $area;
	}
	
		$result .= ')';
		
	return $result;
}

/**
* 翻页效果
*/
function getPageString($paramsArray,$totalNum,$url = 'search.php',$max_display_page = 10) {
	if (MAX_PRODUCTS_PER_PAGE >= $totalNum) {
		return null;
	}
	
	$url_params = Util::deleteKeyValueOfArray($paramsArray,array('page'));
		
	$totalPage = $totalNum / MAX_PRODUCTS_PER_PAGE;
	if($totalNum % MAX_PRODUCTS_PER_PAGE > 0){
		$totalPage++;
	}
	
	$currentPage = getPageNo($paramsArray['page']);
	if($currentPage > $totalPage) {
		$currentPage = 1;
	}
	
	$result = '<div class="pagination">';
	if ($currentPage != 1) {
		$url_params['page'] = $currentPage - 1;
		$href_url = SeoUtil::href_link($url,$url_params);
		$result .= '<a href="'.$href_url.'" class="pre-page">上一页</a>';
	}
	
	$dist = $max_display_page * 0.6;
	$step = $max_display_page * 0.4;
	$step1 = $step - 1;
	if($currentPage > $dist){
		$url_params['page'] = 1;
		$href_url = SeoUtil::href_link($url,$url_params);
		$result .= '<a href="'.$href_url.'">1</a>';
		$result .= '<span class="omitted-pages">...</span>';
		$maxPage = $currentPage + $step;
		if($maxPage > $totalPage) {
			$maxPage = $totalPage;
		}
		
		for($i = ($currentPage - $step1);$i <= $maxPage;$i++){
			if($i == $currentPage){
				$result .= '<span class="current-page">'. $i .'</span>';
			}else {
				$url_params['page'] = $i;
				$href_url = SeoUtil::href_link($url,$url_params);
				$result .= '<a href="'.$href_url.'">'.$i.'</a>';
			}
		}
	}else {
		$maxPage = $max_display_page;
		if($maxPage > $totalPage) {
			$maxPage = $totalPage;
		}
		
		for ($i = 1;$i <= $maxPage;$i++){
			if($i == $currentPage){
				$result .= '<span class="current-page">'. $i .'</span>';
			}else {
				$url_params['page'] = $i;
				$href_url = SeoUtil::href_link($url,$url_params);
				$result .= '<a href="'.$href_url.'">'.$i.'</a>';
			}
		}
	}
	
	if ($totalPage > $currentPage + $step ){
		$url_params['page'] = $currentPage + 1;
		$href_url = SeoUtil::href_link($url,$url_params);
		$result .= '<a href="'.$href_url.'" class="next-page">下一页</a>';
	}
	$result .= '</div>';
	return $result;
}

// Returns true if $string is valid UTF-8 and false otherwise.
function is_utf8($word){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true){
		return true;
	}else{
		return false;
	}
} // function is_utf8




/**
* 对查询关键字转码
*/
function getValidKeywords($target) {
	if(is_utf8($target)) {
		return $target;
	}else {
		return iconv_gb2312_utf8($target);
	}
}

/**
* 对数组数据进行正确的转码
*/

function iconvArray($paramArray){
	$result = array();
	foreach ($paramArray as $k => $v) {
		$result[$k] = getValidKeywords($v);
	}
	
	return $result;
}

/**
* 是否是省份
*/
function isProcince($target) {
	$provinces = split(',',CHINA_PROVINCES);
	
	return in_array($target,$provinces);
}

/**
* 获得导航栏目
*/
function getNavString($cats,$catId,$pName=null,$page='list.php') {
	$split_string = '&nbsp;&nbsp;>&nbsp;&nbsp;';
	$result = null;
	if(sizeof($cats) == 1 && $cats[0]['cid'] == 0) {
		return $split_string . $cats[0]['name'];
	}
	
	if(isEmptyOrUnset($catId)) {
		$catId = '0';
	}
	
	$parent_cid  = 'xxxxx';
	$cid = 'xxxxxx';
	$flag = true;
	while($cid != '0' && !isEmptyOrUnset($cid) && sizeof($cats) > 1) {
		foreach($cats as $k => $v) {
			if($v['cid'] == $catId && $flag) {
				if($pName == null){
					$result = $split_string . $v['name'];
				} else {
					$url_params['cid'] = $v['cid'];
					$href_url = SeoUtil::href_link($page,$url_params);
					$result = $split_string . '<a href="'. $href_url .'">' . $v['name'] . '</a>';
				}
				$flag = false;
				$parent_cid = $v['parent_cid'];
				$cid = $v['cid'];
				break;
			}else if($v['cid'] == $parent_cid){
				$url_params['cid'] = $v['cid'];
				$href_url = SeoUtil::href_link($page,$url_params);
				$result = $split_string . '<a href="' . $href_url . '">' . $v['name'] . '</a>' . $result;
				$parent_cid = $v['parent_cid'];
				$cid = $v['cid'];
				break;
			} else {
				$cid = '0';
			}
		}
	}
	
	if ($pName != null) {
		$result .=  $split_string . $pName;
	}
	
	return $result;
}

/**
* 获得导航路径数组
*/
function getNavArray($cat,$taobaoCats) {
	$result = getParentCatsByCid($cat['cid']);//null;//getNavArrayOfFileByCatId($cat['cid']);

	if($result == null || sizeof($result) < 1){
		$result[] = array('cid' => '0','parent_cid' => '-1','is_parent' => 'true','name' => '所有分类','sort_order' => '0');

		if($cat['cid'] != '0'){
			$parent_cid = $cat['parent_cid'];
			$result[] = $cat;
			while($parent_cid != 0) {
				$tmpCat = $taobaoCats->getCats($parent_cid);
				$result[] = $tmpCat;
				$parent_cid = $tmpCat['parent_cid'];
			}
		}
	}
	return $result;
}

/**
* 通过分类ID获得导航路径数组
*/
function getNavArrayByCatId($catId,$taobaoCats) {
	$result = getParentCatsByCid($catId);//null;//getNavArrayOfFileByCatId($catId);
	if($result == null || sizeof($result) < 1){
		$tmpCat = $taobaoCats->getCats($catId);
		
		return getNavArray($tmpCat,$taobaoCats);
	}
	
	return $result;
}

/**
* 通过读取分类文件获得导航路径数组
*/
function getNavArrayOfFileByCatId($catId) {
	$cats = readCatsFile2CatsArray();

	if($cats == null || sizeof($cats) < 1){
		return null;
	}
	$cats = $cats['item_cats']['item_cat'];
	$result[] = array('cid' => '0','parent_cid' => '-1','is_parent' => 'true','name' => '所有分类','sort_order' => '0');
	if($catId != '0'){
		//当前分类
		$cCat = getCurrentCat($cats,$catId);
		$result[] = $cCat;
		$parent_cid = $cCat['parent_cid'];
		$c = 0;
		while($parent_cid != '0' && isset($parent_cid) && $parent_cid != null) {
			$tmpCat = getParentCat($cats,$catId);
			$result[] = $tmpCat;
			$parent_cid = $tmpCat['parent_cid'];
			if($c++ > 300) break;
		}
	}
	
	return $result;
}

/**
* 获得父类
*/
function getParentCat($cats,$catId) {
	$cCat = getCurrentCat($cats,$catId);
	if(isset($cCat) && $cCat != null) {
		$parentId = $cCat['parent_cid'];
	}

	return getCurrentCat($cats,$parentId);
}

/**
* 获得当前分类
*/
function getCurrentCat($cats,$catId) {
	if(!isset($catId) || $catId == null) {
		return null;
	}
	foreach($cats as $k => $v) {
		if($v['cid'] == $catId) {
			return $v;
		}
	}
	
	return null;
}

/**
* 获得分类数据
*/
function getCatByCatId($catId,$taobaoCats) {
	$cat = selectCatByCid($catId);
	
	if($cat == null){
		return $taobaoCats->getCat($catId);
	}
	
	return $cat;
}

/**
* 获得最上层父类（0层除外）
*/
function getTopestParent($cats) {
	if(sizeof($cats) == 1 && $cats[0]['cid'] == 0) {
		return null;
	}
	
	for($i = 0;$i < sizeof($cats);$i++) {
		if($cats[$i]['parent_cid'] == '0') {
			return $cats[$i];
		}
	}
	
	return null;
}

/*
*获得所有分类的名字
*/
function getAllCatsNames($cats) {
	if(sizeof($cats) == 1 && $cats[0]['cid'] == 0) {
		return null;
	}
	
	$result = '';
	for($i = 0;$i < sizeof($cats);$i++) {
		if($cats[$i]['cid'] != '0') {
			$result .= $cats[$i]['name'] . ',';
		}
	}
	
	return $result;
}

/*
*获得父类的名字
*/
function getParentCatName($cats) {
	if(sizeof($cats) == 1 && $cats[0]['cid'] == 0) {
		return null;
	}
	
	$result = '';
	for($i = 0;$i < sizeof($cats);$i++) {
		if($cats[$i]['cid'] != '0') {
			$result = $cats[$i]['name'];
			break;
		}
	}
	
	return $result;
}

/**
* 根据积分计算等级
*/
function getSellerScore($credit) {
	$score = 1;
	if($credit >= 4 && $credit <= 10) {
		$score = 1;
	}else if($credit >= 11 && $credit <= 40) {
		$score = 2;
	}else if($credit >= 41 && $credit <= 90) {
		$score = 3;
	}else if($credit >= 91 && $credit <= 150) {
		$score = 4;
	}else if($credit >= 51 && $credit <= 250) {
		$score = 5;
	}else if($credit >= 251 && $credit <= 500) {
		$score = 6;
	}else if($credit >= 501 && $credit <= 1000) {
		$score = 7;
	}else if($credit >= 1001 && $credit <= 2000) {
		$score = 8;
	}else if($credit >= 2001 && $credit <= 5000) {
		$score = 9;
	}else if($credit >= 5001 && $credit <= 10000) {
		$score = 10;
	}else if($credit >= 10001 && $credit <= 20000) {
		$score = 11;
	}else if($credit >= 20001 && $credit <= 50000) {
		$score = 12;
	}else if($credit >= 50001 && $credit <= 100000) {
		$score = 13;
	}else if($credit >= 100001 && $credit <= 200000) {
		$score = 14;
	}else if($credit >= 200001 && $credit <= 500000) {
		$score = 15;
	}else if($credit >= 500001 && $credit <= 1000000) {
		$score = 16;
	}else if($credit >= 1000001 && $credit <= 2000000) {
		$score = 17;
	}else if($credit >= 2000001 && $credit <= 5000000) {
		$score = 18;
	}else if($credit >= 5000001 && $credit <= 10000000) {
		$score = 19;
	}else if($credit >= 10000001) {
		$score = 20;
	}
	return $score;
}

/**
* 淘宝风云榜关键字
*/
function getTaobaoFengyunWords() {
	$fileHandler = fopen(TAOBAO_FENGYUN_URL,"r");
	
	$line = null;
	if(!$fileHandler){
		return null;
	}else{
		while(!feof($fileHandler)){
			$line .= fgets($fileHandler, 1024 * 5);
		}
	}
	fclose($fileHandler);
	
	$pat = '/<a(.*?)href="http:\/\/ju.atpanel.com(.*?)"(.*?)>(.*?)<\/a>/i';
	preg_match_all($pat, $line, $m);

	$result = array();
	foreach($m[4] as $k => $v) {
		if(!strstr($v,'img') && iconv_gb2312_utf8($v) != '我要出价') {
			$result[] = iconv_gb2312_utf8($v);
		}
	}
	
	return $result;
}

/**
* 从cookie中取出当前的城市
*/
function getCurrentArea() {
	if(isEmptyOrUnset($_COOKIE['area'])) {
		return "所有地区";
	}
	
	return $_COOKIE['area'];
}

/**
*图片数组转换
*/
function convertTopImages($images) {
	if(is_robot()) return "[]";
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
* 属性值别名字符串
*/
function getPropertyAliasString($alias) {
	if($alias == null || $alias == "") return null;
	
	$props = split(';',$alias);
	$names = '';
	for($i = 0;$i < sizeof($props);$i++) {
		if($props[$i] == "") continue;
		
		$values = split(':',$props[$i]);
		if(isset($values[2]) && $values[2] != "") {
			$names .= $values[2] . ';';
		}
	}

	if(preg_match('/(色|银|红|橙|黄|绿|青|蓝|紫)/i',$names)) {
		$names = '此商品有如下颜色可以选择:' . $names;
	} else if(stristr($names,'套餐')){
		$names = '此商品有如下套餐可以选择:' . $names;
	} else {
		$names = '';
	}
	
	return $names;
}

/**
* 属性值别名
*/
function getPropertyAlias($alias) {
	if($alias == null || $alias == "") return null;
	
	$props = split(';',$alias);
	$names = array();
	for($i = 0;$i < sizeof($props);$i++) {
		if($props[$i] == "") continue;
		
		$values = $split(':',$props[$i]);
		if(isset($values[2]) && $values[2] != "") {
			$names[] = $values[2];
		}
	}
	
	return $names;
}

/**
* 获得奢侈品查询的条件数组
*/
function getSearchLuxuryParams($id,$defined_params,$params = null) {
	if(!isset($defined_params[$id])) return null;
	
	$result = $defined_params[$id];
	$result = Util::deleteKeyValueOfArray($result,array('name','alias'));
	$def = $defined_params['default'];
	foreach($def as $name => $val) {
		if(!isset($result[$name]) || $result[$name] == ''){
			$result[$name] = $val;
		}
	}
	
	if($params != null && is_array($params)) {
		foreach($params as $name => $val) {
			if($val && $val !== '') {
				$result[$name] = $val;
			}
		}
	}
	
	return $result;
}

/**
* 获得分类频道条件数组
*/
function getChannelParams($defined_params,$name='alias') {
	foreach($defined_params as $k => $v) {
		if($k !== 'default' && isset($v[$name])) {
			$result[] = $v[$name];
		}
	}
	
	return $result;
}

// 取得IIDs
function getItemsNumIids($items) {
	if(sizeof($items) < 1) return null;
	
	$result = null;
	if(!is_array($items['taobaoke_items']['taobaoke_item'][0])) {
		$result[] = $items['taobaoke_items']['taobaoke_item'][0];
	}else {
		for($i = 0;$i < sizeof($items['taobaoke_items']['taobaoke_item']);$i++) {
			$result[] = $items['taobaoke_items']['taobaoke_item'][$i]['num_iid'];
		}
	}
	
	return $result;
}

// 奢侈品排序
function getLuxuryOrderString($currentOrder,$params,$url='luxury-list.php') {
	$order = array('3'=>'信誉高低','4'=>'成交高低','10'=>'价格高低','11'=>'价格低高','6'=>'最新上架',);
	$result = '<div class="viewTab"><ul class="tabs">';
	
	foreach($order as $k => $v) {
		if($currentOrder == $k) {
			$result .= '<li class="current">' . $v . '</li>';
		}else {
			$params['sort'] = $k;
			$params['page'] = 1;
			$href_url = SeoUtil::href_link($url,$params);
			$result .= '<li><a href="' . $href_url . '">' . $v . '</a></li>';
		}
	}

	$result .= '</ul><div class="clearing"></div></div>';
	return $result;
}

// 获得价格
function getSearchPrice($dataArray) {
	// 价格区间
	$low = getPrice($dataArray['p1']);
	$high = getPrice($dataArray['p2']);
	
	if(!isEmptyOrUnset($low) || !isEmptyOrUnset($high)){
		//设置高价格
		if(isEmptyOrUnset($high)){
			$high = 100000000;
		}

		// 设置低价格
		if(isEmptyOrUnset($low) || $low == 0){
			$low = 0.01;
		}
		
		// 高低价格交换
		if($low > $high){
			$tmp = $high;
			$high = $low;
			$low = $tmp;
		}
		$searchParamArr['p1'] = $low;
		$searchParamArr['p2'] = $high;
	}
	
	return $searchParamArr;
}

// 获取信誉值
function getCredit($c) {
	$c = trim($c);
	if(isEmptyOrUnset($c) || !isNumeric($c) || $c < 1 || $c > 20) {
		return null;
	}
	
	return $c;
}

// 获得信誉
function getSearchCredit($dataArray) {
	// 价格区间
	$c1 = getCredit($dataArray['c1']);
	$c2 = getCredit($dataArray['c2']);
	
	if(!isEmptyOrUnset($c1) || !isEmptyOrUnset($c2)){
		//设置高价格
		if(isEmptyOrUnset($c2)){
			$c2 = 20;
		}

		// 设置低价格
		if(isEmptyOrUnset($c1) || $c1 == ""){
			$c1 = 1;
		}
		
		// 高低价格交换
		if($c1 > $c2){
			$tmp = $c2;
			$c2 = $c1;
			$c1 = $tmp;
		}
		$searchParamArr['c1'] = getSellerCreditValue($c1);
		$searchParamArr['c2'] = getSellerCreditValue($c2);
	}
	
	return $searchParamArr;
}

/**
* 检查是否包含特殊词语
*/
function contains_s_words($q) {
	global $s_words;
	
	return contains_words($q,$s_words);
}

/**
* 检查是否包含特殊词语
*/
function contains_words($q,$words,$both=false) {
	if(in_array($q,$words)) {
		return true;
	}
	
	foreach($words as $word) {
		if(stristr($q,$word)) return true;
		if($both && stristr($word,$q)) return true;
	}
	
	return false;
}

/** 
* 两个字符串数组 去掉和主数组包含相同的字符的字符
*/
function filter_by_mainarray($mainArray,$sArray) {
	$result = array();
	
	if(!is_array($sArray) || !is_array($mainArray) || sizeof($mainArray) < 1 || sizeof($sArray) < 1) return $result;
	foreach($sArray as $val) {
		if(!contains_words($val,$mainArray,true)) {
			$result[] = $val;
		}
	}
	return $result;
}


// 产品名称处理，获得关键字
function get_keywords_by_name($name){
	global $ex_keywords,$split_chars,$split_chars_after,$split_chars_before;
	$split_str = '/(' . implode('|',$split_chars) . ')/';
	$str = preg_replace($split_str, " ", $name);

	$keys = split(" ",$str);

	$result = array();
	foreach($keys as $v) {
		$tmp = trim($v);
		$tmp2 = iconv_utf8_gb2312($tmp);
		if(!contains_words($tmp,$ex_keywords) && strlen($tmp2) > 3 && strlen($tmp2) <= 22 && !is_numeric($tmp)){
			$result[] = $tmp;
		}
	}
	
	return array_unique($result);
}

//显示广告组
function display_nivo_slider_ad($slider_ads,$id) {
	if(!isset($slider_ads[$id]) || sizeof($slider_ads[$id]) < 1) return "";
	$ad = '<div id="'.$id.'" class="nivoSlider">';
	foreach($slider_ads[$id] as $k => $v) {
		$ad .= '<a href="'.StringUtil::convertTaokeUrl2GotoUrl($v['link']).'" target="_blank"><img src="'.$v['img'].'" title="#'.$id.'_hc_'.$k.'" /></a>';
	}
	$ad .= '</div>';
	foreach($slider_ads[$id] as $k =>  $v) {
		$ad .= '<div id="'.$id.'_hc_'.$k.'" class="nivo-html-caption">';
		$ad .= $v['caption'];
		$ad .= '</div>';
	}
	
	return $ad;
}

//显示广告组
function display_looped_slider($slider_ads,$id,$replace_id=null) {
	if(!isset($slider_ads[$id]) || sizeof($slider_ads[$id]) < 1) return "";
	
	$div_id = $id;
	if($replace_id){
		$div_id = $replace_id;
	}
		
	$ad = '<div id="'.$div_id.'" class="loopedSlider"><div class="container"><div class="slides">';
	foreach($slider_ads[$id] as $k => $v) {
		$ad .= '<div><a href="'.StringUtil::convertTaokeUrl2GotoUrl($v['link']).'" target="_blank"><img src="'.$v['img'].'" title="'.$v['title'].'" /></a></div>';
	}
	$ad .= '</div></div><div class="page"><ul class="pagination">';
	$cnt = 1;
	foreach($slider_ads[$id] as $k =>  $v) {
		$ad .= '<li><a href="#">'.$cnt++.'</a></li>';
	}
	$ad .= "</ul></div></div>";
	return $ad;
}

// 从数据库数组中获得最近的标题，关键字描叙
function get_tkd_cats($cats,$cid){
	$title = $keywords = $description ='';
	
	if(sizeof($cats) == 1 && $cats[0]['cid'] == 0) {
		return null;
	}
	
	$pid = $cid;
	while($pid){
		foreach($cats as $k => $v) {
			if($v['cid']==$pid){
				if(!$title) $title = $v['title'];
				if(!$keywords) $keywords = $v['keywords'];
				if(!$description) $description = $v['description'];
				$pid = $v['parent_cid'];
				break;
			}
		}
	}
	
	$result = array();
	$result['title']=$title;
	$result['keywords']=$keywords;
	$result['description']=$description;
	return $result;
}

/**
* 获得相关画报的分类ID
*/
function get_related_huabao_cat($id) {
	$cats_table = require_once('huabao_data.php');
	foreach($cats_table as $h => $v) {
		if(in_array($id,$v['cats'])) return $cats_table[$h];
	}
	
	$cid = rand(1,9);
	return $cats_table[$cid];
}
?>