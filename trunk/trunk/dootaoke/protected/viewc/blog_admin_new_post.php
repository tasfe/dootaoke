<?php $page_title='发表文章';
include 'sub_view/blog_header.php';
?>

<div id="wrap">

<?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_top.php"; ?>

<div id="content">
    <div class="left">
        <p><strong>添加日志</strong></p>
        <form id="blog" method="POST" action="<?php echo $data['rootUrl']; ?>blog/admin/post/saveNew">
            <span class="field">
                <strong>标题: </strong><br/>
                <input type="text" size="60" name="title"/>
            </span>


            <span class="field">
                <strong>状态: </strong><br/>
                <select id="status" name="status" style="width:120px;">
                    <option value="0">草稿</option>
                    <option selected="selected" value="1">发表</option>
                </select>
            </span>


            <span class="field">
                <strong>正文: </strong><br/>
                <textarea id="content1" rows="20" cols="70" name="content"></textarea>
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
                <input type="text" size="60" name="tags"/>
            </span>

            <span class="field">
                <strong>&nbsp;</strong>
                <input type="submit" value="Create This Post" style="width:300px;"/>
            </span>

        </form>
        <hr class="divider"/>

    </div>

    <div class="right">
        <?php include Doo::conf()->SITE_PATH .  Doo::conf()->PROTECTED_FOLDER . "viewc//sub_view//blog_admin_sidebar.php"; ?>
    </div>

    <div style="clear: both;"> </div>
</div>

<?php include 'sub_view/blog_footer.php';?>