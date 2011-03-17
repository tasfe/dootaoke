<div class="condition_box">
<?php $data_params = $data['params'];
unset($data_params['q'],$data_params['p1'],$data_params['p2'],$data_params['cat'],$data_params['page'],
	  $data_params['huodao'],$data_params['dazhe'],$data_params['freepost'],$data_params['mall']);
?>
<form action="<?php echo url2(true,'MallItemController','list_items','p=>' . construct_urlparams($data_params,$data['param_keys']));?>" method="get">
<ul>
<li>关键字:<input type="text" class="text" name="q" value="<?php echo $data['params']['q'];?>" size="15" maxlength="20" /></li>
<li>价格:<input type="text" class="text int" name="p1" value="<?php echo $data['params']['p1'];?>" size="5" maxlength="10" />-
<input type="text" class="text int" name="p2" value="<?php echo $data['params']['p2'];?>" size="5" maxlength="10" />元
</li>
<li><input type="checkbox" class="checkbox" <?php if($data['params']['huodao']) echo 'checked="checked"';?> name="huodao" value="1" />货到付款
<input type="checkbox" class="checkbox" <?php if($data['params']['dazhe']) echo 'checked="checked"';?> name="dazhe" value="1" />VIP打折
<input type="checkbox" class="checkbox" <?php if($data['params']['freepost']) echo 'checked="checked"';?> name="freepost" value="1" />免运费
<input type="checkbox" class="checkbox" <?php if($data['params']['mall']) echo 'checked="checked"';?> name="mall" value="1" />商城
</li>
<li>
<select name="cat">
<?php 
// 如果当前分类是父类，选择本身,否则选择父类
$pcatid = ($data['current_cat']->is_parent=='true')?$data['current_cat']->cid:$data['current_cat']->parent_cid;
?>
<option <?php if($pcatid==$data['params']['cat']) echo 'selected="selected"';?>  value="<?php echo $pcatid; ?>"><?php echo $data['all_cats'][$pcatid]->name;?></option>
<?php foreach($data['catArray'] as $cat) { ?>
	<option <?php if($cat['category_id']==$data['params']['cat']) echo 'selected="selected"';?> value="<?php echo $cat['category_id'];?>"><?php echo $data['all_cats'][$cat['category_id']]->name;?></option>
<?php } ?>
</select>
<li><input type="submit" class="s_button" name="submit" value="搜索" /></li>
</li>
</ul>
</form>
</div><!--condition-->
<div class="c"></div>