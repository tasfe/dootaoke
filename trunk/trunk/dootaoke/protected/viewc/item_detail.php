<?php
// seo
Doo::loadClass(array('util/StringUtil','util/TaobaoUtil'));
$item_detail = $data['item_detail'];

// 面包屑-标题
if(!empty($data['crumbs'])) {
	foreach($data['crumbs'] as $crumb) {
		$keys[] = $crumb['name'];
	}
}

$data['page_title'] = $item_detail['title'] . TITLE_SPLIT .implode(TITLE_SPLIT,array_reverse($keys));
$data['page_keywords'] = implode(',',TaobaoUtil::get_keywords_props($item_detail['props_name']));
$data['page_description'] = '再美点网(www.zmeidian.com)提供'.$item_detail['title'].'导购,'.$item_detail['title'].'支持假一赔三或者7天无理由退换货服务，让您网上购安全省心！';
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
<?php
$is_robot = is_robot();
$taobaoItem = $data['TAOBAO_ITEM'];
$discount = ($item_detail['has_discount'] == "true")?'<b style="color:#FF6600">支持打折</b>':'不支持打折';
$warranty = ($item_detail['has_warranty'] == "true")?'<b style="color:#FF6600">提供保修</b>':'无保修';
$invoice = ($item_detail['has_invoice'] == "true")?'<b style="color:#FF6600">可提供发票</b>':'无发票';
$payer = ($item_detail['freight_payer'] == "seller")?'<b style="color:#FF6600">卖家承担运费</b>':'买家承担运费';
$promise = ($item_detail['sell_promise'] == "true")?'<b style="color:#FF6600">凡使用支付宝购买本店商品，若存在质量问题或者与描叙不符，本店将主动提供退换货服务并承担来回邮费！</b>':'';
$cod = ($item_detail['cod_postage_id'])?'<b style="color:#FF6600">支持</b>':'不支持';
$showcase = ($item_detail['has_showcase']=='true')?'<b style="color:#FF6600">推荐</b>':'';

//$type = ($item_detail['type'] == "fixed")?"价 格":"拍卖";
$price = StringUtil::convertPrice($item_detail['price']);

$seo_title = StringUtil::replaceSpecialChars($item_detail['title']);

$to_url = url2(true,'MainController','redirect_to','type=>2,id=>'.$item_detail['num_iid']); 
$to_shop_url = url2(true,'MainController','redirect_to','type=>1,id=>'.$item_detail['nick']);

$shop_url = url2(true,'MallShopController','shop_detail','p=>'.convert_array_urlparams(array('id'=>$item_detail['nick'])));
?>
<div class="detail">
	<div class="pname"><h1><?php echo $seo_title;?></h1></div>
	<div class="pic_attributes">
	<div class="pic_box">
	<div class="detail_pic">
		<a href="<?php echo $to_url;?>" target="_blank" rel="external nofollow">
		<?php $top_imgs['detail_img'] = TaobaoUtil::convertTaobaoPic($item_detail['pic_url'],"_310x310.jpg");?>
		<img id="detail_img" src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif" alt="<?php echo $seo_title;?>" />
		</a>
	</div>
	</div>
	<?php $imgs = $taobaoItem->getItemImgs($item_detail);
	if($imgs && sizeof($imgs)>0) { ?>
	<div class="small_pic"><ul>
		<?php $i=0;
		 foreach($imgs as $k => $val) { 
		 	 if($i==0) {
		 	 	$liClass='current_img';
		 	 } else {
		 	 	$liClass=null;
		 	 } 
			$top_imgs['s_img_'.$i]=TaobaoUtil::convertTaobaoPic($imgs[$i]['url'],'_40x40.jpg');?>
		<li><img id="s_img_<?php echo $i;?>" class="<?php echo $liClass;?>" name="small-img" src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif"></li>
		<?php $i++;} ?>
	</ul></div>
	<?php } ?>
	<div class="attributes">
		<ul>
			<li>价格：<span class="price"><b><?php echo $price;?></b></span></li>
			<li>商家：<?php echo $item_detail['nick'];?>
			(<a href="<?php echo $shop_url;?>">更多本店商品&gt;&gt;</a>)
			</li>
			<li>发票：<?php echo $invoice;?></li>
			<li>会员VIP打折：<?php echo $discount;?></li>
			<li>货到付款：<?php echo $cod;?></li>
			<li>运费承担：<?php echo $payer;?></li>
			<li>运费：平邮费用：<?php echo StringUtil::convertPrice($item_detail['post_fee']);?> 快递费用：<?php echo StringUtil::convertPrice($item_detail['express_fee']);?> </li>
		    <li>库存数量：<?php echo $item_detail['num'];?></li>
		    <?php if($showcase) { ?><li><b style="color:#FF6600">店长推荐商品</b></li><?php } ?>
		    <?php if($promise) { ?><li><?php echo $promise;?></li><?php } ?>
		</ul>
	<div class="goto">
	<a href="<?php echo $to_url;?>" target="_blank" id="to_p" rel="external nofollow"><img src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/baobei_detail.gif" title="进入商品详情页面>>"/></a>
	<a href="<?php echo $to_shop_url;?>" target="_blank" id="to_p" rel="external nofollow"><img src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/shop_detail.gif" title="进入TA的店铺>>"/></a>
	</div>
	</div>
	<div class="c"></div>
	</div>

 <div class="list_head">
 <ul>
 <li class="selected" name="list_li" target="detail_box"><a href="javascript:void(0);">商品详情</a></li>
 <li id="list_review" name="list_li" target="review_box"><a href="javascript:void(0);">评价详情</a></li>
 </ul>
 </div>
