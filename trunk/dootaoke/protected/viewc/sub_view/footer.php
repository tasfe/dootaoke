<div class="c"></div>
<div id="footer">
<div><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/f_service.png"></div>
<div class="f_links"><a href="<?php echo Doo::conf()->SUBFOLDER; ?>blog/article/9" target="_blank">关于再美点</a>|<a href="<?php echo Doo::conf()->SUBFOLDER; ?>blog" target="_blank">官方博客</a>|<a href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/sendemail.php?username=admin" target="_blank">联系我们</a></div>
<div class="copyright">Copyright © 2011 <a href="<?php echo Doo::conf()->SUBFOLDER; ?>">再美点网</a> 版权所有  All Rights Reserved.粤ICP备10075949号 </div>
<div class="anquan">
<a href="http://www.bjjubao.org/index.htm" target="_blank"><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/beian/blxx.gif"></a>
<a href="http://www.miibeian.gov.cn/" target="_blank"><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/beian/icp_ba.gif"></a>
<a href="http://www.cyberpolice.cn/index.htm" target="_blank"><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/beian/wl_110.jpg"></a>
<a href="http://www.315online.com.cn/" target="_blank"><img src="<?php echo Doo::conf()->SUBFOLDER; ?>global/img/beian/wsjy_bz.gif"></a>
</div>
</div>
<?php
// 程序运行时间
global $time_start;
$time_end = explode(' ', microtime()); 
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
?>
<div style="display:none;"><?php echo $parse_time;?></div>

<?php
// is robot
$is_robot = is_robot();

$autoClearCache=false;
if(!$is_robot && checkClearTime(AUTOCLEAR_CACHETIME,FILE_CACHE_PATH)) {
	$autoClearCache=true;//autoClearCache(CACHE_PATH,AUTOCLEAR_CACHETIME,array('catpath','cat'));
}
if($autoClearCache) {
//定期获得淘宝，联盟报告
?>
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>cache/clear"></script>
<?php }?>
</div><!--#page-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22418689-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>