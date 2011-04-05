<?php
	
class FileUtil {
	/**
	*  文件处理函数
	*/
	static public function readFile2String($filePath) {
		if(!file_exists($filePath)) {
			return null;
		}
		
		$result = null;
		$fp = fopen($filePath,"r");
		if(!$fp){
		    return null;
		}else{
		 	 while(!feof($fp)){
		 	 	$result .= fgets($fp , 1024);
		 	 }
		 }
		fclose($fp);
		
		return $result;
	}

	/**
	*  文件处理函数
	*/
	static public function readFile2Array($filePath) {
		if(!file_exists($filePath)) {
			return null;
		}
		
		$result = null;
		$fp = fopen($filePath,"r");
		if(!$fp){
		    return null;
		}else{
		 	 while(!feof($fp)){
		 	 	$result[] = fgets($fp , 512);
		 	 }
		 }
		fclose($fp);
		
		return $result;
	}

	/**
	* 写字符串到文件
	*/
	static public function writeString2File($Content,$filePath) {
		$result = '-1';
		$fp = fopen($filePath,"wb");
		if($fp) {
			if(!fwrite($fp,$Content)){
				//echo('Write '.$filePath.' Fail');
				$result = '-1';
			}else {
				//echo('Write File success');
				$result = '1';
			}
			fclose($fp);
		}
		
		return $result;
	}

	/**
	* 读取文件转成分类数组
	*/
	static public function readCatsFile2CatsArray() {
		$catsStr = self::readFile2String(FILE_ALL_CATS);
		if($catsStr == null || strlen($catsStr) < 1) {
			return null;
		}
		
		return unserialize($catsStr);
	}
	/**
	* 写分类数组到文件
	*/
	static public function writeCatsArray2CatsFile($cats) {
		return self::writeString2File(serialize($cats),FILE_ALL_CATS);
	}

	/**
	* 读取远程内容
	*/
	static public function openUrlFile($url) {
	      $result = file_get_contents($url);
		  return $result;
	}
}
?>