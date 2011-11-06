<?php
/*---------常量定义 ---------------------*/
// 网站名称
define('SITE_NAME','美容护肤用品商城_再美点网');
// 默认关键字
define('DEFAULT_SITE_KEYWORDS','美容护肤,正品商城,化妆品,再美点网');
// 默认描叙
define('DEFAULT_SITE_DESC','美容护肤用品商城,构建最诚信的化妆品导购平台,所有商品假一赔三,7天无理由退换货。');

// 域名网址
define('HTTP_SERVER','http://www.zmeidian.com');

// 域名网址
define('TITLE_SPLIT','_');

// API缓存目录
define('FILE_CACHE_PATH','protected/cache/api');

// 特殊符号
define('SPECIAL_CHARS_1','/(\'|┫|╭|◣|█|┣|㊣|◢|◤|⊙|◎|◆|●|《|》|╬|═|→|←|↑|↓|☆|★|□|■|△|▲|〓|※|◤|◥|☉|♀|♂|『|』|〖|〗|【|】|ˇ)/');
define('SPECIAL_CHARS_2','/(<|>|[|])/');

// 编码
define('CHARSET','utf-8');

// 全局参数
$_G = array();
$_G['taobao'] = array(
'default' => array('cat'=>1801,'order'=>2),
'top_cats' => array('1801'=>'美容护肤','50010788'=>'彩妆香水'),// 默认淘宝根分类
'orders' => array('1' => array('key'=>'delist_time:desc','name'=>'最新上架'),'2' => array('key'=>'popularity:desc','name'=>'人气排行'),
	'3' => array('key'=>'volume:desc','name'=>'成交量高低'),'4' => array('key'=>'price:asc','name'=>'价格从低到高'),'5' => array('key'=>'price:desc','name'=>'价格从高到低'),),	
'price' => array('format'=>'/\d/','min'=>0,'max'=>100000000),
'page_size' => array('format'=>'/\d/','mint'=>1,'max'=>40),
'props' => array('format'=>'/^\d+:\d+(;\d+:\d+)*$/i'),
'promoted_service' => array('2'=>'假一赔三','4'=>'7天无理由退换货'),
);


/*---------常量定义 ---------------------*/
//缓存清理时间
define('AUTOCLEAR_CACHETIME',12*60*60);//自动清除缓存时间
define('AUTOCLEAR_NOTCLEAR_PATHS','cat,cat_prop');//自动清除缓存时间
?>