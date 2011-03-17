<?php $page_title='再美点网博客'. $data['post']->title;
include 'sub_view/blog_header.php';
?>
<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">

        <h2><a href="<?php echo $data['rootUrl']; ?>blog/article/<?php echo $data['post']->id; ?>"><?php echo $data['post']->title; ?></a></h2>
        <div class="articles">
            <?php echo $data['post']->content; ?>
            <div class="tagContainer">
                <strong>标签: </strong>
                <?php foreach($data['post']->Tag as $k1=>$v1): ?>
                <span class="tag"><a href="<?php echo $data['rootUrl']; ?>blog/tag/<?php echo $v1->name; ?>"><?php echo $v1->name; ?></a></span>
                <?php endforeach; ?>
            </div>
            <em class="datePosted">Posted on <?php echo formatDate($data['post']->createtime); ?></em>
        </div>

        <hr class="divider"/>
        <div id="comments" name="comments">
          <?php if( isset($data['comments']) ): ?>
            <strong>评论数 (<?php echo $data['post']->totalcomment; ?>)</strong><br/><br/>
              <?php foreach($data['comments'] as $k1=>$v1): ?>

                  <span id="comment<?php echo $v1->id; ?>" name="comment<?php echo $v1->id; ?>" style="font-weight:bold;">
                  <?php if( !empty($v1->url) ): ?>
                      <a href="<?php echo $v1->url; ?>"><?php echo $v1->author; ?></a>
                  <?php else: ?>
                      <?php echo $v1->author; ?>
                  <?php endif; ?>
                  </span> 在 <em><?php echo formatDate($v1->createtime, 'd M, y h:i:s A'); ?></em> 说：<br/>

                  <div class="commentItem"><?php echo $v1->content; ?></div><br/>

              <?php endforeach; ?>
            <hr class="divider"/>
          <?php endif; ?>
        </div>

        <p><strong>写评论 :)</strong></p>
        <form method="POST" action="<?php echo $data['rootUrl']; ?>blog/comment/submit">
            <input type="hidden" name="post_id" value="<?php echo $data['post']->id; ?>"/>
            <span class="field"><span class="commentInput">用户名*:</span><input type="text" name="author" size="32"/></span>
            <span class="field"><span class="commentInput">Email*:</span><input type="text" name="email" size="32"/></span>
            <span class="field"><span class="commentInput">网站:</span><input type="text" name="url" value="http://" size="32"/></span>
            <span class="field"><span class="commentInput">内容*:</span><textarea cols="45" rows="6" name="content"></textarea></span>
            <span class="field"><span class="commentInput">&nbsp;</span><input type="submit" value="Send Comment"/></span>
        </form>
    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>