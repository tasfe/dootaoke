<?php
// seo
Doo::loadClass(array('util/StringUtil','util/TaobaoUtil'));
$shopInfo = $data['shop_info'];
// 面包屑-标题
if(!empty($data['crumbs'])) {
	foreach($data['crumbs'] as $crumb) {
		$keys[] = $crumb['name'];
	}
}

$data['page_title'] = implode(TITLE_SPLIT,array_reverse($keys));
$data['page_keywords'] = implode(',',$keys);
$data['page_description'] = $shopInfo['title'].'导购,'.$shopInfo['title'].'的所有支持假一赔三或者7天无理由退换货服务，让您网上购安全省心！';
?>
<?php include "sub_view/header.php"; ?>
<?php 
$to_shop_url = url2(true,'MainController','redirect_to','type=>1,id=>'.$shopInfo['nick']);
//if(!$state_city) $state_city=($val['location']['state']==$val['location']['city'])?$val['location']['state']:$val['location']['state'].' '.$val['location']['city'];?>
<div id="page_right">
<div class="page_crumbs"><?php echo display_breadcrumb($data['crumbs'],array(array('url'=>Doo::conf()->SUBFOLDER . 'shop','name'=>'店铺'),array('url'=>Doo::conf()->SUBFOLDER,'name'=>'首页')));?>
<div class="total">相关商品<?php echo $data['total_items'];?>件</div>
<div class="c"></div>
</div>

<?php $is_robot = is_robot();
$controller = 'MallShopController';
$action = 'shop_detail';
$params_str = '';
include 'sub_view/list_items.php';
?>
</div><!--page_right-->

<div id="page_left">
<?php include 'sub_view/shop_info.php';?>
<?php 
$param_keys = array('cat','id','q');
$cat_head_name = 'TA的商品分类';
include 'sub_view/cats_list.php';?>
</div><!--page_left-->
<script type="text/javascript">
$(function(){		

});
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>