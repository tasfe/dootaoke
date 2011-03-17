<?php
require_once 'Top.class.php';
/**
*  查询淘宝产品
*/
class TaobaoProduct extends Top{

	/**
	* 淘宝产品查询 获取一个产品的信息
	*/
	function getProduct($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_PRODUCT_GET,$way);
		return $result;
	}
		
	/**
	* 淘宝产品查询 获取产品列表
	*/
	function getProducts($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_PRODUCTS_GET,$way);
		return $result;
	}
		
	/**
	* 淘宝产品查询 搜索产品列表
	*/
	function searchProducts($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_PRODUCTS_SEARCH,$way);
		return $result;
	}
	
	/**
	 * 淘宝产品查询
	 */
	function getProductById($id,$way="POST") {
		if(!$id || !is_numeric($id)) return;
		
		$searchParams = array(
		'fields' => 'product_id,pic_url,created,modified,tsc,cid,cat_name,props,props_str,name,binds,binds_str,sale_props,sale_props_str,price,desc,product_imgs.url,product_prop_imgs.url,product_prop_imgs.props,status,collect_num,level,pic_path,vertical_market,customer_props,property_alias',
		'product_id' => $id
		);
		
		return $this->getProduct($searchParams,$way);
	}
	
	
	/**
	* 从结果中获得数据
	*/
	function getProductArray($result) {
		if($result['products']['@attributes']['list']) {
			return $this->getArrayOfResult($result['products']['product']);
		}
		
		return null;
	}
	
	/**
	* 从结果中获得数据
	*/
	function getItemPropArray($result) {
		if($result['item_props']['@attributes']['list']) {
			return $this->getArrayOfResult($result['item_props']['item_prop']);
		}
		
		return null;
	}
}
?>