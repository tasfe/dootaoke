<?php
/**
 * MallShopController

 * 商城店铺
 *
 * @author darkredz
 */
require_once 'MallController.php';
class MallShopController extends MallController{
	/**
	 * 店铺首页
	 */
	public function shop_index() {
		// css文件
        $data['page_css'] = 'shop';
        
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = 'shop'; 
		$this->view()->renderc('shop_index', $data);
	}
	/**
	 * 店铺详情
	 */
	 public function shop_detail() {
	 	// 参数列表，传递到画面
    	$data['param_keys'] = array('cat','q','id','page');
    	
    	//参数格式转换,接收从list/:p传递的数据
    	Doo::loadClass('util/StringUtil');
        $params = StringUtil::convert_urlparams_array($this->params['p'],$data['param_keys']);
        
        // 没有默认值
        $cat_id = $params['cat'];
        
        // 接收从/list传递的数据
        $params = $this->setRelatedParams($params,$data['param_keys']);
        
        // 参数，传递到画面
        $data['params'] = $this->setDefaultParams($params);
		$data['params']['cat'] = $cat_id;
		    	
		Doo::loadClass(array('util/CacheUtil','util/CommonUtil'));
    	// 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoItem = OpenApi::loadApi('TaobaoItem');
    	
    	$searchParams = array('page_no'=>$params['page'],'genuine_security' => 'true','page_size'=>40,);
    	if($params['q']) $searchParams['q'] =$params['q'];
    	if($cat_id) $searchParams['cid'] =$cat_id;

		$nick = $params['id'];
		$items = CacheUtil::getCachedSellerItems($taobaoItem,$nick,$searchParams);

		$data['total_items'] = $total = $taobaoItem->getTotalResults($items);
		if($total) {
			// 商品数据
			$data['catArray'] = $this->orderCatsByNumCid($taobaoItem->get_item_categories($items));
			$data['itemArray'] = $taobaoItem->get_items($items);
		}
		
		if($total) {
			$taobaoShop = OpenApi::loadApi('TaobaoShop');
			$data['shop_info'] = CacheUtil::getCachedShopInfo($taobaoShop,$nick);
		}
		
		// 从缓存读取所有分类文件缓存
        $data['all_cats'] = $all_cats = $this->readAllCats();
        
		// 面包屑
		$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER.'shop/id-' . urlencode($nick),'name'=>$data['shop_info']['title']);
		if($cat_id) {
			$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER.'cat-' . $cat_id,'name'=>$all_cats[$cat_id]->name);
		}
		
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = 'shop';
		
        // 从p参数中取得page
        $data['current_page'] = $data['params']['page'];
        unset($data['params']['page']);
        
		// css文件
        $data['page_css'] = 'list';
		$this->view()->renderc('shop_detail', $data);
	 }
}
?>