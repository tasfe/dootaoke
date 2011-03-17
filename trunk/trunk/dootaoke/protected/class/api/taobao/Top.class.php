<?php
require_once 'top.config.php';
require_once dirname(__FILE__) . '/../Util.php';

define('MAX_ERROR_NUM',2);
class Top {
//API系统参数
var $topParamArr = null;

//API用户参数
var $userParamArr = null;

// 是否存在错误
var $hasError = false;

// 错误code
var $errorCode = null;

// 错误message
var $errorMsg = null;

/**
* 初始化参数
*/
function initTaobaokeTop() {
	$this->topParamArr = array(
	'app_key' => APP_KEY,
	'app_secret'=>APP_SECRET,
	'format' => 'xml',
	'v' => '2.0',
	'timestamp' => date('Y-m-d H:i:s')
	);
	
	$this->userParamArr = array(
	'nick' => NICK
	);
}

/**
* 初始化参数
*/
function initTaobaoTop() {
	$this->topParamArr = array(
	'app_key' => APP_KEY,
	'app_secret'=>APP_SECRET,
	'format' => 'xml',
	'v' => '2.0',
	'timestamp' => date('Y-m-d H:i:s')
	);
}

/**
* 获得数据
*/
function getArrayData($paramArr,$method="POST"){
	$roopFlag = true;
	$roopCount = 0;
	$result = null;
	// 如果出错循环MAX_ERROR_NUM次
	while($roopFlag){
		if($method == "GET"){
			/*
			 * 以GET方式访问服务
			 */
			$result = Util::getResult($paramArr);
		}else{
			/*
			 * 以POST方式访问服务
			 */
			$result = Util::postResult($paramArr);
		}
		
		//解析xml结果
		$result = Util::getXmlData($result);

		$this->getError($result);
		sleep($roopCount);
		$roopCount++;
		if((!$this->hasError) || ($roopCount >= MAX_ERROR_NUM)){
			$roopFlag = false;
		}
	}

	// 如果出错，则跳转到错误画面
	if($this->hasError){
		echo $this->errorMsg;
		return null;
		//header("location:" . ERROR_PAGE);
		//exit;
	}
	
	return $result;
}

/**
* 获得淘宝商品数据
*/
function searchTaobaokeData($searchParams,$method= TAOBAOKE_ITEMS_GET ,$way = "POST"){
	// 初始化参数
	$this->initTaobaokeTop();
	
	$topParamArr = $this->topParamArr;
	$topParamArr['method'] = $method;
	$userParamArr = $this->userParamArr + $searchParams;
	
	//总参数数组
	$paramArr = $topParamArr + $userParamArr;
	$result = $this->getArrayData($paramArr,$way);
	
	return $result;
}

/**
* 获得淘宝商品数据
*/
function searchTaobaoData($searchParams,$method= TAOBAOKE_ITEMS_GET ,$way = "POST"){
	// 初始化参数
	if($this->topParamArr == null || sizeof($this->topParamArr) < 1){
		$this->initTaobaoTop();
	}

	//$topParamArr = $this->topParamArr;
	$this->topParamArr['method'] = $method;

	//总参数数组
	$paramArr = $this->topParamArr + $searchParams;

	$result = $this->getArrayData($paramArr,$way);

	return $result;
}

/**
 * 是否返回错误
 */
function getError($result){
	if(isset($result['code'])){
		$this->errorCode = $result['code'];
		$this->errorMsg = $result['msg'];
		$this->hasError = true;
	}
}

/**
* 获得当前检索条件下的商品数
*/
function getTotalResults($result){
	$tmp = 0;
	if(isset($result['total_results'])){
		$tmp = $result['total_results'];
	}
	return $tmp;
}

/**
 * 获得结果中的某项数据，保存在数组中
 */
 function getArrayOfResult($target) {
 	if($target[0]) {
 		return $target;
 	} else {
 		$tmp[] = $target;
 		return $tmp;
 	}
 }
}
?>