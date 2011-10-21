<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($data['page_title']?$data['page_title'] . TITLE_SPLIT:''); ?><?php echo SITE_NAME;?></title>
<meta name="keywords" content="<?php echo ($data['page_keywords']?$data['page_keywords']:DEFAULT_SITE_KEYWORDS); ?>">
<meta name="description" content="<?php echo ($data['page_description']?$data['page_description']:DEFAULT_SITE_DESC); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo Doo::conf()->SUBFOLDER; ?>global/css/common.css" media="screen" />
<?php if($data['page_css']) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo Doo::conf()->SUBFOLDER; ?>global/css/<?php echo $data['page_css'];?>.css" media="screen" />
<?php } ?>
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>global/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>global/js/common.js"></script>
<!--script type="text/javascript" src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script-->
<?php if($data['page_js']) { ?>
<script type="text/javascript" src="<?php echo Doo::conf()->SUBFOLDER; ?>global/js/<?php echo $data['page_js'];?>.js"></script>
<?php } ?>
<?php if($data['rss_url']) { ?>
<link rel="alternate" type="application/rss+xml" title="<?php echo  $data['rss_name'] ;?>" href="<?php echo $data['rss_url'];?>">
<?php } ?>
</head>
<body>
<?php include 'site_nav.php';?>
<div id="page">
<?php include 'head_nav.php';?>
