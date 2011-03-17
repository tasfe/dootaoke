<?php $page_title='再美点网博客';
include 'sub_view/blog_header.php';
?>
<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
<table>
<tr>
</td>
    <div class="left">

     <?php foreach($data['posts'] as $k1=>$v1): ?>
        <h2><a href="<?php echo $data['rootUrl']; ?>blog/article/<?php echo $v1->id; ?>"><?php echo $v1->title; ?></a></h2>
        <div class="articles">
            <?php echo substring($v1->content,600,'...'); ?>
            <div class="tagContainer">
                <strong>标签: </strong>
                <?php foreach($v1->Tag as $k2=>$v2): ?>
                <span class="tag"><a href="<?php echo $data['rootUrl']; ?>blog/tag/<?php echo $v2->name; ?>"><?php echo $v2->name; ?></a></span>
                <?php endforeach; ?>
            </div>
            <em class="datePosted">&nbsp;<a href="<?php echo $data['rootUrl']; ?>blog/article/<?php echo $v1->id; ?>#comments" style="text-decoration:none;">评论数 (<?php echo $v1->totalcomment; ?>)</a> | 发表于 <?php echo formatDate($v1->createtime); ?></em>
        </div>
        <hr class="divider"/>
    <?php endforeach; ?>
    <div><?php echo $data['pager']; ?></div>

    </div>
</td>
<td>
    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_sidebar.php"; ?>
    </div>
</td>
</tr>
</table>
    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>