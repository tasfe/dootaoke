<?php
$jobLists=array(
	'0' => array(
		'id' => '2',
		'title' => '完善个人资料',
		'description' => '要让大家了解你，就要先更新自己的个人资料哦',
		'icon' => '',
		'starttime' => '1259896260',
		'endtime' => '0',
		'period' => '0',
		'reward' => 'a:4:{s:4:"type";s:5:"money";s:3:"num";s:2:"10";s:8:"category";s:6:"credit";s:11:"information";s:17:"可获得 铜币 ";}',
		'sequence' => '1',
		'usergroup' => '8',
		'prepose' => '0',
		'number' => '0',
		'member' => '0',
		'auto' => '1',
		'finish' => '1',
		'display' => '1',
		'type' => '0',
		'job' => 'doUpdatedata',
		'factor' => 'a:1:{s:5:"limit";s:0:"";}',
		'isopen' => '1',
	),
	'1' => array(
		'id' => '1',
		'title' => '更新个人头像',
		'description' => '上传自己的头像，给大家留个好印象吧',
		'icon' => '',
		'starttime' => '1259896560',
		'endtime' => '0',
		'period' => '0',
		'reward' => 'a:4:{s:4:"type";s:5:"money";s:3:"num";s:2:"10";s:8:"category";s:6:"credit";s:11:"information";s:17:"可获得 铜币 ";}',
		'sequence' => '2',
		'usergroup' => '8',
		'prepose' => '0',
		'number' => '0',
		'member' => '0',
		'auto' => '1',
		'finish' => '0',
		'display' => '1',
		'type' => '0',
		'job' => 'doUpdateAvatar',
		'factor' => 'a:1:{s:5:"limit";s:0:"";}',
		'isopen' => '1',
	),
	'2' => array(
		'id' => '3',
		'title' => '给admin发送消息',
		'description' => '要和大家熟悉起来，一定要学会发消息哦，还可以顺便问问题',
		'icon' => '',
		'starttime' => '1259694720',
		'endtime' => '0',
		'period' => '0',
		'reward' => 'a:4:{s:4:"type";s:5:"money";s:3:"num";s:2:"10";s:8:"category";s:6:"credit";s:11:"information";s:17:"可获得 铜币 ";}',
		'sequence' => '3',
		'usergroup' => '8',
		'prepose' => '0',
		'number' => '0',
		'member' => '0',
		'auto' => '1',
		'finish' => '1',
		'display' => '1',
		'type' => '0',
		'job' => 'doSendMessage',
		'factor' => 'a:2:{s:4:"user";s:5:"admin";s:5:"limit";s:0:"";}',
		'isopen' => '1',
	),
	'3' => array(
		'id' => '4',
		'title' => '寻找并添加5个好友',
		'description' => '去找找有没有志同道合的朋友？加他们为好友吧',
		'icon' => '',
		'starttime' => '1259694780',
		'endtime' => '0',
		'period' => '0',
		'reward' => 'a:4:{s:4:"type";s:5:"money";s:3:"num";s:2:"10";s:8:"category";s:6:"credit";s:11:"information";s:17:"可获得 铜币 ";}',
		'sequence' => '4',
		'usergroup' => '8',
		'prepose' => '0',
		'number' => '0',
		'member' => '0',
		'auto' => '1',
		'finish' => '1',
		'display' => '1',
		'type' => '0',
		'job' => 'doAddFriend',
		'factor' => 'a:4:{s:4:"user";s:0:"";s:4:"type";s:1:"2";s:3:"num";s:1:"5";s:5:"limit";s:0:"";}',
		'isopen' => '1',
	),
	'4' => array(
		'id' => '5',
		'title' => '论坛每日红包',
		'description' => '发红包咯！每天报到都有红包',
		'icon' => '',
		'starttime' => '1259694840',
		'endtime' => '0',
		'period' => '24',
		'reward' => 'a:4:{s:4:"type";s:5:"money";s:3:"num";s:2:"10";s:8:"category";s:6:"credit";s:11:"information";s:17:"可获得 铜币 ";}',
		'sequence' => '7',
		'usergroup' => '',
		'prepose' => '0',
		'number' => '0',
		'member' => '0',
		'auto' => '0',
		'finish' => '1',
		'display' => '0',
		'type' => '0',
		'job' => 'doSendGift',
		'factor' => '',
		'isopen' => '1',
	),
);
?>