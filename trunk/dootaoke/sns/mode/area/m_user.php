<?php
!defined('P_W') && exit('Forbidden');
if($windid && $winduid) {
	$userinfo = array('verify'=>$loginhash,'id'=>$winddb['id'],
		'username'=>$winddb['username'],'money'=>$winddb['money'],
		'currency'=>$winddb['currency'],'rvrc'=>$winddb['rvrc'],
		'credit'=>$winddb['credit']);
	echo json_encode($userinfo);
}
?>