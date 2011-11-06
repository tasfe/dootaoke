<?php
// 常用数据
define('API_URL', 'http://gw.api.taobao.com/router/rest');

//define('APP_KEY', '');
//define('APP_SECRET', '');
//define('NICK', '');

$apps = array(
array('APP_KEY'=>'12259852','APP_SECRET'=>'9b395a23d02d908ad1f4fb0e9d987945','APP_NUM'=>'500'),
array('APP_KEY'=>'12177984','APP_SECRET'=>'daa0bc98169de3d75eab1b0bd0a33861','APP_NUM'=>'500'),
array('APP_KEY'=>'12009868','APP_SECRET'=>'268aee99b240dd97a2decee14c72076b','APP_NUM'=>'500'),
array('APP_KEY'=>'12009226','APP_SECRET'=>'6eb1ebd74805155a8dddf1a43effe321','APP_NUM'=>'500'),
);

/**
 * 
 * 根据频率随机取APP_key
 */
function get_rand_app($apps){
    foreach ($apps as $val) {
    	$num = $val['APP_NUM'];
        $x = $num/100;
        for ($i=0;$i<$x;$i++){
            $applist[] = $val['APP_KEY'];
        }
    }
    $app_key=$applist[array_rand($applist)];
    foreach($apps as $val){
    	if($app_key==$val['APP_KEY']){
    		return $val;
    	}
    }
}

$app=get_rand_app($apps);
define('APP_KEY', $app['APP_KEY']);
define('APP_SECRET', $app['APP_SECRET']);
define('NICK', 'hutai123');
define('TAO_OUTER_CODE', 'zmd');

// 淘宝商品详情URL
define('ITEM_TAOBAO_URL', "http://item.taobao.com/item.htm?id=");
define('ITEM_TAOBAO_SHOP_URL', "http://shop%s.taobao.com/");

// 淘宝客
define('TAOBAOKE_ITEMS_GET', 'taobao.taobaoke.items.get');
define('TAOBAOKE_ITEMS_CONVERT', 'taobao.taobaoke.items.convert');
define('TAOBAOKE_SHOPS_CONVERT', 'taobao.taobaoke.shops.convert');
define('TAOBAOKE_ITEMS_DETAIL_GET','taobao.taobaoke.items.detail.get');
define('TAOBAOKE_LISTURL_GET','taobao.taobaoke.listurl.get');
define('TAOBAOKE_SHOPS_GET','taobao.taobaoke.shops.get');

// 淘宝商品
define('TAOBAO_ITEM_GET', 'taobao.item.get');
define('TAOBAO_ITEMS_GET', 'taobao.items.get');

// 搜索商品信息
define('TAOBAO_ITEMS_SEARCH', 'taobao.items.search');

//获取后台供卖家发布商品的标准商品类目 类目属性API
define('TAOBAO_ITEMCATS_GET', 'taobao.itemcats.get');
define('TAOBAO_ITEMCATS_AUTHORIZE_GET', 'taobao.itemcats.authorize.get');
define('TAOBAO_ITEMPROPS_GET', 'taobao.itemprops.get');
define('TAOBAO_ITEMPROPVALUES_GET', 'taobao.itempropvalues.get');

// 淘宝商店
define('TAOBAO_SHOP_GET', 'taobao.shop.get');

// 淘宝用户
define('TAOBAO_USER_GET', 'taobao.user.get');

// 淘宝画报
define('TAOBAO_HUABAO_CHANNEL_GET', 'taobao.huabao.channel.get');
define('TAOBAO_HUABAO_CHANNELS_GET', 'taobao.huabao.channels.get');
define('TAOBAO_HUABAO_POSTER_GET', 'taobao.huabao.poster.get');
define('TAOBAO_HUABAO_POSTERS_GET', 'taobao.huabao.posters.get');
define('TAOBAO_HUABAO_SPECIALPOSTERS_GET', 'taobao.huabao.specialposters.get');

// 淘宝产品
define('TAOBAO_PRODUCT_ADD', 'taobao.product.add');//上传一个产品，不包括产品非主图和属性图片
define('TAOBAO_PRODUCT_GET', 'taobao.product.get');//获取一个产品的信息
define('TAOBAO_PRODUCT_IMG_DELETE', 'taobao.product.img.delete');//删除产品非主图
define('TAOBAO_PRODUCT_IMG_UPLOAD', 'taobao.product.img.upload');//上传单张产品非主图，如果需要传多张，可调多次
define('TAOBAO_PRODUCT_PROPIMG_DELETE', 'taobao.product.propimg.delete');//删除产品属性图
define('TAOBAO_PRODUCT_PROPIMG_UPLOAD', 'taobao.product.propimg.upload');//上传单张产品属性图片，如果需要传多张，可调多次
define('TAOBAO_PRODUCT_UPDATE', 'taobao.product.update');//修改一个产品，可以修改主图，不能修改子图片
define('TAOBAO_PRODUCTS_GET', 'taobao.products.get');//获取产品列表
define('TAOBAO_PRODUCTS_SEARCH', 'taobao.products.search');//搜索产品信息
?>