<?php
/**
*  缓存处理函数
*/
require_once dirname(__FILE__) . '/Cache.class.php';

class CacheUtil{
	/**
	* 获得指定分类的所有子类
	*/
	static public function getCachedCatigories($catId,$cat=null) {
		$result = null;
		// 如果没有子类，不进行cache
		if($catId != null && ((isset($cat['is_parent']) && $cat['is_parent'] == 'true') || $cat == null)) {
			// 根据分类ID的长度来分文件夹
			$len = strlen($catId);
			if($len <= 3) {
				$len = 1;
			}else if($len <= 7) {
				$len = 2;
			}else if($catId < '50010000'){
				$len = 3;
			}else if($catId < '50013000'){
				$len = 4;
			}else if($catId < '50015000'){
				$len = 5;
			}else {
				$len = 6;
			}
			$Cache = new CacheFile('cat_' . $catId,'cat_'. $catId . '_','txt');
			$Cache->SetCacheTime(FILE_CACHE_TIME);//缓存时间
			$Cache->SetCacheDir(FILE_CACHE_PATH . '/cat/' . $len);

			$CacheFile= $Cache->Run();

			if($CacheFile) {
				$result = unserialize($Cache->readCacheFile2String());
			}else {
				$taobaoCats = new TaobaoCats();

				$result = $taobaoCats->getSubCats($catId);
				if($result == null || sizeof($result) < 1) {
					return unserialize($Cache->readCacheFile2String());
				}
				$Cache->SaveToCacheFile(serialize($result));
			}
		}
		return $result;
	}

	/**
	* 获得所有的子类
	*/
	static public function getAllCachedSubCats($catId,&$result,$cCat=null) {
		$cSubCat = self::getCachedCatigories($catId,$cCat);
		if($cSubCat != null && sizeof($cSubCat) > 0) {
			$result[] = $cSubCat;
			
			$tmp = null;
			$cats = $cSubCat['item_cats']['item_cat'];
			foreach($cats as $k => $v) {
				if(isset($v['is_parent']) && $v['is_parent'] == 'true') {
					$tmp = self::getAllCachedSubCats($v['cid'],$result,$v);
					
					if($tmp != null && sizeof($tmp) > 0) {
						$result[] = $tmp;
					}
				}
			}	
		}
		return $tmp;
	}

	/**
	* list page data cached
	*/
	static public function getCachedTaobaokeListData($searchParams,$taobaoke,$cacheTime = 1440,$file_prefix = "list_") {
		//just cache the first page
		if($searchParams['page_no'] != 1 || $searchParams['flag']) {
			$result = $taobaoke->getItemsByCatId($searchParams);
			return $result;
		}

		$searchStr = '';
		ksort($searchParams);
		foreach ($searchParams as $key => $val) {
			if ($key !='' && $val !='') {
				$searchStr .= $key.$val;
			}
		}
		
		// 根据分类ID的长度来分文件夹
		$len = strlen($searchParams['cid']);
		if($len <= 3) {
			$len = 1;
		}else if($len <= 7) {
			$len = 2;
		}else {
			$val = $searchParams['cid'] - 50000000;
			if($val < 0){
				$len = 0;
			}else{
				$len = round($val / 100);
			}
		}

		$Cache = new CacheFile($searchStr,$file_prefix,'txt');
		$Cache->SetCacheTime($cacheTime);//default 1h
		$Cache->SetCacheDir(FILE_CACHE_PATH . '/list/' . $len .'/' . $searchParams['cid'] . "/");

		$CacheFile= $Cache->Run();
		
		$result = null;
		if($CacheFile) {
			$result = unserialize(gzuncompress($Cache->readCacheFile2String()));
		}else {
			$result = $taobaoke->getItemsByCatId($searchParams);
			if($result == null || sizeof($result) < 1) {
				return unserialize(gzuncompress($Cache->readCacheFile2String()));
			}
			$Cache->SaveToCacheFile(gzcompress(serialize($result)));
		}
		
		return $result;
	}


	/**
	* list page data cached
	*/
	static public function getCachedSellerItems($taobao,$nick,$params,$cacheTime = 1440,$file_prefix = "",$compressed=true,$serialize=true) {
		if($nick == null || $nick == "") return null;
		if($params['page_no']>1 || $params['cid'] || $params['q']) {
			$result = $taobao->getSellerItems($nick,$params);
			return $result; 
		}
		
		$hashNick = md5($nick);
		$subDir = substr($hashNick,0,2);
			
		$Cache = new CacheFile('',$hashNick,'txt');
		$Cache->SetCacheTime($cacheTime);
		$Cache->SetCacheDir(FILE_CACHE_PATH . '/seller/'. $subDir . '/');
		$Cache->SetCacheFileCommpress($compressed);
		$Cache->SetCacheFileSerialize($serialize);
		
		$CacheFile= $Cache->Run();
		
		$result = null;
		if($CacheFile) {
			$result = $Cache->readCacheFile2String();
		}else {
			$result = $taobao->getSellerItems($nick,$params);
			if($result == null || sizeof($result) < 1) {
				return $Cache->readCacheFile2String();
			}
			$Cache->SaveToCacheFile($result);
		}
		
		return $result;
	}

