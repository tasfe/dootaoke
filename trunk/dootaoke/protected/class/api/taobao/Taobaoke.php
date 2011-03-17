<?php
require_once 'Top.class.php';
/**
*  查询淘宝客商品
*/
class Taobaoke extends Top{
	/**
	* 淘宝客商品查询
	*/
	function getItems($searchParams,$way = "POST"){
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_ITEMS_GET,$way);
	}
	
	/**
	* 淘宝客商品转换
	*/
	function convertItems($searchParams,$way = "POST") {
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_ITEMS_CONVERT,$way);
	}
	
	/**
	* 淘宝客店铺查询
	*/
	function getShops($searchParams,$way = "POST"){
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_SHOPS_GET,$way);
	}
	
	/**
	* 淘宝客店铺转换
	*/
	function convertShops($searchParams,$way = "POST") {
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_SHOPS_CONVERT,$way);
	}
	
	/**
	* 查询淘宝客推广商品详细信息
	*/
	function getItemsDetail($searchParams,$way = "POST") {
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_ITEMS_DETAIL_GET,$way);
	}
	
	/**
	* 关键字URL
	*/
	function getListurl($q,$way = "POST") {
		$searchParams['q'] = $q;
		$searchParams['outer_code'] = 'lu';
		return $this->searchTaobaokeData($searchParams,TAOBAOKE_LISTURL_GET,$way);
	}
		
	/**
	* 获得当前检索条件下的所有数据
	*/
	function getTaobaokeItemDetail($result) {
		$tmp = "";
		if(isset($result['taobaoke_item_details']['taobaoke_item_detail'])){
			$tmp = $result['taobaoke_item_details']['taobaoke_item_detail'];
		}
		return $tmp;
	}
	
	/**
	* 获得当前检索条件下的所有数据
	*/
	function getTaobaokeItem($result){
		$tmp = null;
		if(isset($result['taobaoke_items']['taobaoke_item'])){
			return $this->getArrayOfResult($result['taobaoke_items']['taobaoke_item']);
		}
		return $tmp;
	}
	
	/**
	* 获得当前检索条件下的所有数据
	*/
	function getTaobaokeShop($result){
		$tmp = "";
		if(isset($result['taobaoke_shops']['taobaoke_shop'])){
			return $this->getArrayOfResult($result['taobaoke_shops']['taobaoke_shop']);
		}
		return $tmp;
	}
	
	/**
	* 通过商品ID获得商品的点击连接
	*/
	function getClickUrlByIid($iid,$way = "POST"){
		//API用户参数
		$searchArr = array(
			'fields' => 'click_url',
			'num_iids' => trim($iid)
		);
		$result = $this->convertItems($searchArr,$way);
		$result2 = $this->getTaobaokeItem($result);

		$tmp = "";
		if(isset($result2[0]['click_url'])){
			$tmp = $result2[0]['click_url'];
		}
		return $tmp;
	}
	
	/**
	* 通过店铺ID获得店铺的点击连接
	*/
	function getClickUrlBySid($sid,$way = "POST"){
		//API用户参数
		$searchArr = array(
			'fields' => 'click_url',
			'sids' => trim($sid)
		);
		
		$result = $this->convertShops($searchArr,$way);
		$result2 = $this->getTaobaokeShop($result);

		$tmp = "";
		if(isset($result2[0]['click_url'])){
			$tmp = $result2[0]['click_url'];
		}

		return $tmp;
	}
	
	/**
	* 淘宝:通过分类查询
	*/
	function getItemsByCatId($searchParams,$way = "POST"){
		$catId = $searchParams['cid'];
		if($catId == null || $catId == "") {
			return "";
		}
		
		$page_no = isset($searchParams['page_no'])?$searchParams['page_no']:1;
		$sort = isset($searchParams['sort'])?$searchParams['sort']:'commissionNum_desc';
		$start_credit = isset($searchParams['start_credit'])?$searchParams['start_credit']:'1heart';
		$end_credit = isset($searchParams['end_credit'])?$searchParams['end_credit']:'5goldencrown';
		$page_size = isset($searchParams['page_size'])?$searchParams['page_size']:40;
		
		//API用户参数
		$searchArr = array(
			'fields' => 'iid,num_iid,title,nick,volume,shop_click_url,pic_url,price,seller_credit_score,item_location,commission_num',
			'sort'=> trim($sort),//默认成交量由高到低
			'page_no' => $page_no,
			'cid' => trim($catId),
			'page_size' => $page_size,
			'guarantee' => 'true',
			'start_credit' => $start_credit,
			'end_credit' => $end_credit,
		);
		
		if(isset($searchParams['area']) && $searchParams['area'] != null && $searchParams['area'] != ""){
			$searchArr['area'] = $searchParams['area'];
		}
		
		if(isset($searchParams['mall_item']) && $searchParams['mall_item']== "true"){
			$searchArr['mall_item'] = 'true';
		}
		
		if(isset($searchParams['keyword']) && $searchParams['keyword'] != null && $searchParams['keyword'] != ""){
			$searchArr['keyword'] = $searchParams['keyword'];
			if(!$catId){
				unset($searchArr['cid']);
			}
		}
		
		if(isset($searchParams['start_price']) && $searchParams['start_price'] != null && $searchParams['start_price'] != "" &&
		   isset($searchParams['end_price']) && $searchParams['end_price'] != null && $searchParams['end_price'] != "" ){
			$searchArr['start_price'] = $searchParams['start_price'];
			$searchArr['end_price'] = $searchParams['end_price'];
		}
		
		return $this->getItems($searchArr,$way);
	}
	
	/**
	* 查询单个淘宝客推广商品详细信息
	*/
	function getItemDetail($num_iid,$way = "POST") {
		if($num_iid == null || $num_iid == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'iid,title,item_img.url,props_name,cid,nick,pic_url,type,price,desc,num,location,post_fee,express_fee,ems_fee,has_discount,has_invoice,has_warranty,freight_payer,property_alias,list_time,delist_time,click_url,shop_click_url,seller_credit_score',
			'num_iids'=> trim($num_iid)//淘宝客商品数字id串
		);
		
		return $this->getItemsDetail($searchArr,$way);
	}
	
	/**
	* 查询单个淘宝客推广商品详细信息
	*/
	function getItemsCompareDetail($num_iids,$way = "POST") {
		if($num_iids == null || $num_iids == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'iid,num_iid,props_name,title,cid,nick,pic_url,type,price,stuff_status,auction_point,num,location,post_fee,express_fee,ems_fee,has_discount,has_invoice,has_warranty,freight_payer,property_alias,list_time,delist_time,click_url,shop_click_url,seller_credit_score',
			'num_iids'=> trim($num_iids)//淘宝客商品数字id串
		);
		
		return $this->getItemsDetail($searchArr,$way);
	}
	
	/**
	* 查询奢侈品商品详细信息
	*/
	function getItemsLuxuryDetail($num_iids,$way = "POST") {
		if($num_iids == null || $num_iids == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'num_iid,title,cid,nick,pic_url,type,price,stuff_status,auction_point,num,location,post_fee,express_fee,ems_fee,has_discount,has_invoice,has_warranty,freight_payer,property_alias,list_time,delist_time,click_url,shop_click_url,seller_credit_score',
			'num_iids'=> trim($num_iids)//淘宝客商品数字id串
		);
		
		return $this->getItemsDetail($searchArr,$way);
	}
	
	/**
	* 获得一个ItemDetail数据
	*/
	function getItemDetailList($itemDetail) {
		if(isset($itemDetail['taobaoke_item_details']['taobaoke_item_detail']['item'])) {
			return $itemDetail['taobaoke_item_details']['taobaoke_item_detail']['item'];
		}
		
		return null;
	}
	
	/**
	* 获得商品的所有图片
	*/
	function getItemImgs($itemDetail) {
		$result = null;
		if(isset($itemDetail['item_imgs']['item_img'])) {
			return $this->getArrayOfResult($itemDetail['item_imgs']['item_img']);
		}
		
		return $result;
	}
}
?>