<?php
/**
 * MallItemController
 * 商城
 *
 * @author darkredz
 */
require_once 'MallController.php';
class MallItemController extends MallController{
	/**
	 * 商品详情
	 */
    public function item_detail(){
    	// 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoItem = OpenApi::loadApi('TaobaoItem');
    	$data['TAOBAO_ITEM'] = $taobaoItem; 
    	$data['item_detail'] = $item_detail =  $taobaoItem->getItemDetail($this->params['id']);
		if(!$data['item_detail']) {
			DooUriRouter::redirect('404.php',true,'404');	
		}
		
        // 从缓存读取所有分类文件缓存
        $data['all_cats'] = $all_cats = $this->readAllCats();
        
        //当前分类
        $data['current_cat'] = $current_cat = $all_cats[$item_detail['cid']];
        
        // 同级分类
    	$pid = $current_cat->parent_cid;
		$data['catArray'] = array();
		foreach($all_cats as $k => $cat) {
			if($cat->parent_cid == $pid) {
				$data['catArray'][$k]['category_id'] = $cat->cid;	
			}
		}
		
		// 面包屑导航条
		$pCats = $this->getParentCats($all_cats,$current_cat);
		$pCats = array_reverse($pCats);
		foreach($pCats as $cat) {
			$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER.'cat-' . $cat->cid,'name'=>$cat->name);	
		}
		
		// 店铺信息
		Doo::loadClass('util/CacheUtil');
		$taobaoShop = OpenApi::loadApi('TaobaoShop');
		$data['shop_info'] = CacheUtil::getCachedShopInfo($taobaoShop,$item_detail['nick']);
		// 店长信息
		$taobaoUser = OpenApi::loadApi('TaobaoUser');
		$data['user_info'] = CacheUtil::getCachedUserInfo($taobaoUser,$item_detail['nick']);
		
		// 卖家相关商品
    	$searchParams = array('page_no'=>1,'genuine_security' => 'true','page_size'=>40,);
		$items = CacheUtil::getCachedSellerItems($taobaoItem,$item_detail['nick'],$searchParams);
		
		$data['total_items'] = $total = $taobaoItem->getTotalResults($items);
		if($total) {
			// 商品数据
			//$data['catArray'] = $this->orderCatsByNumCid($taobaoItem->get_item_categories($items));
			$data['itemArray'] = $taobaoItem->get_items($items);
		}
		
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = $this->getRootCat($pCats)->cid;
		
