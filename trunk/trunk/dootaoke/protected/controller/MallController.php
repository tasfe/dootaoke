<?php
/**
 * MallController
 * 商城
 *
 * @author darkredz
 */
class MallController extends DooController{
	
	/**
	 * 网站首页
	 */
	public function index(){		
		global $_G;
    	$taobao_sets = $_G['taobao'];
    	// 顶层分类
    	$data['top_cats'] = $top_cats = $taobao_sets['top_cats'];

		// 商品分类
		// 从缓存读取所有分类文件缓存
        $all_cats = $this->readAllCats();    	
    	// 按父类分组
    	$data['cats'] = $this->groupCatsByParents($top_cats,$all_cats);
    	
		// 热销商品
    	// 开放API
    	Doo::loadClass('api/OpenApi');
    	$taobaoItem = OpenApi::loadApi('TaobaoItem');
    	foreach($top_cats as $cid => $name) {
	    	$searchParams = array('fields'=>'num_iid,product_id,title,nick,pic_url,cid,price,type,delist_time,post_fee,has_discount,num,is_prepay,promoted_service,ww_status,list_time,location,volume',
					'genuine_security' => 'true',// 正品保障商品
					'cid'=>$cid,//当前分类
					'page_size'=>40,
					'order_by' => 'volume:desc',// 销量高低
					'page_no' => 2);
					
			$items = $this->getListItems($taobaoItem, $searchParams,true);
			$data['items'][$cid] = array('cid'=>$cid,'name'=>$name,'items'=>$taobaoItem->get_items($items)); 
    	}
		
		// css文件
        $data['page_css'] = 'index';
		$this->view()->renderc('index', $data);
    }
	/**
	 * 载入数据
	 */
	protected function loadData($file) {
		$data_file = Doo::conf()->SITE_PATH . 'protected/class/' . $file . '.php';
		if(file_exists($data_file)) {
			require_once($data_file);
		}
	}

   /**
     *  1 设置参数默认值
     *  2 检查无效参数数据，替换成有效数据
     */
    protected function setDefaultParams(&$params) {
    	global $_G;
    	$taobao_sets = $_G['taobao'];
    	// 默认分类
    	$params['cat'] = $this->getDefaultCat($params['cat']);
    	
    	// 默认排序
    	if(!array_key_exists($params['order'],$taobao_sets['orders'])) {
    		$params['order'] = $taobao_sets['default']['order'];
    	}
    	// 关键字
    	$params['q'] = trim($params['q']);
    	
    	// 页码
    	if(!$params['page'] || !is_numeric($params['page']) || $params['page'] < 1) {
    		$params['page'] = 1;
    	}
    	
		//属性
		if($params['props'] && !preg_match($taobao_sets['props']['format'],$params['props'])) {
    		$params['props'] = null;
    	}
    	
    	// 价格
    	if($params['p1'] || $params['p2']) {
	    	$p1 = $params['p1'];
    		$p2 = $params['p2'];
    		if(!is_numeric($p1)) $p1 = $taobao_sets['price']['min'];
    		if(!is_numeric($p2)) $p2 = $taobao_sets['price']['max'];
    		$p1 = abs($p1);
    		$p2 = abs($p2);
    		if($p1 > $p2) {
    			$tmp = $p1;
    			$p1 = $p2;
				$p2 = $tmp;    			
    		}
    		$params['p1'] = $p1;
    		$params['p2'] = $p2;
    	}
    	
    	// 有效值huodao mall dazhe freepost
    	$fixed_values = array('huodao'=>1,'mall'=>1,'freepost'=>1,'dazhe'=>1,);
    	foreach($fixed_values as $k => $v) {
    		if($params[$k] && $params[$k]!=$v) {
    			$params[$k] = $v;
    		}
    	}
    	
    	return $params;
    }
	
	/**
	 * 根据分类商品数量和分类ID对分类排序
	 */
	protected function orderCatsByNumCid($cats) {
		if(sizeof($cats) < 1 || !is_array($cats[0])) return;
		
		$tmp = array();
		foreach($cats as $cat) {
			$tmp[$cat['count']] =$cat; 
		}
		krsort($tmp);
		return $tmp;
	}
	
