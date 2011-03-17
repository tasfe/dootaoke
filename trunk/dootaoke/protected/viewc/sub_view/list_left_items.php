<?php
$left_items = $left_items?$left_items:$data['leftItems'];
$leftlist_num = $leftlist_num?$leftlist_num:10;
$img_id_x = $img_id_x?$img_id_x:'';
?>
<div class="items_box">
<div class="p_list">
<ul>
<?php
	$i = 0; 
	foreach($left_items as $val) { 
	//$img_url = convertTaobaoPic($val['pic_url'],"_160x160.jpg");
	$title = TaobaoUtil::removeSpan($val['title']);
	$hurl = url2(true,'MallItemController','item_detail','id=>'.$val['num_iid']);
	$shop_url = url2(true,'MallShopController','shop_detail','p=>'.convert_array_urlparams(array('id'=>$val['nick'])));
?>
		<li>
			<div class="pic_box">
				<a href="<?php echo $hurl;?>">
					<?php $top_imgs['lf_img_'.$img_id_x . $i] = TaobaoUtil::convertTaobaoPic($val['pic_url'],"_160x160.jpg");?>
					<img alt="<?php echo $title;?>" id="lf_img_<?php echo $img_id_x . $i;?>" src="<?php echo Doo::conf()->SUBFOLDER;?>global/img/loader.gif">
				</a>
			</div>
			<div class="title">
				<a href="<?php echo $hurl;?>" title="<?php echo $title;?>"><?php echo $val['title'];?></a>
			</div>
			<div class="price">价格：<span class="price"><?php echo $val['price'];?></span>元</div>
			<div>商家：<span><a href="<?php echo $shop_url;?>"><?php echo $val['nick'];?></a></span></div>
			<div>30天销量：<span class="volume"><?php echo $val['volume'];?></span>件</div>
			<?php if($val['item_location']) { ?><div>所在地：<span><?php echo $val['item_location'];?></span></div><?php } ?>
		</li>
<?php $i++;if($i >= $leftlist_num) break;} ?>
</ul>
</div>

</div><!--items-->
