<?php
!function_exists('readover') && exit('Forbidden');

InitGP(array('t'));

if (empty($t) || $t == 'resetpwd') {

	!isset($userdb['sinaweibo_isfollow']) && $userdb['sinaweibo_isfollow'] = 1;
	ifchecked('sinaweibo_isfollow', $userdb['sinaweibo_isfollow']);
			
	$bindService = L::loadClass('weibobindservice', 'sns/weibotoplatform'); /* @var $bindService PW_WeiboBindService */
	$bindInfo = $bindService->getLocalBindInfo($winduid, PW_WEIBO_BINDTYPE_SINA);
	$isBindWeibo = (bool) $bindInfo;
	if (!$isBindWeibo) {
		$bindSinaWeiboUrl = $bindService->getBindUrl($winduid);
	} else {
		$syncer = L::loadClass('WeiboSyncer', 'sns/weibotoplatform'); /* @var $syncer PW_WeiboSyncer */
		$syncSetting = $syncer->getUserWeiboSyncSetting($winduid);
		ifchecked('article_issync', $syncSetting['article']);
		ifchecked('diary_issync', $syncSetting['diary']);
		ifchecked('photos_issync', $syncSetting['photos']);
		ifchecked('group_issync', $syncSetting['group']);
		ifchecked('transmit_issync', $syncSetting['transmit']);
		ifchecked('comment_issync', $syncSetting['comment']);
	}
	
	$isNotResetPassword = $bindService->isLoginUserNotResetPassword($winduid);
		
	require_once uTemplate::printEot('profile_weibobind');
	pwOutPut();
} elseif ($t == 'unbind') {
	$bindService = L::loadClass('weibobindservice', 'sns/weibotoplatform'); /* @var $bindService PW_WeiboBindService */
	if ($bindService->isLoginUserNotResetPassword($winduid)) refreshto('profile.php?action=weibobind&t=resetpwd', '你的帐号未创建密码，请创建密码后再解除绑定');
	
	$got = $bindService->callPlatformUnBind($winduid, PW_WEIBO_BINDTYPE_SINA);

	refreshto('profile.php?action=weibobind','operate_success');
} elseif ($t == 'setsync') {
	PostCheck();
	InitGP(array('article_issync', 'diary_issync', 'photos_issync', 'group_issync', 'transmit_issync', 'comment_issync'), 'P', 2);
	$syncSetting = array(
		'article' => (bool) $article_issync,
		'diary' => (bool) $diary_issync,
		'photos' => (bool) $photos_issync,
		'group' => (bool) $group_issync,
		'transmit' => (bool) $transmit_issync,
		'comment' => (bool) $comment_issync,
	);
	$syncer = L::loadClass('WeiboSyncer', 'sns/weibotoplatform'); /* @var $syncer PW_WeiboSyncer */
	$syncer->updateUserWeiboSyncSetting($winduid, $syncSetting);

	refreshto('profile.php?action=weibobind','operate_success');
} elseif ($t == 'setpassword') {
	PostCheck();
	InitGP(array('resetpwd', 'resetpwd_repeat'), 'P');
	if ('' == $resetpwd || '' == $resetpwd_repeat) Showmsg('创建密码不能为空');
	
	$rg_config  = L::reg();
	list($rg_regminpwd,$rg_regmaxpwd) = explode("\t", $rg_config['rg_pwdlen']);
	$register = L::loadClass('Register', 'user');
	$register->checkPwd($resetpwd, $resetpwd_repeat);
	
	$bindService = L::loadClass('weibobindservice', 'sns/weibotoplatform'); /* @var $bindService PW_WeiboBindService */
	$bindService->resetLoginUserPassword($winduid, $resetpwd);
	
	$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
	$user = $userService->get($winduid);
	Cookie("winduser",StrCode($winduid."\t".PwdCode($user['password'])."\t".$user['safecv']));
	Cookie("ck_info",$db_ckpath."\t".$db_ckdomain);
	Cookie('lastvisit','',0);
	
	refreshto('profile.php?action=weibobind&t=resetpwd','operate_success');
	
} elseif ($t == 'bindsuccess') {
	extract(L::style('',$skinco));
	
	$msg_info = '绑定新浪帐号成功（窗口将自动关闭）';
	require_once uTemplate::printEot('profile_privacy_bindsuccess');
	pwOutPut();
}

function ifchecked($out, $var) {
	$GLOBALS[$out] = $var ? ' checked' : '';
}
