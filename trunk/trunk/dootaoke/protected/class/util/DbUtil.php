<?php
/**
*  文件处理函数
*/
require_once 'Database.class.php';

class DbUtil{
	/**
	* 获得指定分类数据
	*/
	static public function selectCatByCid($catId,$db=null) {
		if($catId == null || $catId == '') return null;

		$sql = "select * from " .TABLE_CATIGORY." where cid='" . $catId . "'";
		if($db == null) {
			$db = new Database;
		}
		$db->query($sql);
		$db->close();
		return $db->getRecord();
	}

	/*
	* 获得所有子类数据
	*/
	static public function selectAllSubCatsById($pId,$db=null) {
		if($pId == null || $pId == '') return null;

		$sql = "select * from " .TABLE_CATIGORY." where parent_cid='" . $pId . "'";
		if($db == null) {
			$db = new Database;
		}
		$db->query($sql);
		$db->close();
		return $db->getRecord();
	}

	/*
	* 获得所有子类数据
	*/
	static public function insertSuggest($datas,$db=null) {
		$selectsql = sprintf("select count(*) as num from " . TABLE_SUGGEST . " where ip='%s' and TIME_TO_SEC(now()) - TIME_TO_SEC(create_at) < 3600",$datas['ip']);
		
		if($db == null) {
			$db = new Database;
		}
		$db->query($selectsql);
		$result = $db->getRecord();
		
		if(isset($result['num']) && $result['num'] > 0) {
			$db->close();
			return 'existed';
		}
		
		$sql = sprintf("insert into " .TABLE_SUGGEST. " (suggest,author,ip,create_at) values ('%s','%s','%s',now())",mysql_real_escape_string($datas['content']),mysql_real_escape_string($datas['author']),$datas['ip']);
		$result = $db->query($sql);
		$db->close();
		return $result;
	}

	/**
	* 获得指定分类的上层分类
	*/
	static public function getParentCatsByCid($catId) {
		$db = new Database;
		$cat = selectCatByCid($catId,$db);

		if($cat == null) return null;
		$result[] = $cat;
		$parent_cid = $cat['parent_cid'];
		$cid = $cat['cid'];
		while($cid != '0' && isset($cid) && $cid != null) {
			$tmp = selectCatByCid($parent_cid,$db);
			$result[] = $tmp;
			$cid = $tmp['cid'];
			$parent_cid = $tmp['parent_cid'];
		}
		
		return $result;
	}
}
?>