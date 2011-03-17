<?php
!function_exists('readover') && exit('Forbidden');
(!defined('SCR') || SCR != 'login') && exit('Forbidden');

$bindService = L::loadClass('weibobindservice', 'sns/weibotoplatform'); /* @var $bindService PW_WeiboBindService */
if ($action == 'weibologin') {
	$sessionId = GetCookie(PW_WEIBO_LOGIN_COOKIE_NAME);
	if (!$sessionId || !$bindService->getLoginSession($sessionId)) {
		$sessionId = $bindService->createLoginSession();
	}
	$bindService->updateLoginSession($sessionId, array('httpReferer' => $pre_url));
	
	Cookie(PW_WEIBO_LOGIN_COOKIE_NAME, $sessionId, $timestamp + PW_WEIBO_LOGIN_COOKIE_EXIPRE);
	
	$loginSinaWeiboUrl = $bindService->getLoginUrl($sessionId);
	ObHeader($loginSinaWeiboUrl);
} elseif ($action == 'weibologinregister') {
	$sessionId = GetCookie(PW_WEIBO_LOGIN_COOKIE_NAME);
	$sessionInfo = $bindService->getLoginSession($sessionId);
	if (!$sessionId || !$sessionInfo || !$sessionInfo['sessiondata']['sinaUser']) refreshto('login.php', '登录会话超时，请重试', 3);
	$weiboUser = $sessionInfo['sessiondata']['sinaUser'];
	
	/**
	 * 注册初始化
	 */
	require_once(R_P.'require/functions.php');
	$rg_config  = L::reg();
	$inv_config = L::config(null, 'inv_config');
	list($regminname,$regmaxname) = explode("\t", $rg_config['rg_namelen']);
	list($rg_regminpwd,$rg_regmaxpwd) = explode("\t", $rg_config['rg_pwdlen']);
	
	if ($db_pptifopen && $db_ppttype == 'client') Showmsg('passport_register');
	list($regq, , , ,$showq) = explode("\t", $db_qcheck);
	
	if (isRegClose()) Showmsg($rg_config['rg_whyregclose']);
	
	InitGP(array('step'));
	if ($step == 'doreg') {
		PostCheck(0, $db_gdcheck & 1, $regq, 0);
		if ($_GET['method'] || (!($db_gdcheck & 1) && $_POST['gdcode']) || (!$regq && ($_POST['qanswer'] || $_POST['qkey']))) {
			Showmsg('undefined_action');
		}
	
		InitGP(array('regreason','regname','regpwd','regpwdrepeat','regemail','customdata', 'regemailtoall','rgpermit'),'P');
		InitGP(array('question','customquest','answer'),'P');
		InitGP(array('useweiboavatar'));
		InitGP(array('invcode'));
		
		$regpwd = $regpwdrepeat = $bindService->generateLoginTmpPassword();
	
		$sRegpwd = $regpwd;
		$register = L::loadClass('Register', 'user');
		/** @var $register PW_Register */
	
		$rg_config['rg_allowregister']==2 && $register->checkInv($invcode);
		$register->checkSameNP($regname, $regpwd);
	
		$register->setStatus(11);
		$regemailtoall && $register->setStatus(7);
		$register->setName($regname);
		$register->setPwd($regpwd, $regpwdrepeat);
		$register->setEmail($regemail);
		$register->setSafecv($question, $customquest, $answer);
		$register->setReason($regreason);
		$register->setCustomfield(L::config('customfield','customfield'));
		$register->setCustomdata($customdata);
		$register->data['yz'] = 1; //round the email check
		$register->execute();
	
		if ($rg_config['rg_allowregister']==2) {
			$register->disposeInv();
		}
		list($winduid, $rgyz, $safecv) = $register->getRegUser();
		
		$windid  = $regname;
		$windpwd = md5($regpwd);
		
		if ($useweiboavatar) {
			require_once(R_P.'require/showimg.php');
			$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
			$userService->update($winduid, array('icon' => setIcon($weiboUser['avatar'], 2, array('', '', 80, 80))));
		}
		
		$isSuccess = $bindService->bindNewLoginUser($winduid, $sessionInfo['sessiondata']['platformSessionId'], array('randomPassword' => $regpwd));
		
		Cookie("winduser",StrCode($winduid."\t".PwdCode($windpwd)."\t".$safecv));
		Cookie("ck_info",$db_ckpath."\t".$db_ckdomain);
		Cookie('lastvisit','',0);

		$verifyhash = GetVerify($winduid);
		ObHeader("login.php?action=weibologinregister&step=finish&verify=$verifyhash");
	} elseif ($step == 'finish') {
		$loginUserInfo = $bindService->getLoginUserInfo($winduid);
		if (!$loginUserInfo) Showmsg('注册失败');
		
		require_once(R_P.'require/header.php');
		require_once(PrintEot('weibologin_register'));
		footer();
	}
	
	!$rg_config['rg_timestart'] && $rg_config['rg_timestart'] = 1960;
	!$rg_config['rg_timeend'] && $rg_config['rg_timeend'] = 2000;
	$img = @opendir("$imgdir/face");
	while ($imagearray = @readdir($img)) {
		if ($imagearray!="." && $imagearray!=".." && $imagearray!="" && $imagearray!="none.gif") {
			$imgselect.="<option value='$imagearray'>$imagearray</option>";
		}
	}
	@closedir($img);
	
	require_once(R_P.'require/header.php');
	$showq = intval($showq);
	$custominfo = unserialize($db_union[7]);
	$customfield = L::config('customfield','customfield');
	require_once(PrintEot('weibologin_register'));
	footer();
} elseif ($action == 'weibologinbind') {
	$sessionId = GetCookie(PW_WEIBO_LOGIN_COOKIE_NAME);
	$sessionInfo = $bindService->getLoginSession($sessionId);
	if (!$sessionId || !$sessionInfo || !$sessionInfo['sessiondata']['sinaUser']) refreshto('login.php', '登录会话超时，请重试', 3);
	$weiboUser = $sessionInfo['sessiondata']['sinaUser'];
	
	InitGP(array('step'));
	if (2 == $step) {
		PostCheck(0,$db_gdcheck & 2,$loginq,0);
		require_once(R_P . 'require/checkpass.php');

		InitGP(array('pwuser','pwpwd','question','customquest','answer','cktime','hideid','jumpurl','lgt','keepyear'),'P');
		if (!$pwuser || !$pwpwd) Showmsg('login_empty');
		
		$loginUser = array('username' => $pwuser, 'password' => md5($pwpwd));
		$loginUser['safecv'] = $db_ifsafecv ? questcode($question, $customquest, $answer) : '';
		list($winduid, $groupid, $windpwd, $showmsginfo) = processLogin(null, $loginUser, $cktime, $lgt);
		
		require_once(file_exists(D_P."data/groupdb/group_$groupid.php") 
			? Pcv(D_P."data/groupdb/group_$groupid.php") : D_P."data/groupdb/group_1.php");
		($_G['allowhide'] && $hideid) ? Cookie('hideid',"1",$cktime) : Loginipwrite($winduid);
	
		if (GetCookie('o_invite') && $db_modes['o']['ifopen'] == 1) {
			list($o_u,$hash,$app) = explode("\t",GetCookie('o_invite'));
			if (is_numeric($o_u) && strlen($hash) == 18) {
				require_once(R_P.'require/o_invite.php');
			}
		}
		if (empty($jumpurl) || false !== strpos($jumpurl, $regurl)) {
			$jumpurl = isset($sessionInfo['sessiondata']['httpReferer']) ? $sessionInfo['sessiondata']['httpReferer'] : $db_bfn;
		}
		//passport
		if ($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)) {
			$tmp = $jumpurl;
			$jumpurl = $forward ? $forward : $db_ppturls;
			$forward = $tmp;
			//TODO 这里面有obheader，用到$action
			require_once(R_P.'require/passport_server.php');
		}
		//passport
		
		$isSuccess = $bindService->bindExistLoginUser($winduid, $sessionInfo['sessiondata']['platformSessionId']);
		
		refreshto($jumpurl,'have_login');
	}
	
	$arr_logintype = array();
	if ($db_logintype) {
		for ($i = 0; $i < 3; $i++) {
			if ($db_logintype & pow(2,$i)) $arr_logintype[] = $i;
		}
	} else {
		$arr_logintype[0] = 0;
	}
	
	require_once(R_P.'require/header.php');
	require_once(PrintEot('weibologin_bind'));
	footer();
} elseif ($action == 'weibologinroute') {
	$sessionId = GetCookie(PW_WEIBO_LOGIN_COOKIE_NAME);
	$sessionInfo = $bindService->getLoginSession($sessionId);
	if (!$sessionId || !$sessionInfo || !$sessionInfo['sessiondata']['platformSessionId']) Showmsg('验证会话错误，请重试');
	
	if (!$sessionInfo['sessiondata']['isBound']) {
		$jumpurl = !isRegClose() ? 'login.php?action=weibologinregister' : 'login.php?action=weibologinbind';
		//$jumpnow = 1;
		$msg_info = '新浪微博帐号认证通过（窗口将自动关闭）';
		extract(L::style('',$skinco));
		require_once PrintEot('weibologin_notice');
		pwOutPut();
		exit;
	}
		
	$userId = $bindService->fetchBoundUser($sessionInfo['sessiondata']['platformSessionId']);
	if (!$userId) Showmsg('新浪帐号自动登录失败，请重试');
	
	$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
	if (!$userService->get($userId)) {
		$bindService->callPlatformUnBind($userId, PW_WEIBO_BINDTYPE_SINA);
		Showmsg('用户在站点已删除，请重试');
	}
		
	list($winduid, $groupid, $windpwd, $showmsginfo) = processLogin($userId);

	require_once(file_exists(D_P."data/groupdb/group_$groupid.php") 
		? Pcv(D_P."data/groupdb/group_$groupid.php") : D_P."data/groupdb/group_1.php");
	Loginipwrite($winduid);

	if (GetCookie('o_invite') && $db_modes['o']['ifopen'] == 1) {
		list($o_u,$hash,$app) = explode("\t",GetCookie('o_invite'));
		if (is_numeric($o_u) && strlen($hash) == 18) {
			require_once(R_P.'require/o_invite.php');
		}
	}
	$jumpurl = isset($sessionInfo['sessiondata']['httpReferer']) ? $sessionInfo['sessiondata']['httpReferer'] : $db_bfn;
	//passport
	if ($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)) {
		$tmp = $jumpurl;
		$jumpurl = $forward ? $forward : $db_ppturls;
		$forward = $tmp;
		//TODO 这里面有obheader，用到$action
		require_once(R_P.'require/passport_server.php');
	}
	//passport
	
	$msg_info = '使用新浪微博帐号登录成功（窗口将自动关闭）';
	extract(L::style('',$skinco));
	require_once PrintEot('weibologin_notice');
	pwOutPut();
}


