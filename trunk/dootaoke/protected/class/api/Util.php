<?php
require_once 'Snoopy.class.php';

class Util {
	static private $snoopy = NULL;
	
	/**
	 * 实例化Snoopy
	 */
	static public function instanceSnoopy() {
		if (self::$snoopy == NULL) {
			self::$snoopy = new Snoopy();
		}
		self::$snoopy->rawheaders["Accept-Encoding"] = "gzip, deflate";
	}
	
	/**
	 * 生成签名
	 * @param $paramArr：api参数数组
	 * @return $sign
	 */
	static public function createSign ($paramArr) {
		$sign = APP_SECRET;
		ksort($paramArr);
		foreach ($paramArr as $key => $val) {
			if ($key !='' && $val !='') {
				$sign .= $key.$val;
			}
		}
		$sign = strtoupper(md5($sign));
		return $sign;
	}
	
	/**
	 * 生成字符串参数 
	 * @param $paramArr：api参数数组
	 * @return $strParam
	 */
	static public function createStrParam ($paramArr) {
		$strParam = '';
		foreach ($paramArr as $key => $val) {
			if ($key != '' && $val !='') {
				$strParam .= $key.'='.urlencode($val).'&';
			}
		}
		return $strParam;
	}
	
	/** 
	* 删除数组中的一个键值
	*/
	static public function deleteKeyValueOfArray($params,$k) {
		if(gettype($k) == 'array') {
			foreach ($k as $key => $val) {
				unset($params[$val]);
			}
		}else {
			unset($params[$k]);
		}
		return $params;
	}
	
	/**
	 * 以GET方式访问api服务
	 * @param $paramArr：api参数数组
	 * @return $result
	 */
	static public function getResult($paramArr) {
		self::instanceSnoopy();
		//组织参数
		$sign = self::createSign($paramArr);
		$strParam = self::createStrParam($paramArr);
		$strParam .= 'sign='.$sign;
		//访问服务
		self::$snoopy->fetch(API_URL.'?'.$strParam);
		$result = self::gzdecode(self::$snoopy->results);
		//返回结果
		return $result;
	}
	
	/**
	 * 以POST方式访问api服务
	 * @param $paramArr：api参数数组
	 * @return $result
	 */
	static public function postResult($paramArr) {
		self::instanceSnoopy();
		//组织参数，Snoopy类在执行submit函数时，它自动会将参数做urlencode编码，所以这里没有像以get方式访问服务那样对参数数组做urlencode编码
		$sign = self::createSign($paramArr);
		$paramArr['sign'] = $sign;
		//访问服务
		self::$snoopy->submit(API_URL, $paramArr);
		$result = self::gzdecode(self::$snoopy->results);
		//返回结果
		return $result;
	}
	
	/**
	 * 以POST方式访问api服务，带图片
	 * @param $paramArr：api参数数组
	 * @param $imageArr：图片的服务器端地址，如array('image' => '/tmp/cs.jpg')形式
	 * @return $result
	 */
	static public function postImageResult($paramArr, $imageArr) {
		self::instanceSnoopy();
		//组织参数
		$sign = self::createSign($paramArr);
		$paramArr['sign'] = $sign;
		//访问服务
		self::$snoopy->_submit_type = "multipart/form-data";
		self::$snoopy->submit(API_URL, $paramArr, $imageArr);
		$result = self::$snoopy->results;
		//返回结果
		return $result;
	}
	
	/**
	 * 解析xml
	 */
	static public function getXmlData ($strXml) {
		$pos = strpos($strXml, 'xml');
		if ($pos) {
			$xmlCode=simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
			$arrayCode=self::get_object_vars_final($xmlCode);
			return $arrayCode ;
		} else {
			return '';
		}
	}
	
	static private function get_object_vars_final($obj){
		if(is_object($obj)){
			$obj=get_object_vars($obj);
		}
		
		if(is_array($obj)){
			foreach ($obj as $key=>$value){
				$obj[$key]=self::get_object_vars_final($value);
			}
		}
		return $obj;
	}
	
