<?php
$data['page_title'] = '中国第一化妆品社区与导购平台';
$data['page_keywords'] = '化妆平,美容护肤,彩妆,香水';
$data['page_description'] = '再美点网(zmeidian.com)是一家正品化妆品导购网站,所有商品提供假一赔三或者7天无理由退换货服务，让您网上购安全省心！';

Doo::loadClass('util/TaobaoUtil');
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
<div class="r_top">
<?php $slider_ads = require_once("sub_view/data/slider_ads.php");?>
<div class="slider_ad"><?php echo display_slider_ad($slider_ads,"index_1",'container1','slides1');?></div>

<div class="article">
<h2>热门话题</h2>
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>sns/new.php?action=article&length=60&order=hits"&num=11></script>
</div>
<div class="c"></div>
</div>
<iframe frameborder="0" marginheight="0" marginwidth="0" border="0" id="alimamaifrm" name="alimamaifrm" scrolling="no" height="150px" width="760px" src="http://taoke.alimama.com/channel/beautifyChannelHor.htm?pid=mm_14154427_2202002_9118625" ></iframe>

<div class="new_items">
<?php 
$count = 0;
foreach($data['items'] as $items) {
	$left_items = $items['items'];
	$leftlist_num = 8;
	$img_id_x = $count++;
?>
<div class="box_head"><h2><?php echo $items['name'];?>热卖商品</h2></div>
<?php 
	require('sub_view/list_left_items.php');
}?>
</div><!--new items-->
</div><!--page_right-->

<div id="page_left">
<?php 
if(sizeof($data['cats']) > 0) {
foreach($data['cats'] as $cats) {
	$pcat = $cats['pcat'];
	$subcats = $cats['subcats'];

	$param_keys = array('cat');
	$controller = 'MallItemController';
	$action = 'list_items';
	$cat_head_name = $cats['pcat']->name;
?>
<div class="cats_box">
	<div class="head"><h2><?php echo $cat_head_name;?></h2></div>
	<div class="cats_list">
		<ul>
		<?php
		foreach($subcats as $cat) {
		if(!$cat_name = $cat->name) continue;
		$data_params = array('cat'=>$cat->cid);
		?>
			<li><a href="<?php echo url2(true,$controller,$action,'p=>' . construct_urlparams($data_params,array('cat'),$param_keys));?>"><?php echo $cat_name;?></a></li>
		<?php } ?>
		</ul>
	</div>
</div>
<?php }} ?>

<div class="left_box">
<div class="head"><h2>活跃会员</h2></div>
<div class="info">
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>sns/new.php?action=member&order=digests"></script>
</div>
</div>

</div><!--page_left-->
<script type="text/javascript">
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
$(function(){
	$('#index_1').loopedSlider({
			autoStart: 5000,
			restart: 5000,
			containerClick:false,
			container:'.container1',
			slides:'.slides1'
		});
});
</script>
</script>
<?php include "sub_view/footer.php"; ?>