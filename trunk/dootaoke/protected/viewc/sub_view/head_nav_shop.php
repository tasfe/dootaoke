<?php
$propsCache = Doo::cache();
$props = $propsCache->get('shop_props');
if(!$props) {
	$meirong_props = require_once('data/head_nav_shop_props.php');
	$props = head_nav_block($meirong_props);
	$propsCache->set('shop_props',$props);
}

echo $props;
?>