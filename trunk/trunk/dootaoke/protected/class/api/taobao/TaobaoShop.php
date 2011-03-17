<?php
require_once 'Top.class.php';
/**
*  查询淘宝客商品
*/
class TaobaoShop extends Top{
	/**
	* 淘宝客商品查询
	*/
	function getShop($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_SHOP_GET,$way);
		$tmp = "";
		if(isset($result['shop'])){
			$tmp = $result['shop'];
		}
		return $tmp;
	}
	
	/**
	* 通过nick获得店铺Sid
	*/
	function getSidByNick($nick) {
		if(!isset($nick)) {
			return null;
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'sid',
			'nick' => trim($nick)
		);
		$shop = $this->getShop($searchArr);
		
		if($shop != "" && isset($shop['sid'])) {
			return $shop['sid'];
		}
		return null;
	}
	
	/**
	 * 获得店铺信息
	 */
	function getShopInfo($nick) {
		if(!$nick)	return null;
		
		//API用户参数
		$searchArr = array(
			'fields' => 'sid,cid,nick,title,desc,bulletin,pic_path,created,shop_score',
			'nick' => trim($nick)
		);
		$shop = $this->getShop($searchArr);
		
		return $shop;
	}
}
?>