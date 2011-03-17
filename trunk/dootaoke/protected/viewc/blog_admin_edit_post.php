<?php $page_title='编辑文章 - '. $data['post']->title;
include 'sub_view/blog_header.php';
?>
<script>
    function delPost(){
        window.location="<?php echo $data['rootUrl']; ?>admin/post/delete/<?php echo $data['post']->id; ?>";
    }
</script>
<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">
        <p><strong>编辑日志</strong></p>
        <form id="blog" method="POST" action="<?php echo $data['rootUrl']; ?>blog/admin/post/save">
            <span class="field">
                <strong>标题: </strong><br/>
                <input type="text" value="<?php echo $data['post']->title; ?>" size="60" name="title"/>
            </span>

            <span class="field">
                <strong>状态: </strong><br/>
                <select id="status" name="status" style="width:120px;">
                    <?php if( $data['post']->status==1 ): ?>
                    <option value="0">Draft</option>
                    <option selected="selected" value="1">发表</option>
                    <?php else: ?>
                    <option selected="selected" value="0">保存草稿</option>
                    <option value="1">发表</option>
                    <?php endif; ?>
                </select>
            </span>


            <span class="field">
                <strong>正文: </strong><br/>
                <textarea rows="20" cols="70" name="content" id="content1"><?php echo $data['post']->content; ?></textarea>
            </span>
            
		<script charset="utf-8" src="<?php echo Doo::conf()->SUBFOLDER; ?>editor/kindeditor.js"></script>
		<script>
			KE.show({
				id : 'content1',
				shadowMode : false,
				autoSetDataMode: false,
				allowPreviewEmoticons : false,
				afterCreate : function(id) {
					KE.event.add(KE.$('blog'), 'submit', function() {
						KE.util.setData(id);
					});
				}
			});
		</script>

            <br/><em style="color:#999">用逗号将多个标签分开.</em><br/>
            <span class="field">
                <strong>标签: </strong>
                <input type="text" value="<?php echo $data['tags']; ?>" size="60" name="tags"/>
            </span>

            <span class="field">
                <strong>&nbsp;</strong>
                <input type="submit" value="更新日志" style="width:240px;"/>
                <input type="button" value="删除日志" onclick="javascript:delPost();" style="width:240px;"/>
            </span>
            
            <input type="hidden" value="<?php echo $data['post']->id; ?>" name="id"/>

            <em class="datePosted">发布于 <?php echo formatDate($data['post']->createtime); ?></em>

        </form>
        <hr class="divider"/>

    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_admin_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>
<?php include 'sub_view/blog_footer.php';?>