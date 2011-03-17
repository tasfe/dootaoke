<div id="head_nav">
<div class="head_menu">
	<div class="head_logo">
	<a href="/"><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/logo.png"></a>
	</div>
	<div class="head_top">
	<div class="h_service"></div>
	<b class="rc-lt"></b>
	<div class="menu-bd">
	<ul class="links-way oe_menu" id="oe_menu">
		<?php $head_navs_id = array('1801','50010788','products','shop');?>
		<li <?php if(!in_array($data['head_nav_id'],$head_navs_id)) echo 'class="current"';?>><a title="首页" href="<?php echo Doo::conf()->SUBFOLDER; ?>"><span>首页</span></a></li>
		<li <?php if($data['head_nav_id']==1801) echo 'class="current"';?>><h2><a title="美容护肤用品" href="<?php echo Doo::conf()->SUBFOLDER; ?>list/cat-1801"><span>美容护肤</span></a></h2>
		<?php include 'head_nav_meirong.php';?>
		</li>
		 <li <?php if($data['head_nav_id']==50010788) echo 'class="current"';?>><h2><a title="彩妆香水" href="<?php echo Doo::conf()->SUBFOLDER; ?>list/cat-50010788"><span>彩妆香水</span></a></h2>
		<?php include 'head_nav_caizhuang.php';?>
		 </li>
		 <li <?php if($data['head_nav_id']=='products') echo 'class="current"';?>><h2><a title="产品库" href="<?php echo Doo::conf()->SUBFOLDER; ?>products"><span>产品库</span></a></h2>
		<?php include 'head_nav_products.php';?>
		 </li>
		 <li <?php if($data['head_nav_id']=='shop') echo 'class="current"';?>><h2><a title="店铺" href="<?php echo Doo::conf()->SUBFOLDER; ?>shop"><span>店铺</span></a></h2>
		<?php include 'head_nav_shop.php';?>
		 </li>
		 <li><h2><a title="美丽社区" href="<?php echo Doo::conf()->SUBFOLDER; ?>sns"><span>社区</span></a></li></h2>
	</ul>
	</div>
	</div><!--head top-->
	<div class="c"></div>
</div><!--head menu-->
<div class="head_submenu">
	<div class="head_search">
		<form action="<?php echo Doo::conf()->SUBFOLDER; ?>list/" method="get">
			<div class="fl"><input id="q_key" type="text" class="text" name="q" value="<?php echo trim($_GET['q']);?>" size="30" maxlength="30" /></div>
			<div class="cat_select"><select name="cat" class="catSelect">
				<?php global $_G;?>
				<?php foreach($_G['taobao']['top_cats'] as $k => $v) { ?>
				<option <?php echo $data['params']['cat']==$k?'selected':'';?> value="<?php echo $k;?>"><?php echo $v;?></option>
				<?php } ?>
			</select></div>
			<button type="submit" id="search_btn" class="search_button">搜索</button>
		</form>
	</div>
	<div class="submenu_box">
	<b class="rc-lt"></b>
	<div class="sub-menu-bd">
	<ul class="sub-links-way">
		<li><span class="font_1">热门搜索：</span></li>
		<?php include 'head_hot_words.php';?>
	</ul>
	</div>
	</div>
	<div class="c"></div>
</div><!--head submenu-->
</div><!--head_nav-->