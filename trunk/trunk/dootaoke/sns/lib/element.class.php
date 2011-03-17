<?php
!function_exists('readover') && exit('Forbidden');

require_once(R_P.'require/functions.php');
/**
 * Element class
 *
 * @copyright phpwind
 * @author xiaolang
 * @package Element
 */
class PW_Element{
	var $db;
	var $defaultnum;
	var $ifpwcache;

	/**
	 * 构造函数
	 *
	 * @param int $defaultnum
	 * @return Element
	 */
	function PW_Element($defaultnum=0){
		global $db,$db_ifpwcache;
		$this->defaultnum 	= 10;
		$this->ifpwcache	= $db_ifpwcache;
		$this->db 			= $db;
	}

	function setDefaultNum($defaultnum){
		$this->defaultnum 	= intval($defaultnum) ? intval($defaultnum) : 10;
	}

	/**
	 * 实例化getinfo：由于getinfo类采用了单件模式，所以实例化比较特殊
	 *
	 * @param bool $reality
	 * @param num $num
	 * @return object
	 */
	function singLeton($reality,$num){
		L::loadClass('getinfo', '', false);
		$info =& GetInfo::getInstance($reality);
		$info->cachenum = $num;
		return $info;
	}

	function _getBlackList($field, $list) {
		return $list ? " AND $field NOT IN(" . S::sqlImplode(explode(',', $list)) . ')' : '';
	}
	/**
	 * 获取最新帖
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function newSubject($round='',$num=0,$special=0){
		$num 	= intval($num) ? intval($num) : $this->defaultnum;
		$special = (int) $special;
		$sqladd = '';
		$posts 	= array();
		$fid = $this->_cookFid($round);
		if ($this->ifpwcache & 128) {
			$sqladd .= ' AND e.special='.S::sqlEscape($special);
			$sqladd .= $this->_getBlackList('e.id', $GLOBALS['db_tidblacklist']);
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			$query = $this->db->query("SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type='newsubject' $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
			while ($rt = $this->db->fetch_array($query)) {
				$posts[] = $this->_cookSubjectData($rt);
			}
		} else {
			$info = $this->singLeton(true,$num);
			$posts = $info->getPostList('newsubject',$fid,$info->cachenum,0,$special);
		}
		return $posts;
	}
	
	/**
	 * 获取最新回复
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function newReply($round='',$num=0,$special=0){
		global $db_ptable;
		$fid = $this->_cookFid($round);
		$num 	= intval($num) ? intval($num) : $this->defaultnum;

		if ($this->ifpwcache & 256) {
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			$sqladd .= $this->_getBlackList('e.id', $GLOBALS['db_tidblacklist']);
			$query = $this->db->query("SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type='newreply' $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
			while ($rt = $this->db->fetch_array($query)) {
				$posts[] = $this->_cookSubjectData($rt);
			}
		} else {
			$info = $this->singLeton(true,$num);
			$posts = $info->getPostList('newreply',$fid,$info->cachenum);
		}
		return $posts;
	}
	/**
	 * 获取精华帖
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function digestSubject($round=0,$num=0,$special=0){
		!in_array($special,array(1,2)) && $special = 0;
		$fid = $this->_cookFid($round);
		$num 	= intval($num) ? intval($num) : $this->defaultnum;
		$sqladd = '';
		$fid && $sqladd .= " AND fid IN ($fid) ";
		$sqladd .= $special ? ' AND digest='.S::sqlEscape($special) : "AND digest>'0'";
		$sqladd .= $this->_getBlackList('tid', $GLOBALS['db_tidblacklist']);
		$sql	= "SELECT tid,fid,author,authorid,subject,type,postdate,hits,replies,digest FROM pw_threads FORCE INDEX (".getForceIndex('idx_digest').") WHERE ifcheck=1 $sqladd ORDER BY tid DESC ".S::sqlLimit($num);
		$query 	= $this->db->query($sql);
		while($rt = $this->db->fetch_array($query)){
			$posts[] = $this->_cookSubjectData($rt);
		}
		return $posts;
	}
	/**
	 * 获取置顶帖
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function topSubject($round=0,$num=0,$special=3){
		$toppedtype = array(1,2,3);
		!in_array($special,$toppedtype) && $special = 3;
		$fid = $this->_cookFid($round);
		$num 	= intval($num) ? intval($num) : $this->defaultnum;
		$sqladd = '';
		if ($special==3) {
			include pwCache::getPath(D_P."data/bbscache/toppeddb.php");
			if ($toppeddb[3][1]) {
				$toptids = S::sqlImplode(explode(',',$toppeddb[3][1]));
				$sqladd .= "AND tid IN($toptids)";
			} else {
				return false;
			}
		} elseif ($special==2 && $fid) {
			include pwCache::getPath(D_P."data/bbscache/toppeddb.php");
			if ($toppeddb[2][$fid][2]) {
				$toptids = S::sqlImplode(explode(',',$toppeddb[2][$fid][2]));
				$sqladd .= "AND tid IN($toptids)";
			} else {
				return false;
			}
		} else {
			$fid && $sqladd .= " AND fid IN ($fid) ";
			$sqladd .= ' AND topped='.S::sqlEscape($special);
		}
		$sqladd .= $this->_getBlackList('tid', $GLOBALS['db_tidblacklist']);
		$sql = "SELECT tid,fid,author,authorid,subject,type,postdate,hits,replies FROM pw_threads WHERE ifcheck='1' $sqladd ORDER BY lastpost DESC ".S::sqlLimit($num);
		$posts = array();
		$query = $this->db->query($sql);
		while($rt = $this->db->fetch_array($query)){
			$posts[] = $this->_cookSubjectData($rt);
		}
		return $posts;
	}

	function highLightSubject($round=0,$num=0,$special=0){
		$fid = $this->_cookFid($round);
		$num 	= intval($num) ? intval($num) : $this->defaultnum;
		$sqladd = '';
		$fid && $sqladd .= " AND t.fid IN ($fid) ";
		$sqladd .= $this->_getBlackList('t.tid', $GLOBALS['db_tidblacklist']);
		$sql = "SELECT DISTINCT t.tid,t.titlefont,t.fid,t.postdate,t.author,t.authorid,t.subject FROM pw_adminlog a LEFT JOIN pw_threads t ON a.field2=t.tid WHERE a.type='highlight' ".$sqladd." ORDER BY timestamp DESC ".S::sqlLimit($num);
		$posts = array();
		$query = $this->db->query($sql);
		while($rt = $this->db->fetch_array($query)){
			if (!$rt['titlefont']) continue;
			$posts[] = $this->_cookSubjectData($rt);
		}
		return $posts;
	}
	/**
	 * 版块排行
	 *
	 * @param string $type 	:topic：帖子总数，article：主题数，tpost：今日发帖数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function forumSort($round='topic',$num=0,$special=0){
		!in_array($round,array('topic','article','tpost')) && $round = 'topic';
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$forum = array();
		$query = $this->db->query("SELECT f.fid,f.name,f.forumadmin,fd.tpost,fd.topic,fd.article,fd.subtopic,fd.top1,fd.top2 as value FROM pw_forumdata fd LEFT JOIN pw_forums f USING(fid) WHERE f.password='' AND f.allowvisit='' AND f.f_type<>'hidden' AND f.type<>'category' AND f.cms<>1 ORDER BY fd.$round DESC ".S::sqlLimit($num));
		while($rt = $this->db->fetch_array($query)){
			$tem = array();
			$tem['url'] 	= 'thread.php?fid='.$rt['fid'];
			$tem['title'] 	= strip_tags($rt['name']);
			$tem['value'] 	= $rt[$round];
			$tem['image']	= $rt['logo'];
			$tem['addition']= $rt;
			$forum[] = $tem;
		}
		return $forum;
	}

	function cates($round='',$num=0,$special=0){
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$query = $this->db->query("SELECT fid,name,logo,descrip FROM pw_forums WHERE type='category' AND cms<>1 ORDER BY vieworder ".S::sqlLimit($num));
		$catedbs = array();
		while ($rt = $db->fetch_array($query)) {
			$tem = array();
			$tem['url'] 	= 'cate.php?cateid='.$rt['fid'];
			$tem['title'] 	= strip_tags($rt['name']);
			$tem['value'] 	= '';
			$tem['image']	= $rt['logo'];
			$tem['addition']= $rt;
			$catedbs[] = $tem;
		}
		return $catedbs;
	}
	/**
	 * 获取最新图片
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function newPic($round=0,$num=0,$special=0){
		global $db_ftpweb,$attachpath;
		$fid = $this->_cookFid($round);
		$num 	= intval($num) ? intval($num) : $this->defaultnum;
		$newpic = array();
		if ($this->ifpwcache & 512) {
			$delete = array();
			$sqladd = '';
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			$query = $this->db->query("SELECT e.eid,e.addition,e.special,t.tid,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies,a.ifthumb FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid LEFT JOIN pw_attachs a ON e.value=a.aid WHERE e.type='newpic' $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
			while ($rt = $this->db->fetch_array($query)) {
				$addition = unserialize(stripslashes($rt['addition']));
				$pic = geturl($addition[0],'show',$rt['ifthumb']);
				if (!is_array($pic)) {
					$delete[] = $rt['eid'];
					continue;
				}
				$tem = array();
				$tem['url'] 	= 'read.php?tid='.$rt['tid'];
				$tem['title'] 	= $rt['subject'];
				$tem['value'] 	= $addition[1];
				$tem['image']	= $pic[0];
				$tem['forumname']	= getForumName($rt['fid']);
				$tem['forumurl']	= getForumUrl($rt['fid']);
				list($tem['topictypename'],$tem['topictypeurl']) = getTopicType($rt['type'],$rt['fid']);
				$tem['addition']= $rt;
				$newpic[] = $tem;
			}
			if ($delete) {
				$this->db->query("DELETE FROM pw_elements WHERE eid IN(".S::sqlImplode($delete).")");
				return $this->newPic($round,$num,$special);
			}
		} else {
			$info = $this->singLeton(true,$num);
			$newpic = $info->newAttach('img',$fid,$info->cachenum);
		}
		return $newpic;
	}
	/**
	 * 用户排行
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function userSort($round='postnum',$num=0,$special=true){
		global $_CREDITDB;
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$sorttype = array('money','rvrc','credit','currency','todaypost','monthpost','postnum','monoltime','onlinetime','digests','f_num');
		foreach ($_CREDITDB as $key => $val) {
			is_numeric($key) &&	$sorttype[] = $key;
		}
		$type = $round;
		!$type && $type = 'postnum';
		!in_array($type,$sorttype) && Showmsg('undefined_action');
		$sort = array();
		if ($this->ifpwcache & 1) {
			if (!$special) {
				$sql = "SELECT id as uid,addition as title,value FROM pw_elements WHERE type='usersort' AND mark=".S::sqlEscape($type)." ORDER BY value DESC ".S::sqlLimit($num);
			} else {
				require_once(R_P.'require/showimg.php');
				$sql = "SELECT e.id as uid,e.addition as title,e.value,m.icon,m.groupid,m.memberid FROM pw_elements e LEFT JOIN pw_members m ON e.id=m.uid WHERE e.type='usersort' AND e.mark=".S::sqlEscape($type)." ORDER BY e.value DESC ".S::sqlLimit($num);
			}
			$query = $this->db->query($sql);
			while ($rt = $this->db->fetch_array($query)) {
				$tem = array();
				$tem['url'] 	= USER_URL.$rt['uid'];
				$tem['title'] 	= $rt['title'];
				$tem['value'] 	= $rt['value'];
				if (array_key_exists('icon',$rt)) {
					$pic = showfacedesign($rt['icon'],true,'s');
					if (is_array($pic)) {
						$tem['image'] = $pic[0];
					} else {
						$tem['image'] = '';
					}
				} else {
					$tem['image'] = '';
				}
				$tem['addition']= $rt;
				$sort[] = $tem;
			}
		} else {
			$info = $this->singLeton(true,$num);
			$sort = $info->userSort($type,$num);
		}
		return $sort;
	}
	/**
	 * 获取用户的所有排行
	 *
	 * @param string $type	：无用参数
	 * @param string $fid	：无用参数
	 * @param int $num		：无用参数
	 * @param int $special	：无用参数
	 * @return array
	 */
	function getAllUserSort($round=0,$num=0,$special=false){
		global $_CREDITDB;
		!($this->ifpwcache & 1) && Showmsg('undefined_action');
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$sorttype = array('money','rvrc','credit','currency','todaypost','monthpost','postnum','monoltime','onlinetime','digests');
		foreach ($_CREDITDB as $key => $val) {
			is_numeric($key) &&	$sorttype[] = $key;
		}
		$sort = $count = array();
		$query = $this->db->query("SELECT * FROM pw_elements WHERE type='usersort' ORDER BY mark DESC, value DESC");
		while ($rt = $this->db->fetch_array($query)) {
			if ($count[$rt['mark']]>=$num) {
				continue;
			}
			$sort[$rt['mark']][] = array($rt['id'],$rt['addition'],$rt['value']);
			$count[$rt['mark']]++;
		}
		return $sort;
	}
	/*
	 * 活动排行
	 */
	function newActive($round=0,$num=0){
		return $this->newSubject($round,$num,2);
	}
	function hotActive($round=0,$num=0){
		return $this->replySort($round,$num,2);
	}
	function todayActive($round=0,$num=0){
		return $this->replySortDay($round,$num,2);
	}
	/**
	 * 热门回复排行
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function replySort($round=0,$num=0,$special=0){
		return $this->replySortInterface('replysort',$round,$num,$special);
	}
	function replySortDay($round=0,$num=0,$special=0){
		return $this->replySortInterface('replysortday',$round,$num,$special);
	}
	function replySortWeek($round=0,$num=0,$special=0){
		return $this->replySortInterface('replysortweek',$round,$num,$special);
	}
	function replySortInterface($type='replysort',$fid=0,$num=0,$special=0){
		!$type && $type = 'replysort';
		!in_array($type,array('replysort','replysortday','replysortweek')) && Showmsg('undefined_action');		
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$special = (int)$special;
		$fid = $this->_cookFid($fid);
		
		if (($type=='replysort' && ($this->ifpwcache & 2)) || ($type=='replysortday' && ($this->ifpwcache & 4)) || ($type=='replysortweek' && ($this->ifpwcache & 8))) {
			$sqladd = '';
			$sqladd .= ' AND e.special='.S::sqlEscape($special);
			$sort = array();
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			if ($type == 'replysortday') {
				$dayTime = PwStrtoTime(get_date(time(),'Ymd'));
				$sqladd .= " AND t.postdate >= $dayTime";
			}
			if ($special == 2) {
				$sql = "SELECT a.*,e.mark as fid FROM pw_elements e LEFT JOIN pw_activity a ON e.id=a.tid WHERE e.type=".S::sqlEscape($type)." $sqladd ORDER BY e.value DESC".S::sqlLimit($num);
			} elseif ($special == 3) {
				global $db_moneyname,$db_rvrcname,$db_creditname,$db_currencyname,$_CREDITDB;
				$cType = array(
							'money'		=> $db_moneyname,
							'rvrc'		=> $db_rvrcname,
							'credit'	=> $db_creditname,
							'currency'	=> $db_currencyname
						);
				foreach ($_CREDITDB as $k => $v) {
					$cType[$k] = $v[0];
				}
				$sql = "SELECT r.tid,r.cbtype,r.catype,r.cbval,r.caval,r.timelimit,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies FROM pw_elements e LEFT JOIN pw_reward r ON e.id=r.tid LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type=".S::sqlEscape($type)." $sqladd ORDER BY e.value DESC".S::sqlLimit($num);
			} elseif ($special == 4) {
				$sql = "SELECT t.tid,t.name,t.icon,t.price,e.mark as fid FROM pw_elements e LEFT JOIN pw_trade t ON e.id=t.tid WHERE e.type=".S::sqlEscape($type)." $sqladd ORDER BY e.value DESC".S::sqlLimit($num);
			} else {
				$sql = "SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type=".S::sqlEscape($type)." $sqladd ORDER BY e.value DESC".S::sqlLimit($num);
			}
			
			$query = $this->db->query($sql);
			while ($rt = $this->db->fetch_array($query)) {
				$post = array();
				$post['url'] 	= 'read.php?tid='.$rt['tid'];
				if ($special == 2) {
					$post['title'] 	= $rt['subject'];
					$post['value'] 	= $rt['deadline'];
					$post['image']	= '';
				} elseif ($special == 3) {
					$post['title'] 	= $rt['subject'];
					$post['value'] 	= $cType[$rt['cbtype']].":".$rt['cbval'];
					$post['image']	= '';
				} elseif ($special == 4) {
					$post['title'] 	= $rt['name'];
					$post['value'] 	= $rt['price'];
					if ($rt['icon']) {
						$pic = geturl($rt['icon'],'show',1);
						if(is_array($pic)){
							$post['image'] = $pic[0];
						} else {
							$post['image'] = 'images/noproduct.gif';
						}
					} else {
						$post['image'] = 'images/noproduct.gif';
					}
				} else {
					$post['title'] 	= $rt['subject'];
					$post['value'] 	= $rt['replies'];
					$post['image']	= '';
				}
				$post['forumname']	= getForumName($rt['fid']);
				$post['forumurl']	= getForumUrl($rt['fid']);
				list($post['topictypename'],$post['topictypeurl']) = getTopicType($rt['type'],$rt['fid']);
				$post['addition'] = $rt;
				$sort[] = $post;
			}
		} else {
			$info = $this->singLeton(true,$num);
			switch ($type) {
				case 'replysort':
					$time = 0;
					break;
				case 'replysortday':
					$time = 24;
					break;
				case 'replysortweek':
					$time = 7*24;
					break;
				default:
					$time = 0;
			}
			$sort = $info->getPostList('replysort',$fid,$info->cachenum,$time,$special);
		}
		return $sort;
	}

	/**
	 * 热门点击排行
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function hitSortInterface($type='hitsort',$fid=0,$num=0,$special=0){
		!$type && $type = 'hitsort';
		!in_array($type,array('hitsort','hitsortday','hitsortweek')) && Showmsg('undefined_action');
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$fid = $this->_cookFid($fid);
		if (($type=='hitsort' && ($this->ifpwcache & 16)) || ($type=='hitsortday' && ($this->ifpwcache & 32)) || ($type=='hitsortweek' && ($this->ifpwcache & 64))) {
			$sqladd = '';
			$sort = array();
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			if ($type == 'hitsortday') {
				$dayTime = PwStrtoTime(get_date(time(),'Ymd'));
				$sqladd .= " AND t.postdate >= $dayTime";
			}
			$query = $this->db->query("SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.type,t.postdate,t.hits,t.replies FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type=".S::sqlEscape($type)." $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
			while ($rt = $this->db->fetch_array($query)) {
				if (!$rt['tid']) continue; 
				$post = array();
				$post['url'] 	= 'read.php?tid='.$rt['tid'];
				$post['title'] 	= $rt['subject'];
				$post['value'] 	= $rt['hits'];
				$post['image']	= '';
				$post['forumname']	= getForumName($rt['fid']);
				$post['forumurl']	= getForumUrl($rt['fid']);
				list($post['topictypename'],$post['topictypeurl']) = getTopicType($rt['type'],$rt['fid']);
				$post['addition'] = $rt;
				$sort[] = $post;
			}
		} else {
			$info = $this->singLeton(true,$num);
			switch ($type) {
				case 'hitsort':
					$time = 0;
					break;
				case 'hitsortday':
					$time = 24;
					break;
				case 'hitsortweek':
					$time = 7*24;
					break;
				default:
					$time = 0;
			}
			$sort = $info->getPostList('hitsort',$fid,$info->cachenum,$time);
		}
		return $sort;
	}

	function hitSort($round=0,$num=0,$special=0){
		return $this->hitSortInterface('hitsort',$round,$num,$special);
	}
	function hitSortDay($round=0,$num=0,$special=0){
		return $this->hitSortInterface('hitsortday',$round,$num,$special);
	}
	function hitSortWeek($round=0,$num=0,$special=0){
		return $this->hitSortInterface('hitsortweek',$round,$num,$special);
	}

	/**
	 * 最新会员和最老会员
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function getMembers($round='new',$num=0,$special=0){
		in_array($round,array('new','old')) || $round = 'new';
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$order = $round=='new'? 'DESC':'';
		$sql = "SELECT uid,username,regdate FROM pw_members ORDER BY uid $order".S::sqlLimit($num);
		$member = array();
		$query = $this->db->query($sql);
		while ($rt = $this->db->fetch_array($query)) {
			$tem = array();
			$tem['url'] 	= USER_URL.$rt['uid'];
			$tem['title'] 	= $rt['username'];
			$tem['value'] 	= $rt['regdate'];
			$tem['image']	= '';
			$tem['addition']= $rt;
			$member[] = $tem;
		}
		return $member;
	}
	/**
	 * 热门标签和最新标签
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function getTags($round='hot',$num=0,$special=0){
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$round = $round=='new' ? 'tagid' : 'num';
		$sql = "SELECT tagid,tagname,num FROM pw_tags WHERE ifhot='0' ORDER BY $round DESC ".S::sqlLimit($num);
		$tags = array();
		$query = $this->db->query($sql);
		while ($rt = $this->db->fetch_array($query)) {
			$tem = array();
			$tem['url'] 	= 'link.php?action=tag&tagname='.rawurlencode(preg_replace('/"([^"]+)"/','$1',$rt['tagname']));
			$tem['title'] 	= $rt['tagname'];
			$tem['value'] 	= $rt['num'];
			$tem['image']	= '';
			$tem['addition']= $rt;
			$tags[] = $tem;
		}
		return $tags;
	}
	/**
	 * 论坛基本信息
	 *
	 * @param string $type
	 * @param string $fid
	 * @param int $num
	 * @param int $special
	 * @return array
	 */
	function getInfo($round=0,$num=0,$special=0){
		global $tdtime,$db_online,$db_hostweb;
		$bbsinfo = $this->db->get_one("SELECT newmember,totalmember,higholnum,higholtime,tdtcontrol,yposts,hposts FROM pw_bbsinfo WHERE id=1");
		$rs = $this->db->get_one("SELECT SUM(fd.topic) as topic,SUM(fd.subtopic) as subtopic,SUM(fd.article) as article,SUM(fd.tpost) as tposts FROM pw_forums f LEFT JOIN pw_forumdata fd USING(fid) WHERE f.ifsub='0' AND f.cms!='1'");
		$bbsinfo['topic']   = $rs['topic'] + $rs['subtopic'];
		$bbsinfo['article'] = $rs['article'];
		$bbsinfo['tposts']  = $rs['tposts'];
		if($bbsinfo['tdtcontrol'] < $tdtime && $db_hostweb == 1){
			//* $this->db->update("UPDATE pw_bbsinfo SET yposts='$bbsinfo[tposts]',tdtcontrol='$tdtime' WHERE id=1");
			pwQuery::update('pw_bbsinfo', 'id=:id', array(1), array('yposts'=>$bbsinfo[tposts], 'tdtcontrol'=>$tdtime));
			$this->db->update("UPDATE pw_forumdata SET tpost=0 WHERE tpost<>'0'");
			$bbsinfo['yposts'] = $bbsinfo['tposts'];
			$bbsinfo['tposts'] = '';
		}
		unset($bbsinfo['tdtcontrol']);
		$bbsinfo['guest'] = $bbsinfo['users'] = 0;
		if (!$db_online && file_exists(D_P.'data/bbscache/olcache.php')) {
			include pwCache::getPath(D_P.'data/bbscache/olcache.php');
			$bbsinfo['guest'] = $guestinbbs;
			$bbsinfo['users'] = $userinbbs;
		} elseif ($db_online) {
			$userinbbs = $guestinbbs = 0;
			$query = $this->db->query("SELECT uid!=0 as ifuser,COUNT(*) AS count FROM pw_online GROUP BY uid!='0'");
			while($rt = $this->db->fetch_array($query)){
				if($rt['ifuser']){
					$bbsinfo['users'] = $rt['count'];
				} else {
					$bbsinfo['guest'] = $rt['count'];
				}
			}
		}
		$bbsinfo['usertotal'] = $bbsinfo['guest']+$bbsinfo['users'];
		return $bbsinfo;
	}

