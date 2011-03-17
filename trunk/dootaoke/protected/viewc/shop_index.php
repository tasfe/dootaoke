<?php
// seo
Doo::loadClass(array('util/StringUtil','util/TaobaoUtil'));
$shopInfo = $data['shop_info'];

$data['page_title'] = '化妆品名店_化妆品品牌旗舰店';
$data['page_keywords'] = '化妆品名店,化妆品旗舰店,淘宝化妆品店铺';
$data['page_description'] = '淘宝网化妆品旗舰店大全，化妆品名店大全，让你美丽动人，购物更安全快捷';
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
<?php $brands = require('data/brand_shops.php');?>
<div class="brands">
<div class="head"><h2>品牌官方旗舰店</h2></div>
<ul>
<?php foreach($brands as $b) {
$shop_url = url2(true,'MallShopController','shop_detail','p=>'.convert_array_urlparams(array('id'=>$b['nick'])));
?>
<li>
<div class="pic"><a href="<?php echo $shop_url;?>"><img src="<?php echo Doo::conf()->SUBFOLDER .'global/img/brand/'.$b['img'];?>"></a></div>
<div class="name"><a href="<?php echo $shop_url;?>"><?php echo $b['brand'];?></a></div>
</li>
<?php } ?>
</ul>
<div class="c"></div>
</div>
<iframe style="margin-top:10px;" frameborder="0" marginheight="0" marginwidth="0" border="0" id="alimamaifrm" name="alimamaifrm" scrolling="no" height="150px" width="760px" src="http://taoke.alimama.com/channel/beautifyChannelHor.htm?pid=mm_14154427_2202002_9104820" ></iframe>
</div><!--page_right-->

<div id="page_left">
<div class="left_box">
<div class="head"><h2>美容用品店铺推荐</h2></div>
<?php $shops = require('data/meirong_shops.php');?>
<div class="shops">
<ul>
<?php 
$display_num = 18;
$cnt = 0;
foreach($shops as $s) {
$shop_url = url2(true,'MallShopController','shop_detail','p=>'.convert_array_urlparams(array('id'=>$s['nick'])));
?>
<li>
<div class="name"><a href="<?php echo $shop_url;?>"><?php echo $s['name'];?></a></div>
</li>
<?php $cnt++;if($cnt>$display_num) break;} ?>
</ul>
<div class="c"></div>
</div>
</div><!--left box-->

<div class="left_box">
<div class="head"><h2>彩妆用品店铺推荐</h2></div>
<?php $shops = require('data/caizhuang_shops.php');?>
<div class="shops">
<ul>
<?php 
$display_num = 18;
$cnt = 0;
foreach($shops as $s) {
$shop_url = url2(true,'MallShopController','shop_detail','p=>'.convert_array_urlparams(array('id'=>$s['nick'])));
?>
<li>
<div class="name"><a href="<?php echo $shop_url;?>"><?php echo $s['name'];?></a></div>
</li>
<?php $cnt++;if($cnt>$display_num) break;} ?>
</ul>
<div class="c"></div>
</div>
</div><!--left box-->
</div><!--page_left-->
<script type="text/javascript">
$(function(){		

});
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>