	/**
	* list page data cached
	*/
	static public function getCachedProduct($iid,$taobaoke,$file_prefix = "",$cacheTime = 2880,$compressed=true,$serialize=true) {
		if($iid == null || $iid == "") return null;
		$subDir = substr($iid,0,2);
		$subDir2 = substr($iid,2,2);
		
		$Cache = new CacheFile('',$file_prefix . $iid,'txt');
		$Cache->SetCacheTime($cacheTime);//default 1h
		$Cache->SetCacheDir(FILE_CACHE_PATH . '/prod/'. $subDir . '/'. $subDir2 . '/');
		$Cache->SetCacheFileCommpress($compressed);
		$Cache->SetCacheFileSerialize($serialize);
		
		$CacheFile= $Cache->Run();
		
		$result = null;
		if($CacheFile) {
			$result = $Cache->readCacheFile2String();
		}else {
			$result = $taobaoke->getItemDetail($iid);
			if($result == null || sizeof($result) < 1) {
				return $Cache->readCacheFile2String();
			}
			$Cache->SaveToCacheFile($result);
		}
		
		return $result;
	}


	/**
	* 数据缓存
	* $obj 查询对象
	* $func 查询对象函数
	* $name 缓存文件名（hash前）
	* $prefix 文件前缀
	* $subdir 缓存文件路径
	* $params 查询对象函数的参数
	* $cachetime 缓存时间（分）
	* $suffix 文件后缀
	*/
	static public function getCachedData($obj,$func,$name,$prefix,$subdir='',$params=null,$cachetime=2880,$compressed=true,$serialize=true,$suffix='txt'){
		$Cache = new CacheFile($name,$prefix,$suffix);
		$Cache->SetCacheTime($cachetime);
		$Cache->SetCacheFileCommpress($compressed);
		$Cache->SetCacheDir(FILE_CACHE_PATH . $subdir);
		$Cache->SetCacheFileSerialize($serialize);
		
		$CacheFile= $Cache->Run();
		
		$result = null;
		if($CacheFile) {
			$result = $Cache->readCacheFile2String();
		}else {
			if($params) {
				$result = $obj->$func($params);
			}else {
				$result = $obj->$func();
			}
			if($result == null || sizeof($result) < 1) {
				return $Cache->readCacheFile2String();
			}
			$Cache->SaveToCacheFile($result);
		}
		
		return $result;
	}
	
	
	/**
	* 淘宝分类属性
	*/
	static public function getCachedCatProps($cat,$params,$propname,$cachetime=14400,$compressed=true){
		return self::getCachedData($cat,'getItemProps',$propname,$propname.$params['cid'].'_','/cat_prop/'.$propname.'/',$params,$cachetime,$compressed);
	}

	/**
	* 淘宝画报
	*/
	static public function getCachedTaobaoHuabaoChannels($huabao,$cachetime=2880){
		return self::getCachedData($huabao,'getChannels','huabao','channels_','/huabao/',null,$cachetime);
	}

	/**
	* 淘宝画报
	*/
	static public function getCachedTaobaoHuabaoPosters($huabao,$params,$cachetime=120){
		return self::getCachedData($huabao,'getPosters','huabao','poster'.$params['channel_id'].'_','/huabao/',$params,$cachetime);
	}

	/**
	* 淘宝画报
	*/
	static public function getCachedTaobaoHuabaoSPosters($huabao,$params,$cachetime=120){
		$p = $params;
		sort($p);
		$unique = implode('',$p);
		return self::getCachedData($huabao,'getSpecialPosters','huabao','sposter'.$unique .'_','/huabao/',$params,$cachetime);
	}

	/**
	* 店铺信息缓存
	*/
	static public function getCachedShopInfo($taobao,$nick,$cacheTime = 4320,$file_prefix = "") {
		if($nick == null || $nick == "") return null;
		$hashNick = md5($nick);
		$subDir = substr($hashNick,0,2);

		return self::getCachedData($taobao,'getShopInfo',$hashNick,'shop_','/seller/info/'. $subDir . '/',$nick,$cacheTime);
	}
	
	/**
	 * 产品信息缓存
	 */
	static public function getCachedProductById($product,$id,$cacheTime = 4320,$file_prefix="") {
		if($id == null || $id == "") return null;
		$subDir = substr($id,0,2);
		$subDir2 = substr($id,2,2);
		
		return self::getCachedData($product,'getProductById',$id,'p_','/product/'. $subDir . '/'. $subDir2 . '/',$id,$cacheTime);
	}
	
	/**
	 * 缓存列表首页数据
	 */
	static public function getCachedSearchItems($taobaoItem,$searchParams,$cacheTime=60,$file_prefix) {
		$searchStr = self::formatSearchParams($searchParams);
		
		return self::getCachedData($taobaoItem,'searchItems',$searchStr,'cat_','/cat/'. $searchParams['cid'] . '/',$searchParams,$cacheTime);
	}
	
	/**
	 * 将参数格式化
	 */
	static public function formatSearchParams($params, $unsetKeys=null) {
		$searchStr = '';
		ksort($params);
		foreach ($params as $key => $val) {
			if(is_array($unsetKeys) && in_array($key, $unsetKeys)) continue;
			if ($key !='' && $val !='') {
				$searchStr .= $key.$val;
			}
		}
		
		return $searchStr;
	}
}
?>