	function getPushInfo($type=0,$fid=0,$num=0,$special=0){
		!$type && Showmsg('undefined_function');
		$focusdb = array();
		$sqladd	= '';
		!$fid && $fid = getCommonFid();
		$fid && $sqladd .= " AND fid IN ($fid) ";
		$query =  $this->db->query("SELECT * FROM pw_focus WHERE pushto=".S::sqlEscape($type)." $sqladd ORDER BY pushtime DESC ".S::sqlLimit($num));
		while($rt = $this->db->fetch_array($query)) {
			$focus = array();
			if($rt['imgurl'] && substr($rt['imgurl'],0,7) != 'http://'){
				$a_url = geturl($rt['imgurl'],'show','1');
				$rt['imgurl'] = is_array($a_url) ? $a_url[0] : $a_url;
			}
			$focus['url'] 	= $rt['url'];
			$focus['title']	= $rt['subject'];
			$focus['image']	= $rt['imgurl'];
			$focus['value']	= $rt['content'];
			$focus['addition'] = $rt;
			$focusdb[] = $focus;
		}
		return $focusdb;
	}


	/**
	 * 获取热门收藏
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @return array
	 */
	function hotFavorsort($type=false,$fid='',$num=0){
		global $forum;
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$sqladd = '';
		$favors = array();
		!$fid && $fid = getCommonFid();
		isset($forum) || include pwCache::getPath(D_P.'data/bbscache/forum_cache.php');

		if ($this->ifpwcache & 1024) {
			$fid && $sqladd .= " AND e.mark IN ($fid) ";
			$query = $this->db->query("SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.postdate,t.hits,t.replies,t.favors FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type='hotfavor' $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
			while ($rt = $this->db->fetch_array($query)) {
				$favor = array();
				$favor['url'] 	= 'read.php?tid='.$rt['tid'];
				$favor['title'] = $rt['subject'];
				$favor['value'] = $rt['favors'];
				$favor['posttime'] = get_date($rt['postdate']);
				$favor['forum'] = $forum[$rt['fid']]['name'];
				$favor['image']	= '';
				$favor['addition'] = $rt;
				$favors[] = $favor;
			}
		} else {
			$info = $this->singLeton(true,$num);
			$favors = $info->gethotfavor($fid,$info->cachenum);
		}
		return $favors;
	}

	/**
	 * 获取最新收藏
	 *
	 * @param string $type 	:无用参数
	 * @param string $fid
	 * @param int $num
	 * @return array
	 */
	function newFavorsort($type=false,$fid='',$num=0){
		global $forum;
		$num = intval($num) ? intval($num) : $this->defaultnum;
		$sqladd = '';
		$favors = array();
		!$fid && $fid = getCommonFid();
		isset($forum) || include pwCache::getPath(D_P.'data/bbscache/forum_cache.php');

		$fid && $sqladd .= " AND e.mark IN ($fid) ";
		$query = $this->db->query("SELECT t.tid,t.fid,t.author,t.authorid,t.subject,t.hits,t.replies,t.postdate,t.hits,t.replies,t.favors,e.addition,e.time,t.replies,t.hits FROM pw_elements e LEFT JOIN pw_threads t ON e.id=t.tid WHERE e.type='newfavor' $sqladd ORDER BY e.value DESC ".S::sqlLimit($num));
		while ($rt = $this->db->fetch_array($query)) {
			$favor = array();
			$favor['url'] 	= 'read.php?tid='.$rt['tid'];
			$favor['title'] = $rt['subject'];
			$favor['value'] = $rt['favors'];
			$favor['posttime'] = $rt['postdate'];
			$favor['favortime'] = get_date($rt['time']);
			$favor['forum'] = $forum[$rt['fid']]['name'];
			list($favor['favorid'],$favor['favorer']) = explode('|',$rt['addition']);
			unset($rt['addition']);
			$favor['image']	= '';
			$favor['replies']	= $rt['replies'];
			$favor['hits']	= $rt['hits'];
			$favor['addition'] = $rt;
			$favors[] = $favor;
		}
		return $favors;
	}
	
	function _cookFid($fid) {
		if ($fid && is_numeric($fid)) return $fid;
		if (S::isArray($fid)) {
			foreach ($fid as $key=>$value) {
				if (!$value) unset($fid[$key]);
			}
			if (S::isArray($fid)) return S::sqlImplode($fid);
		}
		return getCommonFid();
	}
	
	function _cookSubjectData($data) {
		$post = array();
		$post['url'] 	= 'read.php?tid='.$data['tid'];
		$post['title'] 	= $data['subject'];
		$post['value'] 	= $data['postdate'];
		$post['image']	= '';
		$post['forumname']	= getForumName($data['fid']);
		$post['forumurl']	= getForumUrl($data['fid']);
		list($post['topictypename'],$post['topictypeurl']) = getTopicType($data['type'],$data['fid']);
		$post['addition'] = $data;
		return $post;
	}
}

?>