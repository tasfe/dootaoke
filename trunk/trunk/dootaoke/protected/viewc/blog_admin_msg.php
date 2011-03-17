<?php $page_title='Editing - '. $data['title'];
include 'sub_view/blog_header.php';
?>
<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">
        <div class="articles">
        <h2><?php echo $data['title']; ?></h2>
        <?php echo $data['content']; ?>
        </div>
    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_admin_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>