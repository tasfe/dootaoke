<?php
require_once 'Top.class.php';
/**
*  查询淘宝用户信息查询
*/
class TaobaoUser extends Top{
	
	/**
	* 淘宝客用户查询
	*/
	function getUser($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_USER_GET,$way);
		$tmp = "";
		if(isset($result['user'])){
			$tmp = $result['user'];
		}
		return $tmp;
	}
	
	// 卖家信用
	function getSellerCredit($user) {
		$tmp = "";
		if(isset($user['seller_credit'])){
			$tmp = $user['seller_credit'];
		}
		return $tmp;
	}
	
	// 买家信用
	function getBuyerCredit($user) {
		$tmp = "";
		if(isset($user['buyer_credit'])){
			$tmp = $user['buyer_credit'];
		}
		return $tmp;
	}
	
	// 卖家信用等级
	function getSellerLevel($user) {
		$tmp = "";
		if(isset($user['seller_credit']['level'])){
			$tmp = $user['seller_credit']['level'];
		}
		return $tmp;
	}
	
	// 买家信用等级
	function getBuyerLevel($user) {
		$tmp = "";
		if(isset($user['buyer_credit']['level'])){
			$tmp = $user['buyer_credit']['level'];
		}
		return $tmp;
	}
	
	// 卖家信用等级
	function getSellerScore($user) {
		$tmp = "";

		if(isset($user['seller_credit']['score'])){
			$tmp = $user['seller_credit']['score'];
		}

		return $tmp;
	}
	
	// 买家信用等级
	function getBuyerScore($user) {
		$tmp = "";
		if(isset($user['buyer_credit']['score'])){
			$tmp = $user['buyer_credit']['score'];
		}
		return $tmp;
	}
	
	// 卖家好评率
	function getSellerCreditRate($user) {
		$tmp = "";
		if(isset($user['seller_credit']['total_num']) && isset($user['seller_credit']['good_num'])){
			$total_num = $user['seller_credit']['total_num'];
			$good_num = $user['seller_credit']['good_num'];
			$tmp = $good_num/$total_num;
		}
		
		return $tmp;
	}
	
	// 买家好评率
	function getBuyerCreditRate($user) {
		$tmp = "";
		if(isset($user['buyer_credit']['total_num']) && isset($user['buyer_credit']['good_num'])){
			$total_num = $user['buyer_credit']['total_num'];
			$good_num = $user['buyer_credit']['good_num'];
			$tmp = $good_num/$total_num;
		}
		
		return $tmp;
	}
}
?>