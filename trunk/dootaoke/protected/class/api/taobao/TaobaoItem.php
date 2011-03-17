<?php
require_once 'Top.class.php';
/**
*  查询淘宝商品
*/
class TaobaoItem extends Top{
	/**
	* 淘宝商品查询
	*/
	function getItem($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEM_GET,$way);
		$tmp = "";
		if(isset($result['item'])){
			$tmp = $result['item'];
		}
		
		return $tmp;
	}
	
	/**
	* 淘宝商品列表查询
	*/
	function getItems($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMS_GET,$way);

		return $result;
	}
	
	/**
	* 淘宝商品列表查询
	*/
	function searchItems($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMS_SEARCH,$way);

		return $result;
	}
	
	/**
	* 淘宝商品列表查询
	*/
	function getSellerItems($nick,$params,$way = "POST"){
		$page_no= $params['page_no']?$params['page_no']:1;
		$page_size = $params['page_size']?$params['page_size']:40;
		$order_by = $params['order_by']?$params['order_by']:'volume:desc';
		//API用户参数
		$searchArr = array(
			'fields' => 'num_iid,title,nick,pic_url,cid,price,type,delist_time,post_fee,has_discount,num,is_prepay,promoted_service,ww_status,list_time,location,volume',
			'nicks' => trim($nick),
			'page_no' => $page_no,
			'page_size' => $page_size,
			'order_by' => $order_by,
		);
		
		foreach($params as $k => $v){
			if($v && !array_key_exists($k,$searchArr)){
				$searchArr[$k] = trim($v);
			}
		}
		$result = $this->searchItems($searchArr,$way);
		return $result;
	}
	
	/**
	* 淘宝:通过分类查询
	*/
	function searchItemsByCatId($catId,$page_no = 1,$order_by = 'volume:desc',$way = "POST"){
		if($catId == null || $catId == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'iid,cid,title,nick,pic_url,price,volume,score,location',
			'order_by'=> trim($order_by),//默认成交量由高到低
			'page_no' => $page_no,
			'cid' => trim($catId),
			'page_size' => MAX_PRODUCTS_PER_PAGE_LIST
		);
		
		$result = $this->searchItems($searchArr,$way);

		return $result;
	}
	
	/**
	* 淘宝:通过分类查询
	*/
	function getItemsByCatId($catId,$page_no = 1,$order_by = 'volume:desc',$start_score = 1,$end_score = 20,$way = "POST"){
		if($catId == null || $catId == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'iid,cid,title,nick,pic_url,price,volume,score,location',
			'order_by'=> trim($order_by),//默认成交量由高到低
			'page_no' => $page_no,
			'cid' => trim($catId),
			'page_size' => MAX_PRODUCTS_PER_PAGE_LIST,
			'is_prepay' => 'true',
			'start_score' => $start_score,
			'end_score' => $end_score
		);
		
		$result = $this->getItems($searchArr,$way);

		return $result;
	}
	
	/**
	* 查询单个淘宝商品详细信息
	*/
	function getItemDetail($num_iid,$way = "POST") {
		if($num_iid == null || $num_iid == "") {
			return "";
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'num_iid,desc,auction_point,title,item_img.url,props_name,input_str,cid,nick,pic_url,type,price,num,location,post_fee,express_fee,ems_fee,has_discount,has_invoice,has_warranty,freight_payer,property_alias,list_time,delist_time,sell_promise,cod_postage_id,has_showcase',
			'num_iid'=> trim($num_iid)
		);
		
		return $this->getItem($searchArr,$way);
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

	/**
	 * 获得分类
	 */
	function get_item_categories($result,$root = 'item_search') {
		if($result[$root]['item_categories']['@attributes']['list']) {
			return $this->getArrayOfResult($result[$root]['item_categories']['item_category']);
		}
	}
		
	/**
	 * 获得商品
	 */
	function get_items($result,$root = 'item_search') {
		if($result[$root]['items']['@attributes']['list']) {
			return $this->getArrayOfResult($result[$root]['items']['item']);
		}
	}
}
?>