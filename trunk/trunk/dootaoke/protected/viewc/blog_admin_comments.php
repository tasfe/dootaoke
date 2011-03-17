<?php $page_title='博客管理 - 审核评论';
include 'sub_view/blog_header.php';
?>
<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">

        <h1>审核评论</h1><br/>
        <p>通过/拒绝评论:</p><br/>
        <div class="articles">
          <?php if( isset($data['comments']) && !empty($data['comments']) ): ?>
              <?php foreach($data['comments'] as $k1=>$v1): ?>
                  <strong>作者: </strong><span><?php echo $v1->author; ?></span><br/>
                  <strong>Email: </strong><span><?php echo links($v1->email); ?></span><br/>
                  <strong>网址: </strong><span><?php echo LINKS($v1->url); ?></span><br/>
                  <strong>评论: </strong><span><?php echo $v1->content; ?></span><br/>
                  <span><a href="<?php echo $data['rootUrl']; ?>blog/admin/comment/approve/<?php echo $v1->id; ?>">[通过]</a></span> | <span><a href="<?php echo $data['rootUrl']; ?>admin/comment/reject/<?php echo $v1->id; ?>">[拒绝]</a></span>
                  <hr class="divider"/>

              <?php endforeach; ?>
          <?php else: ?>
              <h4>暂无可操作评论.</h4>
          <?php endif; ?>
        </div>
    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_admin_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>