function processLogin($userId, $user = null, $cktime = '31536000', $lgt = 0) {
	global $timestamp, $db_ckpath, $db_ckdomain, $db_autoban;
	if (!$user) {
		$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
		$user = $userService->get($userId, true, true);
	}
	
	$pwuser = $user['username'];
	$md5_pwpwd = $user['password'];
	$safecv = $user['safecv'];
	
	require_once(R_P . 'require/checkpass.php');
	$logininfo = checkpass($pwuser, $md5_pwpwd, $safecv, $lgt);
	if (!is_array($logininfo)) {
		Showmsg($logininfo);
	}
	list($winduid, , $windpwd, ) = $logininfo;
		
	/*update cache*/
	$_cache = getDatastore();
	$_cache->delete("UID_".$winduid);
	
	$cktime != 0 && $cktime += $timestamp;
	Cookie("winduser",StrCode($winduid."\t".$windpwd."\t".$safecv),$cktime);
	Cookie("ck_info",$db_ckpath."\t".$db_ckdomain);
	Cookie('lastvisit','',0);//将$lastvist清空以将刚注册的会员加入今日到访会员中
	
	if ($db_autoban) {
		require_once(R_P.'require/autoban.php');
		autoban($winduid);
	}
	
	return $logininfo;
}

function isRegClose() {
	global $timestamp;
	$rg_config  = L::reg();
	return $rg_config['rg_allowregister'] == 0 
		|| ($rg_config['rg_registertype'] == 1 && date('j',$timestamp) != $rg_config['rg_regmon']) 
		|| ($rg_config['rg_registertype'] == 2 && date('w',$timestamp) != $rg_config['rg_regweek']);
}