<div style="display:none;" class="review_box">
<img src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif">
</div>
<div class="detail_box">
<div class="props_box">
    <div class="props">
        <?php $props=TaobaoUtil::convert_props($item_detail['props_name']);?>
        <ul class="prop_list">
        <?php foreach($props as $k => $v) { 
        $lurl = url2(true,'MallItemController','list_items','p=>' . convert_array_urlparams(array('cat'=>$item_detail['cid'],'props'=>$v['pid'].':'.$v['vid'])));	
        ?>
        <li><?php echo $v['pname'];?> : <a href="<?php echo $lurl;?>"><?php echo $v['vname'];?></a></li>
        <?php } ?>
        <div class="c"></div>
        </ul>
        <div class="c"></div>
    </div>
</div>

<div class="desc_box">
    <div class="desc">
    	<?php if($is_robot) {
			echo StringUtil::removelinkImg($item_detail['desc']); 
		} else { 
			echo StringUtil::removeSuperlink($item_detail['desc']);
		} ?>
    </div>
</div>
</div><!--detail_box-->
<div class="c"></div>
</div>
</div><!--page_right-->

<div id="page_left">
<?php $shopInfo = $data['shop_info'];
$display_items = true;
$goodId = $item_detail['num_iid'];
include 'sub_view/shop_info.php';
include 'sub_view/cats_list.php';?>
</div><!--page_left-->
<script type="text/javascript">
$(function(){		
	$("img[name=small-img]").live("mouseover",function(){
		var new_src = $(this).attr("src").replace("40x40.jpg","310x310.jpg");
		$("#detail_img").attr("src",new_src);
		$("img[name=small-img]").removeClass("current_img");
		$(this).addClass("current_img");
	});
	$("#amos").live("click",function(){
		var to = $("#to_p").attr("href");
		window.open(to);
	});
	$("li[name=list_li]").bind("click",function(){
		$("li[name=list_li]").each(function(){
			$(this).removeClass('selected');
			$('.' + $(this).attr('target')).css('display','none');
		});
		$(this).addClass('selected');
		$('.' + $(this).attr('target')).css('display','block');
	});
	$('#list_review').bind('click',function(){
		var r_url = <?php echo '"' . 'http://rate.taobao.com/detail_rate.htm?userNumId='.$data['user_info']['user_id'].'&auctionNumId='.$item_detail['num_iid'].'&showContent=1&currentPage=1&ismore=0&siteID=7' .'"';?>;
		$.get('<?php echo Doo::conf()->SUBFOLDER; ?>ajax/remote',{'r_url':r_url,'func':'taobao_comments'},function(data){
			if(data) {
				$('.review_box').html(data);
			}
		});
	});
	$('#more_reviews').live('click',function(){
		window.open(<?php echo '"' . $to_url . '"';?>);
	});
	
});
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>