	/**
	 * 获得当前分类路径的所有分类
	 */
	protected function getParentCats($allCats,$cCat) {
		$cats[] = $cCat;
		
		if($cCat->parent_cid==0) return $cats;
		
		$pid = $cCat->parent_cid;
		while($pid!=0) {
			$cats[] = $allCats[$pid];
			$pid = $allCats[$pid]->parent_cid;
		}
		
		return $cats;
	}
	
	/**
	 * 获得当前分类路径的最顶层分类
	 */
	 protected function getRootCat($cats) {
	 	return $cats[0];
	 }
	 
	/**
	 * 从数据库或缓存中读取所有分类数据
	 */
	 protected function readAllCats() {
	 	// 分类
    	Doo::loadModel('Category');
    	$cate = new Category();
        // 从缓存读取所有分类文件缓存
		$fileCache = $this->cache();
		$all_cats = $fileCache->get('all_categories'); 
		
		if(!$all_cats) {
			$all_cats = $cate->selectAllCats();
			$fileCache->set('all_categories',$all_cats);
		}
		
		return $all_cats;
	 }

	/**
	 * 是否是props属性，格式：12345:12345;67890:34567
	 */
	protected function isProps($prop) {
		global $_G;
		$taobao_sets = $_G['taobao'];
		
		//属性
		if(!$prop || !preg_match($taobao_sets['props']['format'],$prop)) {
    		return false;
    	}
    	
    	return true;
	}
	
	/**
	 * 判断分类是否存在，不存在则返回默认分类
	 */
	protected function getDefaultCat($cid,$defaultNull=false) {
		$cid = trim($cid);
		$all_cats = $this->readAllCats();
		if(isset($all_cats[$cid]) && $all_cats[$cid]) {
			return $cid;
		} else {
			if($defaultNull) {
				return null;
			}
			global $_G;
    		return $_G['taobao']['default']['cat'];
		}
	}
	
	/**
	 * 从其他方式获得的参数
	 */
	protected function setRelatedParams($params,$param_keys) {
		foreach($param_keys as $param) {
        	if($this->params[$param]) {
        		$params[$param] = $this->params[$param];
        	}
        	
        	if($_GET[$param]) {
        		$params[$param] = $_GET[$param]; 
        	}
        }
        
        return $params;
	}
	
	/**
	 * 获得有效页
	 */
	protected function getPageNo($page) {
	    if(!$page || !is_numeric($page) || $page < 1) {
    		$page = 1;
    	}
    	
    	return $page;
	}
	
    /**
     * 读取缓存或者数据
     * $cached强制缓存
     */
     protected function getListItems($taobaoItem,$searchParams,$cached=false) {
     	$cacheflg = true;// 缓存标志
     	if(!$cached) {
     		// 缓存第一页
	     	if($searchParams['page_no'] > 1) {
	     		$cacheflg = false;
	     	} else {
	     		// 包含以下条件则不缓存数据
	     		$cond = array('start_price','end_price','location.state','location.city','props','q');
		     	foreach($cond as $v) {
		     		if($searchParams[$v]) {
		     			$cacheflg = false;
		     			break;
		     		}	
		     	}
     		}
     	}

     	if(!$cacheflg) {
     		$items = $taobaoItem->searchItems($searchParams);	
     	} else {
     	    Doo::loadClass('util/CacheUtil');
     		$items = CacheUtil::getCachedSearchItems($taobaoItem,$searchParams);
     	}
     	
     	return $items;
     }
     
     /**
      * 对分类分组,父类下的直接子类
      */
      protected function groupCatsByParents($parentCats,$cats) {
      	$result = array();
	 	// 分类
    	Doo::loadModel('Category');
      	foreach($parentCats as $pcid => $pname) {
      		$result[$pcid]['pcat'] = $cats[$pcid];
      		foreach($cats as $cid => $cat) {
      			if($cid != $pcid && $cat->parent_cid == $pcid) {
      				$result[$pcid]['subcats'][$cat->cid] = $cat; 
      			}
      		}
      	}
      	
      	return $result;
      }
}
?>