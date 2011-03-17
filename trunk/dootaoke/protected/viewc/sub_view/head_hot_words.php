<?php 
$hot_words = array('相宜本草'=>'1801','保湿'=>'1801','BB霜'=>'1801','雅顿'=>'1801','雅诗兰黛'=>'1801','贝佳斯绿泥'=>'1801','DHC'=>'1801',' 欧莱雅'=>'1801');

foreach($hot_words as $word  => $cid) {
?>
<li><a href="<?php echo Doo::conf()->APP_URL; ?>list/cat-<?php echo $cid;?>-q-<?php echo urlencode($word);?>"><?php echo $word;?></a><s></s></li>
<?php } ?>