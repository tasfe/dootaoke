<?php
// 常用数据

# Database Settings
define("DB_HOST","localhost");
define("DB_NAME","zmd");
define("DB_USER","root");
define("DB_PWD","root");
//网站根目录
define('URLROOT','/');
//表前缀、表名
define('TABLE_PREFIX','');
define('TABLE_CATIGORY','category');
define('TABLE_TMP_CATIGORY','tmp_category');
define('TABLE_SUGGEST','suggest');

// 错误次数
define('MAX_ERROR_NUM', 1);
// 如果出错，跳转到错误画面
define('ERROR_PAGE', "error.php");
// 首页
define('INDEX_PAGE', "index.php");
// 商品不存在时
define('PRODUCT_NOT_EXISTED', "noproduct.php");
// 数据缓存路径
define('FILE_CACHE_PATH','cache');
// 缓存时间
define('FILE_CACHE_TIME',11520);//缓存24*60分钟*8 天
// 图片不存在的图片
define('TAOBAO_NO_PIC','images/nopic.gif');
	
//所有分类目录及文件
define('FILE_ALL_CATS',FILE_CACHE_PATH . '/cat/cat_all_00ab7659554f9e2eed79523db2a8b26d.txt');
define('FILE_ALL_CATS_SQL',FILE_CACHE_PATH . '/cat/all_sql.sql');

// 产品详情画面显示商品数
define('DETAIL_PRODUCTS_NUM', 8);


// 淘宝风云榜url
define('TAOBAO_FENGYUN_URL','http://pindao.huoban.taobao.com/channel/channelfy.htm?pid=mm_14154427_2202002_8899968&eventid=101325');
	
// 系统首页链接
define('HTTP_SERVER','http://www.95daohang.cn');
//论坛地址
define('HTTP_SERVER_BBS',HTTP_SERVER . '/bbs');

// 检索结果每页最大显示件数
define('MAX_PRODUCTS_PER_PAGE',39);
// 每页最大显示件数
define('MAX_PRODUCTS_PER_PAGE_LIST',40);

// 关键字
define("KEYWORDS", ",网上购物,购物导航");
define("TITLE_KEYWORDS","热卖排行榜");

// 搜索按钮名称
define("TAOBAO_SEARCH_TXT",'搜一下');
// 画面标题后缀
define("SITE_NAME",'_同城购物|比较购物|购物导航网');
define('CUSTOM_META_DESCRIPTION', '%s的价格:%s,商品所在地:%s,你可以找到它的商品详情,顾客评价,图片,价格,参数,运费信息等相关信息,进行同城购物,比较购物。找%s的价格,图片,行情等尽在购物导航网');

//省份
define("CHINA_PROVINCES",'山东,山西,河南,河北,湖南,湖北,广东,广西,黑龙江,辽宁,浙江,安徽,江苏,福建,甘肃,江西,云南,贵州,四川,青海,陕西,吉林,宁夏,海南,西藏,内蒙古,新疆,台湾');

//排序
define("NAV_SORT_KEY_VALUE_ARRAY",'4=volume:desc,5=volume:asc,10=price:desc,11=price:asc');
/*
1:总支出佣金从高到底
2:总支出佣金从低到高
3:信用等级从高到低
4:成交量成高到低
5:成交量从低到高
6:商品下架时间从高到底
7:商品下架时间从低到高
8:佣金比率从高到底
9:佣金比率从低到高
10:价格从高到低
11:价格从低到高
91:打折商品
92:免邮费
*/
define("NAV_SORT_ARRAY",'4=热销商品,91=打折商品,92=免邮费商品,93=秒杀商品,1=最多推荐,10=价格高到低,11=价格低到高,3=信誉高到低,6=新上架');
define("NAV_SORT_ID_ARRAY",'4,91,92,93,1,10,11,3,6');
define("KEYWORD_SORT_ARRAY",'91=打折,92=免邮,93=秒杀');

// 页面头部链接 分类ID=分类名
define("PAGE_HEADER_MAIN_ARRAY",'30=男装,16=女装,14=数码,21=生活,1512=手机,50011740=男鞋,50006843=女鞋,50010388=运动鞋,50006842=箱包,1801=美容');
define("PAGE_HEADER_SUB_ARRAY",',29=宠物,50011397=珠宝,50008163=床品,50012472=保健,50002766=零食,50007216=鲜花,50008090=数码配件,50007218=办公,25=玩具,1625=内衣,50012100=生活电器');
define("PAGE_HEADER_CATIDS_ARRAY",'30,16,14,21,1512,50011740,50006843,29,50012472,50007216,50008090,50008163,50010388,1801,50006842,50002766,50007218,25,1625,50011397,50012100');

// 常用的城市
define("CHINA_BIG_CITIES","北京,上海,深圳,广州,杭州,长沙,重庆,南京");
define("CHINA_BIG_PROVINCES","浙江,广东,湖南,山东,四川,湖北,江苏,香港");

  // 特殊符号
define('SPECIAL_CHARS_1','/(\'|┫|╭|◣|█|┣|㊣|◢|◤|⊙|◎|◆|●|《|》|╬|═|→|←|↑|↓|☆|★|□|■|△|▲|〓|※|◤|◥|☉|♀|♂|『|』|〖|〗|【|】|ˇ)/');
define('SPECIAL_CHARS_2','/(<|>|[|])/');
// RSS类型
define("RSS_FORMAT","2.0,RSS2.0,1.0,RSS1.0,0.91,RSS0.91,PIE0.1,MBOX,OPML,ATOM,ATOM1.0,ATOM0.3,HTML,JS,JAVASCRIPT");

// seo控制
define('SEO_HREF_LINK',true);
?>