<?php
!defined('M_P') && exit('Forbidden');
S::gp(array('action', 'step', 'cid', 'ajax','iscontinue'));
if ($ajax == 1) define('AJAX', '1');

if (!getPostPurview($windid, $_G) && !checkEditPurview($windid, $cid)) Showmsg('您没有发表文章的权限');
$articleService = C::loadClass('articleservice'); /* @var $articleService PW_ArticleService */
cmsSeoSettings();

if (!$action) {
	if (!$step) {
		$pagePosition = $cms_sitename ? "<a href='index.php?m=cms'>$cms_sitename</a>":'<a href="index.php?m=cms">首页</a>';
		S::gp(array('sourcetype', 'sourceid'));
		$columnService = C::loadClass('columnservice');
		/* @var $columnService PW_columnService */
		$columns = $columnService->getAllOrderColumns(0,$windid);
		$purviews = $columnService->getAllPurviewColumns($windid);

		$hasSource = isGM($windid) || checkEditPurview($windid) ? true : false;//栏目编辑或创始人才有权限使用自动调用
		if (!$hasSource) {
			$sourcetype = $sourceid = null;
		}
		$articleModule = $articleService->getArticleModuleFromSource($sourcetype, $sourceid);
		$content = $articleModule->content;
		$articleModule->setColumnId($cid);

		list($filetype, $filetypeinfo) = initFileTypeInfo($db_uploadfiletype);
		require_once (M_P . 'require/header.php');
	} else {
		S::gp(array('cms_subject', 'atc_content', 'cms_descrip'), 'P', 0);
		S::gp(array('cms_sourcetype', 'cms_sourceid', 'cid', 'cms_jumpurl', 'cms_author', 'cms_frominfo',
			'cms_fromurl', 'cms_relate', 'addnewpage', 'cms_timelimit'));
		$cms_timelimit = ($cms_timelimit && (isGM($windid) || checkEditPurview($windid))) ? PwStrtoTime($cms_timelimit) : $timestamp;
		$cms_jumpurl = ($cms_jumpurl && (isGM($windid) || checkEditPurview($windid))) ? $cms_jumpurl : '';
		
		PostCheck();

		$columnService = C::loadClass('columnservice');
		$column = $columnService->findColumnById((int)$cid);
		$purviews = $columnService->getAllPurviewColumns($windid);
		if (!windid && !isGM($windid) && !in_array($column['column_id'], $purviews) && (!$column['allowoffer'] || !getPostPurview($windid, $_G))) {
			Showmsg('你没有权限向本栏目添加文章');
		}

		$articleModule = C::loadClass('articleModule'); /* @var $articleModule PW_ArticleModule */

		$articleModule->setSubject($cms_subject);
		$articleModule->setContent($atc_content);
		$articleModule->setDescrip($cms_descrip);
		$articleModule->setColumnId($cid);
		$articleModule->setJumpUrl($cms_jumpurl);
		$articleModule->setPostDate($cms_timelimit);
		$articleModule->setModifyDate($timestamp);
		$articleModule->setFromInfo($cms_frominfo);
		$articleModule->setFromUrl($cms_fromurl);
		$articleModule->setAuthor($cms_author);
		$articleModule->setUser($windid);
		$articleModule->setUserId($winduid);
		$articleModule->setIfCheck(1);
		$articleModule->setSourceType($cms_sourcetype);
		$articleModule->setSourceId($cms_sourceid);
		$articleModule->setRelate($cms_relate);
		$articleModule->setAttach();
		$articleModule->showError();

		$result = $articleService->addArticle($articleModule);
		

		if ($result) {
			$jumpUrl = $addnewpage ? $basename . "q=post&action=edit&id=" . $result . "&page=add" : $basename . "q=view&id=" . $result;

			$columnService = C::loadClass('columnservice'); /* @var $columnService PW_ColumnService */
			$cname = $columnService->getColumnNameByCIds($cid);
			$weiboService = L::loadClass('weibo','sns');/* @var $weiboService PW_Weibo */
			$weiboContent = substrs(stripWindCode($weiboService->escapeStr($articleModule->descrip)), 125);
			$weiboExtra = array(
							'title' => stripslashes($cms_subject),
							'cid' => $cid,
							'cname' => $cname,
							);
			$weiboService->send($winduid,$weiboContent,'cms',$result,$weiboExtra);
			
		    $msg = defined('AJAX') ?  "success\t".urlRewrite($jumpUrl) : '添加文章成功!';	
		    refreshto($jumpUrl, $msg);
		} else {
			Showmsg('添加文章失败');
		}
	}
} elseif ($action == 'edit') {
	S::gp(array('id', 'page'));
	$articleModule = $articleService->getArticleModule($id);
	$userid = $articleModule->userId;
	if (!checkEditPurview($windid, $articleModule->columnId) && $userid != $winduid) Showmsg('你没有权限编辑本栏目的文章');
	if (!$step) {
		if (!$page) $page = 1;
		if (!is_object($articleModule)) Showmsg('文章不存在');
		
		$pagePosition = getPosition($articleModule->columnId,'','',$cms_sitename);

		$columnService = C::loadClass('columnservice'); /* @var $columnService PW_columnService */
		$columns = $columnService->getAllOrderColumns(0,$windid);

		$attach = initAttach($articleModule->attach);
		$postdate = get_date($articleModule->postDate);

		$content = $articleModule->getPageContent($page);
		$articleModule->showError();

		$pages = $articleModule->getPages($page, CMS_BASEURL.'q=post&action=edit&id=' . $id . '&');

		list($filetype, $filetypeinfo) = initFileTypeInfo($db_uploadfiletype);
		require_once (M_P . 'require/header.php');
	} else {
		S::gp(array('cms_subject', 'atc_content', 'cms_descrip'), 'P', 0);
		S::gp(array('cms_sourcetype', 'cms_sourceid', 'cid', 'cms_jumpurl', 'cms_author', 'cms_frominfo',
			'cms_fromurl', 'cms_relate', 'keep', 'oldatt_desc', 'addnewpage', 'cms_timelimit'));
		$cms_timelimit = ($cms_timelimit && (isGM($windid) || checkEditPurview($windid))) ? PwStrtoTime($cms_timelimit) : $timestamp;
		$cms_jumpurl = ($cms_jumpurl && (isGM($windid) || checkEditPurview($windid))) ? $cms_jumpurl : '';
		PostCheck();
		$articleModule->setSubject($cms_subject);
		$articleModule->setContent($atc_content, $page);
		$articleModule->setDescrip($cms_descrip);
		$articleModule->setColumnId($cid);
		$articleModule->setJumpUrl($cms_jumpurl);
		$articleModule->setPostDate($cms_timelimit);
		$articleModule->setModifyDate($timestamp);
		$articleModule->setFromInfo($cms_frominfo);
		$articleModule->setFromUrl($cms_fromurl);
		$articleModule->setAuthor($cms_author);
		$articleModule->setUser($windid);
		$articleModule->setUserId($winduid);
		$articleModule->setRelate($cms_relate);
		$articleModule->setSourceType($cms_sourcetype);
		$articleModule->setSourceId($cms_sourceid);
		$articleModule->setAttach($oldatt_desc, $keep);
		$articleModule->showError();

		$result = $articleService->updateArticle($articleModule);
		if ($result) {
			$jumpUrl = $addnewpage ? $basename . "q=post&action=edit&id=" . $id . "&page=add" : $basename . "q=view&id=" . $id;
			$msg = defined('AJAX') ?  "success\t".$jumpUrl : '修改文章成功!';	
		    refreshto($jumpUrl, $msg);
		} else {
			Showmsg('修改文章失败');
		}
	}
} elseif ($action == 'deletepage') {
	S::gp(array('id', 'page'));
	$articleModule = $articleService->getArticleModule($id);

	if (!checkEditPurview($windid, $articleModule->columnId) && $articleModule->user != $windid) Showmsg('你没有权限编辑本栏目的文章');

	$articleModule->deletePage($page);
	$articleModule->showError();
	$result = $articleService->updateArticle($articleModule);
	if ($result) {
		refreshto("{$basename}q=post&action=edit&id=$id&page=1", 'operate_success', 2);
	} else {
		Showmsg('删除分页失败');
	}
}

require cmsTemplate::printEot('post');
footer();

function initFileTypeInfo($db_uploadfiletype) {
	$uploadfiletype = ($db_uploadfiletype) ? unserialize($db_uploadfiletype) : '';
	$filetypeinfo = $filetype = '';
	if ($uploadfiletype) {
		foreach ($uploadfiletype as $type => $size) {
			$filetype .= ' ' . $type . ' ';
			$filetypeinfo .= $type . ":" . $size . "KB; ";
		}
	}
	return array($filetype, $filetypeinfo);
}

function initAttach($attachs) {
	$attach = '';
	if ($attachs) {
		foreach ($attachs as $key => $value) {
			$attach .= "'$key' : ['$value[name]', '$value[size]', '$value[attachurl]', '$value[type]', '', '', '', '$value[descrip]'],";
		}
		$attach = rtrim($attach, ',');
	}
	return $attach;
}


?>