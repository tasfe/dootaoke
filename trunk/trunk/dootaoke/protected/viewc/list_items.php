<?php
// seo
$selected_params = selected_params($data['params']['props'],$data['props']);

// 面包屑-标题
if(!empty($data['crumbs'])) {
	foreach($data['crumbs'] as $crumb) {
		$keys[] = $crumb['name'];
	}
}
// 属性-标题
if(!empty($selected_params)) {
	foreach($selected_params as $s) {
		$keys[] = $s['name'];
	}
}

$data['page_title'] = implode(TITLE_SPLIT,array_reverse($keys));
$data['page_keywords'] = implode(',',$keys);
$data['page_description'] = $data['current_cat']->name . '正品化妆品导购,所有商品提供假一赔三或者7天无理由退换货服务，让您网上购安全省心！';

Doo::loadClass('util/TaobaoUtil');
?>
<?php include "sub_view/header.php"; ?>
<div id="page_right">
	<div class="right_box">
		<div class="page_crumbs"><?php echo display_breadcrumb($data['crumbs'],array('url'=>Doo::conf()->SUBFOLDER,'name'=>'首页'));?>
		<div class="total">相关商品<?php echo $data['total_items'];?>件</div>
		<div class="c"></div>
		</div>
		
		<div class="prop_box">
		<?php 
		$data_params = $data['params'];
		// 显示已选择的属性
		if(!empty($selected_params)) {
		?>
		<div class="selected_prop"><span class="font_3">您已选择：</span>
		<?php foreach($selected_params as $s) {
			$data_params = $data['params'];	
			$data_params['props'] = del_a_prop($data_params['props'],$s['pid'],$s['vid']);
		?>
		<a title="取消选择" href="<?php echo url2(true,'MallItemController','list_items','p=>' . construct_urlparams($data_params,$data['param_keys']));?>"><h5><?php echo $s['pname'];?>:</h5><?php echo $s['name'];?><span></span></a>
		<?php } ?>
		<div class="c"></div>
		 </div>
		 <?php } ?>
		
		<?php
		$line_num = 4;//每行显示属性个数
		$prop_num = 4;//默认显示多少个属性
		$max_props = 10;//最多显示多少种属性
		$num = 1;
		foreach($data['props'] as $prop) {
			// 在查询条件中存在则不显示
			$data_params = $data['params'];
			if(stristr($data_params['props'],$prop['pid'])) continue;
			?>
			<div class="sub_prop <?php if($num > $prop_num) echo 'none';?>">
				<div class="prop_name"><?php echo $prop['name'];?>：</div>
				<div class="prop_list"><ul>
				<?php
				if($prop['prop_values']['@attributes']['list']) {
					if($prop['prop_values']['prop_value'][0]){
						$propVals = $prop['prop_values']['prop_value'];
					} else {
						$propVals[] = $prop['prop_values']['prop_value'];
					}
				$cnt = 1;
				foreach($propVals as $pval) {
					$class = ''; 
					 if($prop['pid']==20000 && $cnt>3*$line_num) {//品牌显示三行
					 	$class = 'class="none"';
					 } elseif($prop['pid']!=20000 && $cnt>$line_num) { 
					 	$class = 'class="none"';
					 }
					$prop_params = $data['params'];
					$prop_params['props'] = construct_props($data_params['props'],$prop['pid'], $pval['vid']); 
					?>
					<li <?php echo $class;?> ><a href="<?php echo url2(true,'MallItemController','list_items','p=>' . construct_urlparams($prop_params,$data['param_keys']));?>"><?php echo $pval['name'];?></a></li>
				<?php $cnt++;} } ?>
				</ul></div>
				<?php if($cnt > $line_num+1) { ?><div class="prop_more close" val="more"><a href="#">更多</a></div><?php } ?>
			<div class="c"></div>
			</div>
		<?php $num++;if($num>$max_props) break;} ?>
		</div>
		<?php if($num > $prop_num+1) { ?>
			<div class="show_more close" val="more"><a href="#">全部属性</a></div>
		<?php }?>
	</div><!--prop box-->
	
	<div class="orders_box">
	<ul>
	<?php global $_G;
	$orders = $_G['taobao']['orders'];
	foreach($orders as $k => $o) {
		$data_params = $data['params'];
		$co = array_key_exists($data_params['order'],$orders)?$data_params['order']:1;
		$data_params['order'] = $k;  
	?>
	<li <?php if($co==$k) echo 'class="selected"';?>><a href="<?php echo url2(true,'MallItemController','list_items','p=>' . construct_urlparams($data_params,$data['param_keys']));?>"><?php echo $o['name'];?></a></li>
	<?php	
	}
	?>
	</ul></div><!--orders-->
	
	<?php include 'sub_view/list_condition_box.php'?>
	
	<?php include 'sub_view/list_items.php';?>
</div><!--page_right-->

<div id="page_left">
<?php include 'sub_view/cats_list.php';?>
<?php include 'sub_view/ad/page_left_mr.php';?>
</div><!--page_left-->
<script type="text/javascript">
$(document).ready(function(){
	$(".prop_more").bind('click',function(){
		$(this).prev('.prop_list').find('li.none').toggle();
    	if($(this).attr('val')=='more') {
    		$(this).children('a').html('收起');
    		$(this).attr('val','');
    	} else {
    		$(this).attr('val','more');
    		$(this).children('a').html('更多');
    	}
    	$(this).toggleClass('open').toggleClass('close');
		return false;
	});
	$(".show_more").bind('click',function(){
		$('.prop_box').children('div.none').toggle();
		if($(this).attr('val')=='more') {
    		$(this).children('a').html('收起');
    		$(this).attr('val','');
    	} else {
    		$(this).attr('val','more');
    		$(this).children('a').html('全部属性');
    	}
    	$(this).toggleClass('open').toggleClass('close');
    	return false;
	});
});
var top_imgs = <?php echo TaobaoUtil::convertTopImages($top_imgs); ?>;
</script>
<?php include "sub_view/footer.php"; ?>