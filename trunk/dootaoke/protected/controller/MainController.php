<?php
/**
 * MainController
 * Feel free to delete the methods and replace them with your own code.
 *
 * @author darkredz
 */
class MainController extends DooController{
	/**
	 * 重定向到
	 */
	public function redirect_to() {
	$iid = urldecode($this->params['id']);
	// 跳转方向 默认为产品，店铺:1 商品:2  未知:4 
	$type = $this->params['type'];
	
	$to = "";
	
	// 淘宝客链接转换
	if($type=='9') {
		Doo::loadClass('util/TaobaoUtil');
		$to = TaobaoUtil::convertGotoUrl2TaokeUrl($_SERVER['REQUEST_URI']);

		if(!$to) return 404;
		DooUriRouter::redirect($to,true);
	}
	
	// 搜索
	if(isset($type) && $type == "4"){
		if(substr($iid,0,4) != 'http'){
			$iid = 'http://' . $iid;
		}
	
		//店铺匹配 http://shop12345678.taobao.com http://xxx.taobao.com
		$shopMatch = '/^(http:\/\/)?shop([1-9]\d{5,9})\.taobao\.com(\/)?$/i';
		// 商品：item.taobao.com/item.htm?id=6654689818 item.taobao.com/item.htm?id=8241242601&cm_cat=50003620
		$itemMatch = '/^(http:\/\/)?item\.taobao\.com\/item\.htm\?id=(\d{6,12})[^>]*/i';
		//item.taobao.com/auction/item_detail.htm?item_num_id=7828843050
		$itemMatch2 = '/^(http:\/\/)?item\.taobao\.com\/auction\/item_detail\.htm\?item_num_id=(\d{6,12})[^>]*/i';
		// 搜索 s8.taobao.com/search?q=%D0%AC%D7%D3&commend=all&pid=mm_10369830_0_0
		// search8.taobao.com/browse/search_auction.htm?q=%D0%AC%D7%D3&pid=mm_10007403_0_0
		$searchMatch = '/^(http:\/\/)?\w+\.taobao\.com\/[^>]*search[^>]*q=([^&]*)[^>]*/i';	
		//分类 list.taobao.com/browse/50006843/n-all-50006843.htm?ssid=e-s5&at_topsearch=1
		//list.taobao.com/market/sportculture.htm?promoted_service4=4&cat=50010388&sort=coefp&isprepay=1&random=false&viewIndex=7&yp4p_page=0&commend=all&style=grid&ppath=20000:20578;22196:20642&isnew=&olu=yes&smc=1
	
		if(preg_match($shopMatch,$iid,$m)) {
			$iid = $m[2];
			$type = '1';
			$isSid = true;
		} elseif(preg_match($itemMatch,$iid,$m) || preg_match($itemMatch2,$iid,$m)) {
			$iid = $m[2];
			$type = '2';
		}elseif(preg_match($searchMatch,$iid,$m)) {
			Doo::loadClass('util/StringUtil');
			if(!is_utf8($m[2])) $m[2] = StringUtil::iconv_gb2312_utf8($m[2]);
			$iid = $m[2];
			$type = '3';
		} else {
			//re.taobao.com/search?keyword=%C8%FD%D0%C7&catid=1403&isinner=1&refpid=mm_14154427_0_0&refpos=&posid=3
			//如果带有pid=mm_122334_0_0替换成pid=mm_14154427_0_0
			$otherMatch = '/^(http:\/\/)?\w+\.taobao\.com\/[^>]*pid=mm_(\d+_\d+_\d+)&?([^&]*)[^>]*/i';
			if(preg_match($otherMatch,$iid,$m)) {
				$iid = str_replace($m[2],'14154427_0_0',$iid);
			} elseif(stristr($iid,'?')) {
				$iid .= '&pid=mm_14154427_0_0';
			} else {
				$iid .= '?pid=mm_14154427_0_0';
			}
			DooUriRouter::redirect($iid,true);
			exit;
		}
	}
	
	// 开放API
    Doo::loadClass('api/OpenApi');
	$taobaoke = OpenApi::loadApi('Taobaoke');
	if(isset($type) && $type == "1"){
		$taobaoShop = OpenApi::loadApi('TaobaoShop');
		$sid = $isSid?$iid:$taobaoShop->getSidByNick($iid);
		$to = $taobaoke->getClickUrlBySid($sid);
	
		// 推荐连接为空的时候，直接跳转
		if(empty($to)) {
			$to = sprintf(ITEM_TAOBAO_SHOP_URL,$sid);
		}
	} elseif($type=='3'){
		$to = $taobaoke->getListurl(trim(urldecode($iid)));
		$to = $to['taobaoke_item']['keyword_click_url'];
	} else {
			$to = $taobaoke->getClickUrlByIid($iid);
	
			// 推荐连接为空的时候，直接跳转
			if(empty($to)) {
				$to = ITEM_TAOBAO_URL . $iid;
			}
	}


	DooUriRouter::redirect($to,true);
	}
	
    public function index(){
		$this->view()->renderc('index', null);
    }
    
	public function allurl(){	
		Doo::loadCore('app/DooSiteMagic');
		DooSiteMagic::showAllUrl();	
	}
	
    public function debug(){
		Doo::loadCore('app/DooSiteMagic');
		DooSiteMagic::showDebug($this->params['filename']);
    }
	
	public function gen_sitemap_controller(){
		//This will replace the routes.conf.php file
		Doo::loadCore('app/DooSiteMagic');
		DooSiteMagic::buildSitemap(true);		
		DooSiteMagic::buildSite();
	}
	
	public function gen_sitemap(){
		//This will write a new file,  routes2.conf.php file
		Doo::loadCore('app/DooSiteMagic');
		DooSiteMagic::buildSitemap();		
	}
	
	public function gen_site(){
		Doo::loadCore('app/DooSiteMagic');
		DooSiteMagic::buildSite();
	}
	
    public function gen_model(){
        Doo::loadCore('db/DooModelGen');
        DooModelGen::gen_mysql();
    }

}
?>