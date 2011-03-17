<?php
$propsCache = Doo::cache();
$props = $propsCache->get('products_props');
if(!$props) {
	$meirong_props = require_once('data/head_nav_products_props.php');
	$props = head_nav_block($meirong_props);
	$propsCache->set('products_props',$props);
}

echo $props;
?>