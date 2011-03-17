<?php
// seo
Doo::loadClass(array('util/StringUtil','util/TaobaoUtil'));
$p_detail = $data['product_detail'];

// 面包屑-标题
if(!empty($data['crumbs'])) {
	foreach($data['crumbs'] as $crumb) {
		$keys[] = $crumb['name'];
	}
}

$data['page_title'] = $p_detail['name'] . TITLE_SPLIT .implode(TITLE_SPLIT,array_reverse($keys));
$data['page_keywords'] = implode(',',TaobaoUtil::get_keywords_props($p_detail['props_str'] .';'. $p_detail['binds_str'],false));
$data['page_description'] = '再美点网(www.zmeidian.com)提供'.$p_detail['name'].'导购,'.$p_detail['name'].'支持假一赔三或者7天无理由退换货服务，让您网上购安全省心！';
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
<div class="p_box">
	<div class="p_info">
	<h1><?php echo $p_detail['name'];?></h1>
	
	<?php $props = TaobaoUtil::convert_props_name($p_detail['props_str'] .';'. $p_detail['binds_str'],false); ?>
	<div class="pic">
	<?php $top_imgs['p_img'] = TaobaoUtil::convertTaobaoPic($p_detail['pic_url'],"_120x120.jpg");?>
	<img src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif" id="p_img">
	</div>
	<div clss="other">
	<div>分类：<a href="<?php echo url2(true,'MallItemController','list_items','p=>' . construct_urlparams(array('cat'=>$p_detail['cid'])));;?>"><?php echo $p_detail['cat_name'];?></a></div>
	<div>参考价：<span class="price"><?php echo $p_detail['price'];?></span>元</div>
	<div>
	<?php foreach($props as $k => $v) {
		$prop_str .= $k . '：' . $v .'<br>';
	 } 
	 echo $prop_str;
	 ?>
	</div>

	</div>
	<div class="c"></div>
	</div>
	
	<?php 
	$product_imgs = $p_detail['product_imgs'];
	$img_num = 0;
	$imgs = array();
	if($product_imgs && $product_imgs['@attributes']['list']) {
		if($product_imgs['product_img']['url']) {
			$imgs[] = $product_imgs['product_img'];
		} else {
			$imgs = $product_imgs['product_img'];	
		}
		
	}
	$imgs[] = array('url'=>$p_detail['pic_url']);
	$img_num = sizeof($imgs); 
	
	?>
	<div class="nav">
	<ul class="tabbar">
	<li class="<?php if($data['current_page']==1) echo 'selected'?>" val="desc"><a href="#desc">产品详情</a></li>
	<li class="<?php if($data['current_page']>1) echo 'selected'?>" val="related"><a href="#related">相关商品</a></li>
	<li class="" val="pics"><a href="#pics">产品图片(<em class=""><?php echo $img_num;?></em>)</a></li>
	</ul>
	</div>
	
	<div id="pcontent">
	<div class="related"  style="<?php if($data['current_page']==1) echo 'display:none;';?>">
	<?php 
	$controller = 'MallProductController';
	$action = 'product_detail';
	$params_str = 'id=>' . $p_detail['product_id'];
	include 'sub_view/list_items.php';?>
	</div>
	
	<div class="desc" style="<?php if($data['current_page']>1) echo 'display:none;';?>">
	<?php if(sizeof($props) > 0) { ?>
	<div class="cfg-box">
	<table class="configure">
    <thead><td colspan="2">产品参数</td></thead>
    <tbody>
	<?php foreach($props as $k => $v) {
	?>
	<tr>
        <td class="name"><?php echo $k;?></td>
        <td><?php echo $v;?></td>
     </tr>
	<?php } ?>
   		</tbody>
	</table>
	</div>
	<?php } ?>
	
	<?php 
	if(!empty($p_detail['desc'])) {
		echo $p_detail['desc'];	
	}
	?>
	</div>
	
	<div class="pics" style="display:none;">
	<?php if($img_num) {
	foreach($imgs as $k => $img) {	
		$top_imgs['pr_img' . $k] = TaobaoUtil::convertTaobaoPic($img['url'],"_160x160.jpg");
	?> 
	<img src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif" id="pr_img<?php echo $k;?>">
	<?php }} else { ?>
	<div>没有相关图片</div>
	<?php }?>
	</div>
	</div>
</div><!--p box-->
</div><!--page_right-->

<div id="page_left">
<div class="left_box">
<h3 class="head"><?php echo $p_detail['cat_name'];?></h3>
<?php include 'sub_view/list_left_items.php';?>
</div>
</div><!--page_left-->
<script type="text/javascript">
$(function(){		
	$('.tabbar').children('li').bind('click',function(){
		$('.tabbar').children('li').removeClass('selected');	
		$(this).addClass('selected');
		
		$('#pcontent').children('div').css('display','none');
		$('.' + $(this).attr('val')).css('display','block');
		return false;
	});
});
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>