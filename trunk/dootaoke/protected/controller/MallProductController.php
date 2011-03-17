<?php
/**
 * MallProductController
 * 产品
 *
 * @author darkredz
 */
require_once 'MallController.php';
class MallProductController extends MallController{
	/**
	 * 产品库首页
	 */
	public function products_index() {
		global $_G;
    	$taobao_sets = $_G['taobao'];
    	// 顶层分类
    	$data['top_cats'] = $top_cats = $taobao_sets['top_cats'];
    	
    	// 所有商品分类
    	$data['all_cats'] = $this->readAllCats();
    	
    	// 产品推荐品牌
    	$this->loadData('data/props/product_brands');
    	$data['product_brands'] = $_G['product_brands'];
    	
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = 'products';
		
		// css文件
        $data['page_css'] = 'listp';
		    			
		$this->view()->renderc('products_index',$data);
	}
	
	/**
	 * 产品详情
	 */
	public function product_detail() {
		//$data['param_keys'] = array('id','page');
		$pid = $this->params['id'];
		$page = $this->getPageNo($this->params['page']);
		
		//$data['params'] = $this->setRelatedParams(null,$data['param_keys']);
		
		// 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoProduct = OpenApi::loadApi('TaobaoProduct');
    	
    	// 读取缓存
    	Doo::loadClass('util/CacheUtil');
    	$product = CacheUtil::getCachedProductById($taobaoProduct,$pid);
		$data['product_detail'] = $product['product'];
		
		// 相同产品下的商品
    	$searchParams = array('fields'=>'num_iid,title,nick,pic_url,cid,price,type,delist_time,post_fee,has_discount,num,is_prepay,promoted_service,ww_status,list_time,location,volume',
				'genuine_security' => 'true',// 正品保障商品
				'product_id'=>$pid,
				'page_size'=>40,'order_by'=>'popularity:desc');
		if($page) $searchParams['page_no'] = $page;
		
		$taobaoItem = OpenApi::loadApi('TaobaoItem');
		$items = $taobaoItem->searchItems($searchParams);
		$total = $taobaoItem->getTotalResults($items);
		
		$data['total_items'] = $total; 
		if($total) {
			// 商品数据
			$data['catArray'] = $this->orderCatsByNumCid($taobaoItem->get_item_categories($items));
			$data['itemArray'] = $taobaoItem->get_items($items);
		}
		
		// 左侧推荐商品
		$taobaoke = OpenApi::loadApi('Taobaoke');
		$searchParams = array('cid'=>$data['product_detail']['cid'],'mall_item'=>'true','page_size'=>10,'start_commissionRate'=>300);
		//if($page) $searchParams['page_no'] = $page;
		$taokeItems = $taobaoke->getItemsByCatId($searchParams);
		$data['leftItems'] = $taobaoke->getTaobaokeItem($taokeItems);
		
        // 从p参数中取得page
        $data['current_page'] = $page;
        
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = 'products';
		
		// css文件
        $data['page_css'] = 'detailp';
    	$this->view()->renderc('product_detail',$data);
	}

	/**
	 * 搜索产品库
	 */
	public function search_products() {
		$this->list_products();
	}		
	/**
	 * 产品库
	 */
	public function list_products() {
		$data['param_keys'] = $param_keys = array('q','cat','props','page');
		//传入参数
    	//参数格式转换,接收从list/:p传递的数据
    	Doo::loadClass('util/StringUtil');
        $params = StringUtil::convert_urlparams_array($this->params['p'],$data['param_keys']);

		// 设置参数
		$params = $this->setRelatedParams($params,$param_keys);

		$q = $params['q'];
		$props = $params['props'];
		$isProps = $this->isProps($q);
		
		if($isProps) {
			$params['props'] = $q;
			unset($params['q']);
		} else {
			$params['q'] = $q;
			unset($params['props']);
		}
		
		if(!$params['props'] && $props) {
			$params['props'] = $props;
		}
		
		// 设置分类
		$params['cat'] = $this->getDefaultCat($params['cat'],false);
		// 设置页码
		$params['page'] = $this->getPageNo($params['page']);
		$data['params'] = $params;
		
		global $_G;
    	$taobao_sets = $_G['taobao'];
    	// 顶层分类
    	$data['top_cats'] = $top_cats = $taobao_sets['top_cats'];
    	
    	// 所有商品分类
    	$data['all_cats'] = $this->readAllCats();
		// 根分类id,作为头部导航的标识
		$data['head_nav_id'] = 'products';
		if(!$q && !$props) {
			$this->view()->renderc('products_index',$data);
			//Doo::loadHelper('DooUrlBuilder');
			//DooUriRouter::redirect(DooUrlBuilder::url2('MallProductController', 'products_index', null,true),true,301);
			return;	
		}
		
		// 搜索
		// 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoProduct = OpenApi::loadApi('TaobaoProduct');
    	$searchParams = array('fields'=>'product_id,name,pic_url,cid,props,price,tsc,status',
    	'page_no' => $params['page'],'page_size'=>40,'status'=>'3');// 当前状态(0 商家确认 1 屏蔽 3 小二确认 2 未确认 -1 删除)
    	if($params['cat']) {
    		$searchParams['cid'] = $params['cat'];
    	}
    	if($params['props']) {
    		$searchParams['props'] = $params['props'];
    	} 
    	if($params['q']) {
    		$searchParams['q'] = $params['q'];
    	}

    	//当前页
    	$data['current_page'] = $params['page'];
		// 产品数据
    	$products = $taobaoProduct->searchProducts($searchParams);

    	$data['itemArray'] = $taobaoProduct->getProductArray($products);
    	$data['total_items'] = $taobaoProduct->getTotalResults($products);
    	
    	// 设置分类
		$d_cat = $this->getDefaultCat($params['cat']);
    	// 左侧推荐商品
		$taobaoke = OpenApi::loadApi('Taobaoke');
		$searchParams = array('cid'=>$d_cat,'mall_item'=>'true','page_size'=>10,'start_commissionRate'=>300,'order'=>'commissionNum_desc');
		if(!$isProps) {
			$searchParams['keyword'] = $q;
		}
		
		$taokeItems = $taobaoke->getItemsByCatId($searchParams);
		$data['leftItems'] = $taobaoke->getTaobaokeItem($taokeItems);
    	
    	// 面包屑
    	$data['crumbs'][] = array('url'=>Doo::conf()->SUBFOLDER.'cat-' . $params['cat'] . '-q-' .urlencode($params['q']),'name'=>$params['q']); 
    	
    	// css文件
        $data['page_css'] = 'listp';
		
    	$this->view()->renderc('list_products',$data);
	}
}
?>