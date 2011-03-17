<?php
!defined('P_W') && exit('Forbidden');

/**
 * 个人空间模块数据中心
 * @author sky_hold@163.com
 * @package space
 */
class PwSpaceModel {

	var $base;

	var $_db;
	var $spaceinfo;
	var $_isGM;

	function PwSpaceModel(&$base) {
		$this->base =& $base;
		$this->_db = & $GLOBALS['db'];
		$this->_isGM =& $GLOBALS['isGM'];
		$this->spaceinfo = $base->info;
		$this->_cacheModes = array('article', 'friend', 'weibo', 'colony', 'messageboard', 'recommendUsers');
	}

	function get($uid, $config) {
		$array = array();
		if ($this->_havaCacheMode($config)) {
			$userCache = L::loadClass('UserCache', 'user'); /* @var $userCache PW_UserCache */
			$array = $userCache->get($uid, $config);
		}
		foreach ($config as $key => $value) {
			if (!isset($array[$key])) {
				if ($method = $this->_getMethod($key, 'get')) {
					$array[$key] = $this->$method($uid, $value);
				}
			} elseif ($method = $this->_getMethod($key, 'adorn')) {
				$array[$key]  = $this->$method($array[$key]);
			}
		}
		return $array;
	}

	function _havaCacheMode($config) {
		return array_intersect(array_keys($config), $this->_cacheModes);
	}

	function _getMethod($mode, $action) {
		$method = $action . '_' . $mode;
		if (method_exists($this, $method)) {
			return $method;
		}
		return false;
	}

	function get_info($uid, $num = 0) {
		require_once(R_P . 'require/showimg.php');
		$array = array();
		list($array['faceurl']) = showfacedesign($this->spaceinfo['icon'], 1, 'm');
		$ltitle = L::config('ltitle','level');
		$array['systitle'] = $this->spaceinfo['groupid'] == '-1' ? '' : $ltitle[$this->spaceinfo['groupid']];
		$array['memtitle'] = $ltitle[$this->spaceinfo['memberid']];
		$array['thisvisit'] = $this->spaceinfo['thisvisit'];
		$array['lastvisit_s'] = get_date($this->spaceinfo['lastvisit'], 'Y-m-d');
		$array['totalcredit'] = $this->_getUserTotalCredit();
		$array = array_merge($array, $this->_getDetailInfo($uid));
		return $array;
	}

	function _getDetailInfo($uid) {
		if (!isset($this->spaceinfo['bday'])) {
			$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
			$array = $userService->get($uid);//gender,location,bday
		} else {
			$array = array(
				'gender'	=> $this->spaceinfo['gender'],
				'location'	=> $this->spaceinfo['location'],
				'bday'		=> $this->spaceinfo['bday'],
			);
		}
		return $array;
	}

	function get_diary($uid, $num) {
		global $winduid;
		$sqlAdd = '';
		$array = array();

		$diaryService = L::loadClass('Diary', 'diary'); /* @var $diaryService PW_Diary */
		$gid = $GLOBALS['groupid'];
		if ($winduid != $uid) {
			$friendsService = L::loadClass('Friend', 'friend'); /* @var $friendsService PW_Friend */
			$isFriend = $friendsService->isFriend($winduid, $uid);
			$diaryPrivacy = $isFriend !== true ? array(0) : array(0,1);
			$sqlAdd = " AND d.privacy IN (".S::sqlImplode($diaryPrivacy).")";
			$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
			$userInfo = $userService->get($uid);
			$gid = $userInfo['groupid'];
		}
		$query = $this->_db->query("SELECT d.*,".
									" dt.name".
									" FROM pw_diary d".
									" LEFT JOIN pw_diarytype dt".
									" ON d.dtid= dt.dtid".
									" WHERE d.uid=" . S::sqlEscape($uid) .$sqlAdd. ' ORDER BY d.did DESC '. S::sqlLimit($num));
		while ($rt = $this->_db->fetch_array($query)) {
			$rt['content']  = substrs($rt['content'], 500);
			$rt['postdate'] = get_date($rt['postdate']);
			$rt['groupid'] = $gid;
			list($rt['subject'], $rt['content']) = $diaryService->filterDiaryContent($rt, true, true);
			$rt['content'] = preg_replace('/\[(attachment|upload)=\d+\]/i', '', $rt['content']);
			$array[] = $rt;
		}
		return $array;
	}

