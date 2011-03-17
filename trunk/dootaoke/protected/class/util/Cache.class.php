<?php
/*****************************************
* 原文件名：Cache.php
* 文件功能：缓存模块
* 文件编写：xuefengal@sohu.com
*****************************************/
define("DEBUG",false);
class CacheFile {
	/** 缓存目录 **/
	var $CacheDir = './c';
	/** 缓存的文件 **/
	var $CacheFile = '';
	/** 文件缓存时间(分钟) **/
	var $CacheTime = 0;
	/** 文件是否已缓存 **/
	var $CacheFound = False;
	/** 错误及调试信息 **/
	var $DebugMsg = NULL;
	/** 路径信息 **/
	var $VistUrl = '';
	/** 文件类型 **/
	var $CacheFileType = 'html';
	/**文件前缀*/
	var $CacheFilePrefix = '';
	/**压缩文件存储*/
	var $CacheFileCommpress = false;
	/**序列化数据*/
	var $CacheFileSerialize = false;

	function CacheFile($VistUrl,$CacheFilePrefix = '',$CacheFileType = 'html',$CacheTime = 0,$CacheFileCommpress=false,$CacheFileSerialize=false) {
		$this->CacheTime = $CacheTime;
		$this->VistUrl = $VistUrl;
		$this->CacheFileType = $CacheFileType;
		$this->CacheFilePrefix = $CacheFilePrefix;
		$this->CacheFileCommpress = $CacheFileCommpress;
		$this->CacheFileSerialize=$CacheFileSerialize;
	}

	function Run() {
		/** 缓存时间大于0,检测缓存文件的修改时间,在缓存时间内为缓存文件名,超过缓存时间为False，
		小于等于0,返回false,并清理已缓存的文件
		**/
		$this->SetCacheFile($this->VistUrl,$this->CacheFileType);
		return $this->CacheTime ? $this->CheckCacheFile() : $this->CleanCacheFile();
	}

	function SetCacheFile($VistUrl,$CacheFileType = 'html') {
		if(empty($VistUrl)) {
			/** 默认为index.html **/
			$this->CacheFile = 'index';
		}else {
			/** 传递参数为$_POST时 **/
			$this->CacheFile = is_array($VistUrl) ? implode('.',$VistUrl) : $VistUrl;
		}
		$this->CacheFile = $this->CacheDir.'/' . $this->CacheFilePrefix . md5($this->CacheFile);
		$this->CacheFile .= '.'.$CacheFileType;
	}

	function SetCacheTime($t = 3600) {
		$this->CacheTime = $t;
	}
	
	function SetCacheFileCommpress($commpress = false) {
		$this->CacheFileCommpress = $commpress;
	}
	
	function SetCacheFileSerialize($serialize = false) {
		$this->CacheFileSerialize = $serialize;
	}
	
	function SetCacheFilePrefix($CacheFilePrefix = '') {
		$this->CacheFilePrefix = $CacheFilePrefix;
	}
	
	function SetCacheDir($directory) {
		$this->CacheDir = $directory;
	}

	function CheckCacheFile() {
		if(!$this->CacheTime || !file_exists($this->CacheFile)) {
			return False;
		}
		/** 比较文件的建立/修改日期和当前日期的时间差 **/
		$GetTime=(Time()-Filemtime($this->CacheFile))/(60*1);
		/** Filemtime函数有缓存,注意清理 **/
		Clearstatcache();
		$this->Debug('Time Limit '.($GetTime*60).'/'.($this->CacheTime*60));
		$this->CacheFound = ($GetTime < $this->CacheTime) ? $this->CacheFile : False;
		return $this->CacheFound;
	}

	function SaveToCacheFile($Content) {
		if(!$this->CacheTime) {
			return False;
		}

		/** 检测缓存目录是否存在 **/
		if(true === $this->CheckCacheDir()) {
			$CacheFile = $this->CacheFile;
			$CacheFile = str_replace('//','/',$CacheFile);
			$fp = fopen($CacheFile,"wb");
			if(!$fp) {
				$this->Debug('Open File '.$CacheFile.' Fail');
			}else {
				if($this->CacheFileSerialize) $Content = serialize($Content);
				if($this->CacheFileCommpress) $Content = gzcompress($Content);
				if(!fwrite($fp,$Content)){
					$this->Debug('Write '.$CacheFile.' Fail');
				}else {
					$this->Debug('Cached File');
				}
				fclose($fp);
			}
		}else {
			/** 缓存目录不存在，或不能建立目录 **/
			$this->Debug('Cache Folder '.$this->CacheDir.' Not Found');
		}
	}

	function CheckCacheDir() {
		if(file_exists($this->CacheDir)) { 
			return true; 
		}
		/** 保存当前工作目录 **/
		$Location = getcwd();
		/** 把路径划分成单个目录 **/
		$Dir = split("/", $this->CacheDir);

		/** 循环建立目录 **/
		$CatchErr = True;
		for ($i=0; $i < count($Dir); $i++){
			if (!file_exists($Dir[$i]) && $Dir[$i] != '' && $Dir[$i] != null){
				/** 建立目录失败会返回False 返回建立最后一个目录的返回值 **/
				$CatchErr = mkdir($Dir[$i],0777);
			}
			chdir($Dir[$i]);
		}
		/** 建立完成后要切换到原目录 **/
		chdir($Location);
		if(!$CatchErr) {
		$this->Debug('Create Folder '.$this->CacheDir.' Fail');
		}

		return $CatchErr;
	}
	
	function readCacheFile2String() {
		$result = null;
		if(true === $this->CheckCacheDir()) {
			$CacheFile = $this->CacheFile;
			$CacheFile = str_replace('//','/',$CacheFile);
			$fp = fopen($CacheFile,"r");
			if(!$fp) {
				$this->Debug('Open File '.$CacheFile.' Fail');
			}else {
				 if(!$fp){
				    return null;
				 }else{
				 	 while(!feof($fp)){
				 	 	$result .= fgets($fp , 1024);
				 	 }
				 }
				fclose($fp);
			}
		}
		
		if($this->CacheFileCommpress) $result = gzuncompress($result);
		if($this->CacheFileSerialize) $result = unserialize($result);
		
		return $result;
	}

	function CleanCacheFile() {
		if(file_exists($this->CacheFile)) {
			chmod($this->CacheFile,777);
			unlink($this->CacheFile);
		}
		/** 置没有缓存文件 **/
		$this->CacheFound = False;
		return $this->CacheFound;
	}

	function Debug($msg='') {
		if(DEBUG) {
			$this->DebugMsg[] = '[Cache]'.$msg;
		}
	}

	function GetError() {
		return empty($this->DebugMsg) ? '' : "\n".implode("\n",$this->DebugMsg);
	}

}/* end of class */


/** test

$VistUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

$Cache = new Cache($VistUrl);

$Cache->SetCacheTime(60*24);//缓存24小时

$CacheFile= $Cache->Run();//有缓存文件时返回缓存文件名,可以用header跳转或include包含

if($CacheFile) {
	include $CacheFile;
}else {
	// 正常处理
	ob_clean();
	ob_start();
	//处理代码
	;
	$Buffer = ob_get_clean();
	//输出
	echo($Buffer);
	//缓存文件
	$Cache->SaveToCacheFile($Buffer);
}

//调试信息
echo $Cache->GetError();

**/

?>