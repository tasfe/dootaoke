<?php
$propsCache = Doo::cache();
$props = $propsCache->get('caizhuang_props');
if(!$props) {
	$meirong_props = require_once('data/head_nav_caizhuang_props.php');
	$props = head_nav_block($meirong_props);
	$propsCache->set('caizhuang_props',$props);
}

echo $props;
?>