	function get_photos($uid, $num) {
		global $winduid;
		$_sql_where = '';
		if ($this->spaceinfo['isMe'] || $this->_isGM) {

		} elseif ($this->base->isFriend($winduid)) {
			$_sql_where = ' AND a.private<2';
		} else {
			$_sql_where = ' AND a.private=0';
		}
		$array = array();
		$query = $this->_db->query("SELECT b.pid,b.path,b.ifthumb FROM pw_cnalbum a LEFT JOIN pw_cnphoto b ON a.aid=b.aid WHERE a.atype='0' AND a.ownerid=" . S::sqlEscape($uid) . $_sql_where . ' AND b.pid IS NOT NULL ORDER BY b.pid DESC ' . S::sqlLimit($num));
		while ($rt = $this->_db->fetch_array($query)) {
			$rt['path'] = getphotourl($rt['path'], $rt['ifthumb']);
			$array[] = $rt;
		}
		return $array;
	}

	function get_visitor($uid, $num) {
		$visitors = unserialize($this->spaceinfo['visitors']);
		$array = array();
		if (is_array($visitors)) {
			$uids = array_slice(array_keys($visitors), 0, $num);
			$array = array_flip($uids);
			$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
			foreach ($userService->getUsersWithMemberDataByUserIds($uids) as $rt) {
				list($rt['icon']) = showfacedesign($rt['icon'], 1, 'm');
				list($rt['visittime']) = getLastDate($visitors[$rt['uid']]);
				$rt['visittime_s'] = get_date($visitors[$rt['uid']], 'Y-m-d H:i');
				$rt['timestamp'] = $visitors[$rt['uid']];
				$array[$rt['uid']] = $rt;
			}
		}
		return $array;
	}

	function get_visit($uid, $num) {
		$visitors = unserialize($this->spaceinfo['tovisitors']);
		$array = array();
		if (is_array($visitors)) {
			$uids = array_slice(array_keys($visitors), 0, $num);
			$array = array_flip($uids);
			$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
			foreach ($userService->getUsersWithMemberDataByUserIds($uids) as $rt) {
				list($rt['icon']) = showfacedesign($rt['icon'], 1, 's');
				list($rt['visittime']) = getLastDate($visitors[$rt['uid']]);
				$rt['visittime_s'] = get_date($visitors[$rt['uid']], 'Y-m-d H:i');
				$array[$rt['uid']] = $rt;
			}
		}
		return $array;
	}

	function adorn_messageboard($data) {
		global $db_shield,$groupid;
		if(!$data || !is_array($data)) return array();
		$wordsfb = L::loadClass('FilterUtil', 'filter');
		foreach ($data as $key => $rt) {
			if ($rt['groupid'] == 6 && $db_shield && $groupid != 3) {
				$rt['title'] = appShield('ban_feed');
			} elseif (!$wordsfb->equal($rt['ifwordsfb'])) {
				$rt['title'] = $wordsfb->convert($rt['title'], array(
					'id'	=> $rt['id'],
					'type'	=> 'oboard',
					'code'	=> $rt['ifwordsfb']
				));
			}
			$data[$key] = $rt;
		}
		if (!empty($data)) {
			$commentdb = getCommentDb('board', array_keys($data));
		}
		return array($data, $commentdb);
	}

	function adorn_weibo($data) {
		if (empty($data) || !is_array($data)) return $data;
		foreach ($data as $key => $value) {
			list($value['lastdate'], $value['postdate_s']) = getLastDate($value['postdate']);
			$data[$key] = $value;
		}
		return $data;
	}

	function adorn_friend($data) {
		if (empty($data) || !is_array($data)) return $data;
		$array = array();
		$userService = L::loadClass('UserService', 'user'); /* @var $userService PW_UserService */
		foreach ($userService->getUsersWithMemberDataByUserIds($data) as $rt) {
			list($rt['icon']) = showfacedesign($rt['icon'], 1, 's');
			$array[$rt['uid']] = array(
				'uid' => $rt['uid'],
				'username' => $rt['username'],
				'thisvisit' => $rt['thisvisit'],
				'icon' => $rt['icon'],
				'honor' => $rt['honor']
			);
		}
		return $array;
	}

	/**
	 * 取得个人空间用户的综合积分
	 * @return int $result	个人空间的综合积分
	 */
	function _getUserTotalCredit() {
		global $db_upgrade,$credit;

		require_once(R_P .'require/credit.php');
		require_once(R_P .'require/functions.php');
		$_usercredit = array(
			'postnum'	 => $this->spaceinfo['postnum'],
			'digests'	 => $this->spaceinfo['digests'],
			'rvrc'		 => $this->spaceinfo['rvrc'],
			'money'		 => $this->spaceinfo['money'],
			'credit'	 => $this->spaceinfo['credit'],
			'currency'	 => $this->spaceinfo['currency'],
			'onlinetime' => $this->spaceinfo['onlinetime']
		);
		foreach ($credit->get($this->spaceinfo['uid'],'CUSTOM') as $key => $value) {
			$_usercredit[$key] = $value;
		}
		$result = CalculateCredit($_usercredit, unserialize($db_upgrade));

		return $result;
	}
}
?>