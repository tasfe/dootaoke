<?php 
$list_num = $list_num?$list_num:sizeof($data['itemArray']);
if(isset($list_page) && !$list_page) {
	$list_page = false;		
} else {
	$list_page = true;	
}
?>

<?php if($list_num < 1) { ?>
<div>没有找到相关商品</div>
<?php return;} ?>

<?php if($list_page) { ?>
<?php Doo::loadHelper('DooPager');
$data_params = $data['params'];
unset($data_params['page']);
$controller = $controller?$controller:'MallItemController';
$action = $action?$action:'list_items';
$params_str = $params_str?$params_str:'p=>' . construct_urlparams($data_params,$data['param_keys']);
$pageurl = url2(true,$controller,$action,$params_str);
$pager = new DooPager($pageurl, $data['total_items'], 40, 10,'上一页','下一页');
if($data['total_items'] > 40)
$pager->paginate($data['current_page']);
?>
<div class="page_box page-top"><?php echo $pager->output;?></div>
<div class="c"></div>
<?php } ?>

<div class="products_box">
<div class="p_list">
<ul>
<?php
	$i = 0; 
	foreach($data['itemArray'] as $val) { 
	//$img_url = convertTaobaoPic($val['pic_url'],"_160x160.jpg");
	$title = TaobaoUtil::removeSpan($val['title']);
	$hurl = url2(true,'MallItemController','item_detail','id=>'.$val['num_iid']);
?>
		<li>
			<div class="pic_box">
				<a href="<?php echo $hurl;?>">
					<?php $top_imgs['l_img_'.$i] = TaobaoUtil::convertTaobaoPic($val['pic_url'],"_160x160.jpg");?>
					<img alt="<?php echo $title;?>" id="l_img_<?php echo $i;?>" src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif">
				</a>
			</div>
			<div class="title">
				<a href="<?php echo $hurl;?>" title="<?php echo $title;?>"><?php echo $val['title'];?></a>
			</div>
			<div class="price">价格：<span class="price"><?php echo $val['price'];?></span>元</div>
			<div>30天销量：<span class="volume"><?php echo $val['volume'];?></span>件</div>
			<div class="service">
			<div class="fl">服务：</div>
			<?php if($val['is_prepay']=='true') {?>
			<div class="rushi fl"></div>
			<?php } ?>
			<?php if(stristr($val['promoted_service'],'4')) {?>
			<div class="sevenday fl"></div>
			<?php } ?>
			<?php if(stristr($val['promoted_service'],'2')) {?>
			<div class="zhen fl"></div>
			<?php } ?>
			<?php if($val['has_discount']=='true') {?>
			<div class="zhe fl"></div>
			<?php } ?>
			</div>
		</li>
<?php $i++;if($i > $list_num) break;} ?>
</ul>
</div>

</div><!--products-->
<?php if($list_page) { ?>
<div class="page_box page_bottom"><?php echo $pager->output;?></div>
<div class="c"></div>
<?php } ?>