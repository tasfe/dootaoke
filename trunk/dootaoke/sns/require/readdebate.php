<?php
!function_exists('readover') && exit('Forbidden');

$debatestand = 0;
if ($groupid != 'guest' && !$tpc_locked) {
	$debatestand = $db->get_value("SELECT standpoint FROM pw_debatedata WHERE pid='0' AND tid=".S::sqlEscape($tid)."AND authorid=".S::sqlEscape($winduid));
	$debatestand = (int)$debatestand;
	${'debate_'.$debatestand} = 'SELECTED';
}
if ($page == 1) {
	$debate = $db->get_one("SELECT obtitle,retitle,endtime,obvote,revote,obposts,reposts,umpire,umpirepoint,debater,judge FROM pw_debates WHERE tid=".S::sqlEscape($tid));
}
$stand = (int)S::getGP('stand');
if (!$uid && $read['replies'] > 0 && $stand > 0 && $stand < 4) {
	if ($stand == 3) {
		$rt = $db->get_one("SELECT COUNT(*) AS n FROM pw_debatedata WHERE pid>'0' AND tid=".S::sqlEscape($tid));
		$read['replies'] -= $rt['n'];
		$sqladd = " AND dd.standpoint IS NULL";
	} else {
		$rt = $db->get_one("SELECT COUNT(*) AS n FROM pw_debatedata WHERE pid>'0' AND tid=".S::sqlEscape($tid)." AND standpoint=".S::sqlEscape($stand));
		$read['replies'] = $rt['n'];
		$sqladd = " AND dd.standpoint=".S::sqlEscape($stand);
	}
	$urladd = "&stand=$stand";
	$count = $read['replies']+1;
	$numofpage = ceil($count/$db_readperpage);
	if ($page == 'e' || $page > $numofpage) {
		$page = $numofpage;
	}
}
$fieldadd .= ',dd.standpoint,dd.vote';
$tablaadd .= ' LEFT JOIN pw_debatedata dd ON t.pid=dd.pid';
$special = 'read_debate';
?>