		// css文件
        $data['page_css'] = 'detail';	
		$this->view()->renderc('item_detail', $data);
    }
    
    /**
     * 商品列表
     * 
     * 按照条件展示：分类 属性 关键字 价格 地区 排序 页面 类型
     */
    public function list_items(){
    	// 参数列表，传递到画面
    	$data['param_keys'] = array('cat','props','q','p1','p2','state','city','order','page','type','dazhe','huodao','freepost','mall');
    	//参数格式转换,接收从list/:p传递的数据
    	Doo::loadClass('util/StringUtil');
        $params = StringUtil::convert_urlparams_array($this->params['p'],$data['param_keys']);
        
        // 接收从/list传递的数据
        $params = $this->setRelatedParams($params,$data['param_keys']);
        
        // 参数，传递到画面
        $data['params'] = $this->setDefaultParams($params);

        global $_G;
        $taobao_sets = $_G['taobao'];
		//print_r($params);
        // 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoItem = OpenApi::loadApi('TaobaoItem');
    	$searchParams = array('fields'=>'num_iid,product_id,title,nick,pic_url,cid,price,type,delist_time,post_fee,has_discount,num,is_prepay,promoted_service,ww_status,list_time,location,volume',
    					'genuine_security' => 'true',// 正品保障商品
						'cid'=>$params['cat'],//当前分类
						'page_size'=>40,);
		if($params['q']) $searchParams['q'] =$params['q'];
		if($params['props']) $searchParams['props'] =$params['props'];
		if($params['page']) $searchParams['page_no'] =$params['page']; 
		if($params['p1']) $searchParams['start_price'] =$params['p1'];
		if($params['p2']) $searchParams['end_price'] =$params['p2'];
		if($params['state']) $searchParams['location.state'] =$params['state'];
		if($params['city']) $searchParams['location.city'] =$params['ciy'];
		if($params['order']) $searchParams['order_by'] = $taobao_sets['orders'][$params['order']]['key'];
		if($params['huodao']) $searchParams['is_cod'] = 'true';
		if($params['freepost']) $searchParams['post_free'] = 'true';
		if($params['dazhe']) $searchParams['has_discount'] = 'true';
		if($params['mall']) $searchParams['is_mall'] = 'true';
		
		$items = $this->getListItems($taobaoItem, $searchParams);
		$total = $taobaoItem->getTotalResults($items);
		
		$data['total_items'] = $total; 
		if($total) {
			// 商品数据
			$data['catArray'] = $this->orderCatsByNumCid($taobaoItem->get_item_categories($items));
			$data['itemArray'] = $taobaoItem->get_items($items);
		}
        
        // 从缓存读取所有分类文件缓存
        $data['all_cats'] = $all_cats = $this->readAllCats();
        
        //当前分类
        $data['current_cat'] = $current_cat = $all_cats[$params['cat']];

		// 枚举属性
		if($current_cat && $current_cat->is_parent == 'false') {
			Doo::loadClass('util/CacheUtil');
			$taobaocat = OpenApi::loadApi('TaobaoCat');

			$searchParams = array('cid'=>$params['cat'],'is_key_prop'=>'false','is_enum_prop'=>'true');
			$result = CacheUtil::getCachedCatProps($taobaocat,$searchParams,'enum');
			$data['props'] = $taobaocat->get_item_props($result);
			//$data['all_props'] = $data['props'];
		
//			if($params['props']) {
//				$searchParams = array('cid'=>$params['cat'],'child_path'=>$params['props']);
//				$result = $taobaocat->getItemProps($searchParams);
//				$data['props'] = $taobaocat->get_item_props($result);
//			}
			
			// 是否包含品牌属性,删除品牌属性
			$has_brand = false;
			foreach($data['props'] as $k => $prop) {
				if($prop['pid'] == 20000) {
					$has_brand = true;
					unset($data['props'][$k]);
					break;					
				}
			}
			// 读取品牌属性
			$this->loadData('data/props/' . $params['cat'] . '_brand_prop');
			if($_G['default_brand'])
			array_unshift($data['props'],$_G['default_brand']);
			
//			if(!$has_brand) {
//				// 分类品牌
//				$searchParams = array('cid'=>$params['cat'],'is_key_prop'=>'true','pid'=>'20000');
//				$result = CacheUtil::getCachedCatProps($taobaocat,$searchParams,'brand');
//				$props_brand = $taobaocat->get_item_props($result);
//				if($props_brand) array_unshift($data['props'],$props_brand);
//			}
		} else {
			// 默认枚举属性
			$this->loadData('data/props/' . $params['cat'] . '_prop');
			if($_G['default_prop'])
			$data['props'] = $_G['default_prop'];
		}
		if(!is_array($data['props'][0])) $data['props'][] = $data['props'];
		
		// 不存在子分类时，显示所有同级分类
		if(empty($data['catArray'])) {
			$pid = $current_cat->parent_cid;
			$data['catArray'] = array();
			foreach($all_cats as $k => $cat) {
				if($cat->parent_cid == $pid) {
					$data['catArray'][$k]['category_id'] = $cat->cid;	
				}
								
			}
		}
		
		// 面包屑导航条
		//$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER,'name'=>'首页');
		$pCats = $this->getParentCats($all_cats,$current_cat);
		$pCats = array_reverse($pCats);
		foreach($pCats as $cat) {
			$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER.'list/cat-' . $cat->cid,'name'=>$cat->name);	
		}
		
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = $this->getRootCat($pCats)->cid;

        // seo url优化:去掉默认值
        if($data['params']['order']==1) unset($data['params']['order']);
        // 从p参数中取得page
        $data['current_page'] = $data['params']['page'];
        unset($data['params']['page']);
        
        // css文件
        $data['page_css'] = 'list';
        
        $this->view()->renderc('list_items', $data);
    }
     
}
?>