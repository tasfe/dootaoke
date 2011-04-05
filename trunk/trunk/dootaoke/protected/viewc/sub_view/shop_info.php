<?php $gid=$goodId?$goodId:null;
$userInfo = $data['user_info'];
?>
<div class="left_box">
<div class="head"><h2>店铺信息</h2></div>
<div class="info">
<div><span>店长：<b><?php echo $shopInfo['nick'];?></b></span></div>
<div class="credit">
<?php if($userInfo['type']=='B') { ?>
<img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/credit/tmall.png">
<?php } else { ?>
信誉：<img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/credit/score_<?php echo $userInfo['seller_credit']['level'];?>.gif">
<?php } ?>
</div>
<div>联系：<?php echo display_wangwang($shopInfo['nick'],$gid);?></div>
<div>所在地：<?php echo state_city($userInfo['location']['state'],$userInfo['location']['city']);?></div>
<div><span><a class="abtn2" href="<?php echo $to_shop_url;?>"  rel="external nofollow" target="_blank"><span><b>去TA的淘宝店逛逛</b></span></a></span></div>
<div class="fl">商品描述：<?php echo $shopInfo['shop_score']['item_score'];?></div><div class="fl star<?php echo get_star($shopInfo['shop_score']['item_score'],5);?>"></div><div class="c"></div>
<div class="fl">服务态度：<?php echo $shopInfo['shop_score']['service_score'];?></div><div class="fl star<?php echo get_star($shopInfo['shop_score']['service_score'],5);?>"></div><div class="c"></div>
<div class="fl">发货速度：<?php echo $shopInfo['shop_score']['delivery_score'];?></div><div class="fl star<?php echo get_star($shopInfo['shop_score']['delivery_score'],5);?>"></div>
<div class="c"></div>
</div>
<?php 
Doo::loadClass('util/StringUtil');
$shop_bulletin = trim(trim(strip_tags($shopInfo['bulletin'])),'&nbsp;'); 
if($shop_bulletin && !empty($shopInfo['bulletin'])) { ?>
<div class="head"><h2>店铺公告</h2></div>
<div class="bulletin"><?php echo substring($shop_bulletin,100,'...'); ?><a href="#" id="bulletin_more">显示全部↓</a></div>
<div class="none all_content" id="shop_bulletin"><div class="f"><div class="i"><div class="close_ct"><a href="#" title="关闭">关闭</a></div><?php echo StringUtil::removelinkImg($shopInfo['bulletin']); ?></div></div></div>
<? } ?>
<?php 
$shop_desc = trim(trim(strip_tags($shopInfo['desc'])),'&nbsp;');
if($shop_desc && !empty($shopInfo['desc'])) { ?>
<div class="head"><h2>店铺介绍</h2></div>
<div  class="desc"><?php echo substring($shop_desc,100,'...'); ?><a href="#" id="desc_more">显示全部↓</a></div>
<div class="none all_content" id="shop_desc"><div class="f"><div class="i"><div class="close_ct"><a href="#" title="关闭">关闭</a></div><?php echo StringUtil::removelinkImg($shopInfo['desc']); ?></div></div></div>
<? } ?>
<div class="c"></div>

<?php if($data['total_items'] && $display_items) { ?>
<div class="head"><h2>本店热卖商品</h2></div>
<?php
$list_num = 10;
$list_page = false;
include 'list_items.php'; ?>
<div class="c"></div>
<?php } ?>
</div>
<script type="text/javascript">
$(function(){		
	$('#bulletin_more').bind('click',function(){
		$('#shop_bulletin').css('display','block');
		return false;
	});
	$('#desc_more').bind('click',function(){
		$('#shop_desc').css('display','block');
		return false;
	});
	$('.close_ct').bind('click',function(){
		$('.all_content').css('display','none');
		return false;
	});
});
</script>