<?php
require_once 'Top.class.php';
/**
*  查询淘宝分类
*/
class TaobaoCat extends Top{
	/**
	* 淘宝分类查询 获取后台供卖家发布商品的标准商品类目
	*/
	function getItemCats($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMCATS_GET,$way);
		return $result;
	}
	
	/**
	* 淘宝分类查询 查询B商家被授权品牌列表和类目列表
	*/
	function getItemCatsAuthorize($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMCATS_AUTHORIZE_GET,$way);
		return $result;
	}
	
	/**
	* 淘宝分类查询 获取标准商品类目属性 
	*/
	function getItemProps($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMPROPS_GET,$way);
		return $result;
	}
	
	/**
	* 淘宝分类查询 获取标准类目属性值
	*/
	function getItemPropValues($searchParams,$way = "POST"){
		$result = $this->searchTaobaoData($searchParams,TAOBAO_ITEMPROPVALUES_GET,$way);
		return $result;
	}
	
	/**
	* 通过节点的所有子类
	*/
	function getSubCats($element) {
		if(!is_numeric($element)) {
			$element = '0';
		}else if($element < 0) {
			$element = '0';
		}
		//API用户参数
		$searchArr = array(
			'fields' => 'cid,parent_cid,name,sort_order,is_parent,status',
			'parent_cid' => $element
		);
		$itemCats = $this->getItemCats($searchArr);

		return $itemCats;
	}
	
	/**
	* 获得所有分类
	*/
	function getItemCatsOfResult($result) {
		if(isset($result['item_cats'])){
			return $result['item_cats'];
		}
		
		return null;
	}
	
		/**
	* 获得所有分类
	*/
	function getItemCatOfResult($result) {
		if(isset($result['item_cats']['item_cat'])){
			return $this->getArrayOfResult($result['item_cats']['item_cat']);
		}
		
		return null;
	}
	
	/**
	* 通过根节点的所有子类
	*/
	function getRootSubCats() {
		//API用户参数
		return $this->getSubCats('0');
	}
	
	/**
	* 通过分类ID获得分类的名称
	*/
	function getCats($catIds) {
		//API用户参数
		$searchArr = array(
			'fields' => 'cid,parent_cid,name,sort_order,is_parent,status',
			'cids' => $catIds
		);
		$itemCats = $this->getItemCats($searchArr);

		return $this->getItemCatOfResult($itemCats);
	}
	
		/**
	* 通过分类ID获得分类的名称
	*/
	function getCat($catId) {
		//API用户参数
		$searchArr = array(
			'fields' => 'cid,parent_cid,name,sort_order,is_parent,status',
			'cids' => $catId
		);
		$itemCats = $this->getItemCats($searchArr);

		return $this->getItemCatOfResult($itemCats);
	}
	
	/**
	 * 属性数组
	 */
	function get_item_props($result) {
		if($result['item_props']['@attributes']['list']) {
			return $result['item_props']['item_prop'];
		}
	}
}
?>