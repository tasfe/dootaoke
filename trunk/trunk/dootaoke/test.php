<?php
//$p = '/^\d+:\d+(;\d+:\d+)*$/i';
//$t = '123213:2232;1212:1212;1212:1212';

//echo preg_match($p,$t);
//echo is_numeric('-121.2');
$props = array('50011990_brand_prop.php',
'50011977_brand_prop.php',
'50011978_brand_prop.php',
'50011996_brand_prop.php',
'50011979_brand_prop.php',
'50011980_brand_prop.php',
'50011986_brand_prop.php',
'50011981_brand_prop.php',
'50011982_brand_prop.php',
'50011997_brand_prop.php',
'50011995_brand_prop.php',
'50011983_brand_prop.php',
'50011987_brand_prop.php',
'50011992_brand_prop.php',
'50011994_brand_prop.php',
'50011993_brand_prop.php',
'50011988_brand_prop.php',
'50011998_brand_prop.php',
'50011991_brand_prop.php',
);


$props = array(
'50031311_brand_prop.php',
'50010815_brand_prop.php',
'50010817_brand_prop.php',
'50010810_brand_prop.php',
'50010793_brand_prop.php',
'50010789_brand_prop.php',
'50010790_brand_prop.php',
'50010803_brand_prop.php',
'50010796_brand_prop.php',
'50010794_brand_prop.php',
'50010797_brand_prop.php',
'50010798_brand_prop.php',
'50010805_brand_prop.php',
'50010792_brand_prop.php',
'50010936_brand_prop.php',
'50010807_brand_prop.php',
'50010808_brand_prop.php',
'50010801_brand_prop.php',
'50010800_brand_prop.php',
'50010812_brand_prop.php',
'50044975_brand_prop.php',
'50044977_brand_prop.php',
'50044978_brand_prop.php',
'50044979_brand_prop.php',
'50044980_brand_prop.php',
);
$_G = array();
$result = array();
foreach($props as $p) {
	require('protected/class/data/props/' . $p);
	
	$prop_value = $_G['default_brand']['prop_values']['prop_value'];
	foreach($prop_value as $prop) {
		$result[$prop['vid']] = $prop['name'];
	}
}
print_r($result);
?>