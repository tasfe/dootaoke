<?php
$data['page_title'] = '美容护肤/彩妆香水产品库';
$data['page_keywords'] = '美容护肤,彩妆香水,品牌化妆品';
$data['page_description'] = '正品化妆品导购,所有商品提供假一赔三或者7天无理由退换货服务，让您网上购安全省心！';

Doo::loadClass('util/TaobaoUtil');
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
<form action="<?php echo url2(true,'MallProductController','search_products','search=>');?>" method="get">
	<input type="text" class="text" name="q" value="<?php echo $data['params']['q'];?>" size="20" maxlength="30" />
	<select name="cat">
	<?php foreach($data['all_cats'] as $cat) { ?>
		<option <?php if($cat->cid==$data['params']['cat']) echo 'selected="selected"';?> value="<?php echo $cat->cid;?>"><?php echo $cat->name;?></option>
	<?php } ?>
	</select>
	<input type="submit" class="button" name="submit" value="搜索产品" />
</form>

<div class="brands">
<?php 
$p_brands = $data['product_brands'];
foreach($data['top_cats'] as $cid => $name) { 
$brands = $p_brands[$cid]; 	
?>
<div class="box_head">
<div class="head"><h2><?php echo $name;?>品牌产品</h2></div>
</div>
<div class="brands_list">

<?php foreach($brands as $b) { 
$data_params = array('cat'=>$cid,'q' => '20000:' .$b['vid']);
$burl = url2(true,'MallProductController','list_products','p=>' . construct_urlparams($data_params,$data['param_keys']));
?>
<li><a href="<?php echo $burl;?>"><?php echo $b['name'];?></a></li>
<?php } ?>

</div>
<div class="c"></div>
<?php } ?>
</div><!-- brands-->
</div><!--page_right-->

<div id="page_left">
<div class="left_box">
<?php
$effects = require('sub_view/data/product_effect.php');
foreach($effects as $k => $efs) {
?>
<div class="head"><h2><?php echo $k;?></h2></div>
<div class="info">
<?php foreach($efs as $e) {
$data_params = array('props' => $e['pid'] . ':' . $e['vid']);
$burl = url2(true,'MallProductController','list_products','p=>' . construct_urlparams($data_params,$data['param_keys']));
?>
<li>
<a href="<?php echo $burl;?>"><?php echo $e['name'];?></a>
</li>
<?php } ?>
</div>
<?php } ?>
</div><!--left box-->

</div><!--page_left-->
<script type="text/javascript">
top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>