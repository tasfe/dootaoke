<?php
/**
*  字符串处理函数
*/

class CommonUtil {
 /**
   * 获得当前页码
   */
 function getPageNo($pageNo,$maxpage=100){
 	if(!is_numeric($pageNo)){
 		return 1;
 	}
 	
 	if(intval($pageNo) > $maxpage){
 		return 1;
 	}
 	
 	return $pageNo;
 }
}
?>