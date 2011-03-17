<?php 
if(sizeof($data['catArray']) > 0) {
$param_keys = $param_keys?$param_keys:array('cat');
$controller = $controller?$controller:'MallItemController';
$action = $action?$action:'list_items';
$cat_head_name = $cat_head_name?$cat_head_name:'商品分类';
?>
<div class="cats_box">
	<div class="head"><h2><?php echo $cat_head_name;?></h2></div>
	<div class="cats_list">
		<ul>
		<?php
		foreach($data['catArray'] as $cat) {
		if(!$cat_name = $data['all_cats'][$cat['category_id']]->name) continue; 
		$data_params = $data['params'];
		$data_params['cat'] = $cat['category_id'];
		?>
			<li <?php if($data['current_cat']->cid==$cat['category_id'])echo 'class="current"';?>><a href="<?php echo url2(true,$controller,$action,'p=>' . construct_urlparams($data_params,$data['param_keys'],$param_keys));?>"><?php echo $cat_name;?></a><?php if(isset($cat['count'])) { ?><span class="font_2">(<?php echo $cat['count'];?>)</span><? } ?></li>
		<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>