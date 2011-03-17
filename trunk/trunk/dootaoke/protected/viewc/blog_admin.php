<?php $page_title='博客管理';
include 'sub_view/blog_header.php';
?>

<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">

        <h1>管理日志</h1><br/>
        <p>你可以点击标题来排序，点击日志进行编辑.</p><br/>
        <div class="articles">
            <table>
              <tbody><tr>
                <th width="150"><a href="<?php echo $data['rootUrl']; ?>blog/admin/post/sort/status/<?php echo $data['order']; ?>">Status</a></th>
                <th width="500"><a href="<?php echo $data['rootUrl']; ?>blog/admin/post/sort/title/<?php echo $data['order']; ?>">Title</a></th>
                <th width="360"><a href="<?php echo $data['rootUrl']; ?>blog/admin/post/sort/createtime/<?php echo $data['order']; ?>">Create Time</a></th>
              </tr>
              <?php foreach($data['posts'] as $k1=>$v1): ?>
              <tr class="trecord">
                <td><?php if( $v1->status==1 ): ?>Published<?php else: ?>Draft<?php endif; ?></td>
                <td><a href="<?php echo $data['rootUrl']; ?>blog/admin/post/edit/<?php echo $v1->id; ?>"><?php echo $v1->title; ?></a></td>
                <td><?php echo formatDate($v1->createtime); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody></table>
        </div>

        <hr class="divider"/>
        <?php echo $data['pager']; ?>
    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_admin_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>