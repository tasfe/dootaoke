<?php
// seo
// 面包屑-标题
if(!empty($data['crumbs'])) {
	foreach($data['crumbs'] as $crumb) {
		$keys[] = $crumb['name'];
	}
}

$data['page_title'] = implode(TITLE_SPLIT,array_reverse($keys));
$data['page_keywords'] = implode(',',$keys);
$data['page_description'] = $data['current_cat']->name . '正品化妆品导购,所有商品提供假一赔三或者7天无理由退换货服务，让您网上购安全省心！';

Doo::loadClass('util/TaobaoUtil');
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
	<div class="page_crumbs"><?php echo display_breadcrumb($data['crumbs'],array(array('url'=>Doo::conf()->SUBFOLDER . 'products','name'=>'产品库'),array('url'=>Doo::conf()->SUBFOLDER,'name'=>'首页')));?>
	<div class="total">相关产品<?php echo $data['total_items'];?>件</div>
	<div class="c"></div>
	</div>
	<?php include 'sub_view/list_products.php';?>
</div><!--page_right-->

<div id="page_left">
<div class="left_box">
<h3 class="head">相关商品</h3>
<?php include 'sub_view/list_left_items.php';?>
</div>
</div><!--page_left-->
<script type="text/javascript">
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>