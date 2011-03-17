<?php
require_once 'Top.class.php';
/**
*  查询画报
*/
class TaobaoHuabao extends Top{
	
	// 取频道信息
	function getChannel($channel_id,$way = "POST"){
		$searchParams['channel_id'] = $channel_id;
		$result = $this->searchTaobaoData($searchParams,TAOBAO_HUABAO_CHANNEL_GET,$way);
		
		return $result;
	}
	
	// 取画报频道
	function getChannels($way = "POST"){
		$result = $this->searchTaobaoData(array(),TAOBAO_HUABAO_CHANNELS_GET,$way);
		
		return $result;
	}
	
	// 取画报详情
	function getPoster($poster_id,$way = "POST"){
		$searchParams['poster_id'] = $poster_id;
		$result = $this->searchTaobaoData($searchParams,TAOBAO_HUABAO_POSTER_GET,$way);
		
		return $result;
	}
	
	// 取指定频道Id的画报列表
	/**
	channel_id 	Number 	必须 	频道的Id值 	2000 	
	page_size 	Number 	可选 	查询返回的记录数 	20 	20
	page_no 	Number 	可选 	当前页，默认为1（当输入为负，零，或者超出页数范围时，取默认值） 	10 	1
	*/
	function getPosters($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_HUABAO_POSTERS_GET,$way);
		
		return $result;
	}
	
	// 获取指定画报列表
	/**
		channel_ids 	Number 	必须 	频道Id 	1 	
		number 	Number 	可选 	返回的记录数，默认10条，最多20条，如果请求超过20或者小于等于0，则按10条返回 	10 	10
		type 	String 	必须 	类型可选：HOT(热门），RECOMMEND（推荐） 	HOT
	*/
	function getSpecialPosters($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_HUABAO_SPECIALPOSTERS_GET,$way);
		
		return $result;
	}
	
	
	// 获得取画报频道数据
	function get_poster_channel($result) {
		if($result['channels']['@attributes']['list'] == 'true') {
			return $this->getArrayOfResult($result['channels']['poster_channel']);
		} else {
			$tmp = array();
			$tmp[] = $result['channels']['poster_channel'];
			return $tmp;
		}
	}
	
    // 获得取画报频道数据
	function get_channel_by_id($result,$id) {
		if(isset($result['posters']['@attributes']['list'])) {
			$tmp = $this->get_poster_channel($result);
		} else {
			$tmp = $result;
		}
		
		
		foreach($tmp as $val) {
			if($val['id'] == $id) return $val;
		}
	}
	
	// 获得取画报
	function get_poster($result) {
		if($result['posters']['@attributes']['list'] == 'true') {
			return $this->getArrayOfResult($result['posters']['poster']);
		} else {
			$tmp = array();
			//$tmp[] = $result['posters']['poster'];
			return $tmp;
		}
	}
	
	// 获得取画报
	function get_poster_pics($result) {
		if($result['pics']['@attributes']['list'] == 'true') {
			return $this->getArrayOfResult($result['pics']['poster_picture']);
		} else {
			$tmp = array();
			//$tmp[] = $result['pics']['poster_picture'];
			return $tmp;
		}
	}
}
?>