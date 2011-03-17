<DIV id="site_nav">
<div class="site_nav_box">
	<DIV class="about">
	<ul>
		<LI><a href="javascript:void(0);" id="add_fav">收藏本站</a></LI>
		<LI><a href="javascript:void(0);" id="set_home">设为主页</a></LI>
	</ul>
    </DIV>
	<DIV class="quick-link">
		<UL>
			<LI id="welcome"><span>您好，欢迎您！</span></LI>
			<LI class="not_login"><A href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/login.php">登录</A></LI>
			<LI class="not_login"><A href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/register.php">注册</A></LI>
			<li class="none logined"><a id="username" class="mr10 b" href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/u.php"></a><a id="logout" href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/login.php?action=quit&verify=">退出</a></li>
			<li class="none logined"><a href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/message.php">消息</a></li>
			<li class="none logined"><a href="<?php echo Doo::conf()->SUBFOLDER; ?>sns/u.php">个人中心</a></li>
			<LI><A href="<?php echo Doo::conf()->SUBFOLDER; ?>blog" target="_blank">帮助</A></LI>
		</UL>
		<script language="javascript">
		ajax_login_user('<?php echo Doo::conf()->SUBFOLDER; ?>sns/mode.php?m=area&q=user');
		</script>				
	</DIV>
<div class="c"></div>
</div>
<div class="c"></div>
</DIV>