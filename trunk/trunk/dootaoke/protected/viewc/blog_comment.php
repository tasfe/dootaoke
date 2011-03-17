<?php $page_title='再美点网 - 发表评论';
include 'sub_view/blog_header.php';
?>
<div id="wrap">
<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">
        <div class="articles">
        <h2>评论已经保存</h2>
        <p>您的评论已经提交，审核通过后将会显示。</p>
        </div>
    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>