	static private function gzdecode($data) {
	  $len = strlen($data);
	  if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
	   return $data;  // Not GZIP format (See RFC 1952)
	  }
	  $method = ord(substr($data,2,1));  // Compression method
	  $flags  = ord(substr($data,3,1));  // Flags
	  if ($flags & 31 != $flags) {
	   // Reserved bits are set -- NOT ALLOWED by RFC 1952
	   return null;
	  }
	  // NOTE: $mtime may be negative (PHP integer limitations)
	  $mtime = unpack("V", substr($data,4,4));
	  $mtime = $mtime[1];
	  $xfl  = substr($data,8,1);
	  $os    = substr($data,8,1);
	  $headerlen = 10;
	  $extralen  = 0;
	  $extra    = "";
	  if ($flags & 4) {
	   // 2-byte length prefixed EXTRA data in header
	   if ($len - $headerlen - 2 < 8) {
	     return false;    // Invalid format
	   }
	   $extralen = unpack("v",substr($data,8,2));
	   $extralen = $extralen[1];
	   if ($len - $headerlen - 2 - $extralen < 8) {
	     return false;    // Invalid format
	   }
	   $extra = substr($data,10,$extralen);
	   $headerlen += 2 + $extralen;
	  }
	  
	  $filenamelen = 0;
	  $filename = "";
	  if ($flags & 8) {
	   // C-style string file NAME data in header
	   if ($len - $headerlen - 1 < 8) {
	     return false;    // Invalid format
	   }
	   $filenamelen = strpos(substr($data,8+$extralen),chr(0));
	   if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
	     return false;    // Invalid format
	   }
	   $filename = substr($data,$headerlen,$filenamelen);
	   $headerlen += $filenamelen + 1;
	  }
	  
	  $commentlen = 0;
	  $comment = "";
	  if ($flags & 16) {
	   // C-style string COMMENT data in header
	   if ($len - $headerlen - 1 < 8) {
	     return false;    // Invalid format
	   }
	   $commentlen = strpos(substr($data,8+$extralen+$filenamelen),chr(0));
	   if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
	     return false;    // Invalid header format
	   }
	   $comment = substr($data,$headerlen,$commentlen);
	   $headerlen += $commentlen + 1;
	  }
	  
	  $headercrc = "";
	  if ($flags & 1) {
	   // 2-bytes (lowest order) of CRC32 on header present
	   if ($len - $headerlen - 2 < 8) {
	     return false;    // Invalid format
	   }
	   $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
	   $headercrc = unpack("v", substr($data,$headerlen,2));
	   $headercrc = $headercrc[1];
	   if ($headercrc != $calccrc) {
	     return false;    // Bad header CRC
	   }
	   $headerlen += 2;
	  }
	  
	  // GZIP FOOTER - These be negative due to PHP's limitations
	  $datacrc = unpack("V",substr($data,-8,4));
	  $datacrc = $datacrc[1];
	  $isize = unpack("V",substr($data,-4));
	  $isize = $isize[1];
	  
	  // Perform the decompression:
	  $bodylen = $len-$headerlen-8;
	  if ($bodylen < 1) {
	   // This should never happen - IMPLEMENTATION BUG!
	   return null;
	  }
	  $body = substr($data,$headerlen,$bodylen);
	  $data = "";
	  if ($bodylen > 0) {
	   switch ($method) {
	     case 8:
	       // Currently the only supported compression method:
	       $data = gzinflate($body);
	       break;
	     default:
	       // Unknown compression method
	       return false;
	   }
	  } else {
	   // I'm not sure if zero-byte body content is allowed.
	   // Allow it for now...  Do nothing...
	  }
	  
	  // Verifiy decompressed size and CRC32:
	  // NOTE: This may fail with large data sizes depending on how
	  //      PHP's integer limitations affect strlen() since $isize
	  //      may be negative for large sizes.
	  if ($isize != strlen($data) || crc32($data) != $datacrc) {
	   // Bad format!  Length or CRC doesn't match!
	   return false;
	  }
	  return $data;
	}
}
?>