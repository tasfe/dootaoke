<?php 
$list_num = $list_num?$list_num:sizeof($data['itemArray']);
if(isset($list_page) && !$list_page) {
	$list_page = false;		
} else {
	$list_page = true;	
}
?>
<?php if($list_page) { ?>
<?php Doo::loadHelper('DooPager');
$data_params = $data['params'];
unset($data_params['page']);

$controller = $controller?$controller:'MallProductController';
$action = $action?$action:'list_products';
$pageurl = url2(true,$controller,$action,'p=>' . construct_urlparams($data_params,$data['param_keys']));
$pager = new DooPager($pageurl, $data['total_items'], 40, 10,'上一页','下一页');
$pager->paginate($data['current_page']);

$all_cats = $data['all_cats'];
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
	$title = TaobaoUtil::removeSpan($val['name']);
	$hurl = url2(true,'MallProductController','product_detail','id=>'.$val['product_id']);
?>
		<li>
			<div class="pic_box">
				<a href="<?php echo $hurl;?>">
					<?php $top_imgs['p_img_'.$i] = TaobaoUtil::convertTaobaoPic($val['pic_url'],"_160x160.jpg");?>
					<img alt="<?php echo $title;?>" id="p_img_<?php echo $i;?>" src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif">
				</a>
			</div>
			<div class="title">
				<a href="<?php echo $hurl;?>" title="<?php echo $title;?>"><?php echo $val['name'];?></a>
			</div>
			<div class="price">参考价：<span class="price"><?php echo $val['price'];?></span>元</div>
			<div>分类：<span class="volume"><?php echo $all_cats[$val['cid']]->name;?></span></div>
		</li>
<?php $i++;if($i > $list_num) break;} ?>
</ul>
</div>

</div><!--products-->
<?php if($list_page) { ?>
<div class="page_box page_bottom"><?php echo $pager->output;?></div>
<div class="c"></div>
<?php } ?>