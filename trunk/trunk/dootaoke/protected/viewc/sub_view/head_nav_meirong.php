<?php
$propsCache = Doo::cache();
$props = $propsCache->get('meirong_props');
if(!$props) {
	$meirong_props = require_once('data/head_nav_meirong_props.php');
	$props = head_nav_block($meirong_props);
	$propsCache->set('meirong_props',$props);
}

echo $props;
?>