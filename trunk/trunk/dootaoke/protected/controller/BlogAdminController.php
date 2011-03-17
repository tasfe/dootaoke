<?php

class BlogAdminController extends DooController {

    //Default sort by createtime field
    public $sortField = 'createtime';
    public $orderType = 'desc';
    public static $tags;

    /**
     * Display the list of paginated Posts (draft and published)
     */
	function home() {
        Doo::loadHelper('DooPager');
        Doo::loadModel('Post');

        $p = new Post();
        
        //if default, no sorting defined by user, show this as pager link
        if($this->sortField=='createtime' && $this->orderType=='desc'){
            $pager = new DooPager(Doo::conf()->APP_URL.'admin/post/page', $p->count(), 6, 10);
        }else{
            $pager = new DooPager(Doo::conf()->APP_URL."admin/post/sort/$this->sortField/$this->orderType/page", $p->count(), 6, 10);
        }

        if(isset($this->params['pindex']))
            $pager->paginate(intval($this->params['pindex']));
        else
            $pager->paginate(1);

        $data['rootUrl'] = Doo::conf()->APP_URL;
        $data['pager'] = $pager->output;

        //Order by ASC or DESC
        if($this->orderType=='desc'){
            $data['posts'] = $p->limit($pager->limit, null, $this->sortField,
                                        //we don't want to select the Content (waste of resources)
                                        array('select'=>'id,createtime,status,title,totalcomment')
                                  );
            $data['order'] = 'asc';
        }else{
            $data['posts'] = $p->limit($pager->limit, $this->sortField, null,
                                        //we don't want to select the Content (waste of resources)
                                        array('select'=>'id,createtime,status,title,totalcomment')
                                  );
            $data['order'] = 'desc';
        }

        $this->renderc('blog_admin', $data);
	}

    /**
     * Show single blog post for editing
     */
	function getArticle() {
        Doo::loadModel('Post');
        $p = new Post();
        $p->id = intval($this->params['pid']);
        
        try{
            $data['post'] = $p->relateTag(
                                        array(
                                            'limit'=>'first',
                                            'asc'=>'tag.name',
                                            'match'=>false      //Post with no tags should be displayed too
                                        )
                                );

            $data['tags'] = array();
            foreach($data['post']->Tag as  $t){
                $data['tags'][] = $t->name;
            }
            $data['tags'] = implode(',', $data['tags']);
            
        }catch(Exception $e){
            //Exception will be thrown if Post not found
            return array('/error/postNotFound/'.$p->id,'internal');
        }
        
        $data['rootUrl'] = Doo::conf()->APP_URL;
        $this->renderc('blog_admin_edit_post', $data);
	}

    /**
     * This shows the same content as home(), but with pagination
     * Show error if Page index is invalid (negative)
     */
	function page() {
        if(isset($this->params['pindex']) && $this->params['pindex']>0)
    		$this->home();
        else
            return 404;
	}

    /**
     * Sort by field names DESC or ASC, with paginations
     */
    function sortBy(){
        //if field name not in Post model fields, show error
        if(!in_array($this->params['sortField'], Doo::loadModel('Post', true)->_fields))
            return 404;

        if($this->params['orderType']!='asc' && $this->params['orderType']!='desc')
            return 404;

        $this->orderType = $this->params['orderType'];
        $this->sortField = $this->params['sortField'];
        $this->home();
    }

    /**
     * Validate if post exists
     */
    static function checkPostExist($id){
        Doo::loadModel('Post');
        $p = new Post;
        $p->id = $id;

        //if Post id doesn't exist, return an error
        if($p->find(array('limit'=>1, 'select'=>'id'))==Null)
            return 'Post ID not found in database';
    }

    /**
     * Validate if tags is less than or equal to 10 tags based on the String seperated by commas.
     * Tags cannot be empty 'mytag, tag2,,tag4,  , tag5' (error)
     */
    static function checkTags($tagStr){
        //tags can be empty(no tags)
        $tagStr = trim($tagStr);
        if(empty($tagStr)){
           return;
        }

        $tags = explode(',', $tagStr);

        foreach($tags as $k=>$v){
            $tags[$k] = strip_tags(trim($v));
            if(empty($tags[$k])){
                return '无效标签!';
            }
        }

        if(sizeof($tags)>10)
            return '最多只能添加10个标签!';

        self::$tags = $tags;
    }

    /**
     * Save changes made in Post editing
     */
    function savePostChanges(){               
        Doo::loadHelper('DooValidator');

        $_POST['content'] = trim($_POST['content']);

        //get defined rules and add show some error messages
        $validator = new DooValidator;
        $validator->checkMode = DooValidator::CHECK_SKIP;

        if($error = $validator->validate($_POST, 'post_edit.rules')){
            $data['rootUrl'] = Doo::conf()->APP_URL;
            $data['title'] =  '发生错误!';
            $data['content'] =  '<p style="color:#ff0000;">'.$error.'</p>';
            $data['content'] .=  '<p>点击 <a href="javascript:history.back();">返回</a>编辑.</p>';
            $this->renderc('blog_admin_msg', $data);
        }
        else{
            Doo::loadModel('Post');
            Doo::loadModel('Tag');

            $p = new Post($_POST);

            //delete the previous linked tags first
            Doo::loadModel('PostTag');
            $pt = new PostTag;
            $pt->post_id = $p->id;
            $pt->delete();

            //update the post along with the tags
            if(self::$tags!=Null){
                $tags = array();
                foreach(self::$tags as $t){
                    $tg = new Tag;
                    $tg->name = $t;
                    $tags[] = $tg;
                }
                $p->relatedUpdate($tags);
            }
            //if no tags, just update the post
            else{
                $p->update();
            }
            
            //clear the sidebar cache
            Doo::cache('front')->flushAllParts();
            
            $data['rootUrl'] = Doo::conf()->APP_URL;
            $data['title'] =  '更新成功!';
            $data['content'] =  '<p>你的修改已经被保存.</p>';
            $data['content'] .=  '<p>点击  <a href="'.$data['rootUrl'].'blog/article/'.$p->id.'">查看</a>.</p>';
            $this->renderc('blog_admin_msg', $data);
        }
    }

    function createPost(){
        $data['rootUrl'] = Doo::conf()->APP_URL;
        $this->renderc('blog_admin_new_post', $data);
    }

    function saveNewPost(){
        Doo::loadHelper('DooValidator');

        $_POST['content'] = trim($_POST['content']);

        //get defined rules and add show some error messages
        $validator = new DooValidator;
        $validator->checkMode = DooValidator::CHECK_SKIP;

        if($error = $validator->validate($_POST, 'post_create.rules')){
            $data['rootUrl'] = Doo::conf()->APP_URL;
            $data['title'] =  '发生错误!';
            $data['content'] =  '<p style="color:#ff0000;">'.$error.'</p>';
            $data['content'] .=  '<p>点击 <a href="javascript:history.back();">返回</a> 编辑.</p>';
            $this->renderc('blog_admin_msg', $data);
        }
        else{
            Doo::loadModel('Post');
            Doo::loadModel('Tag');
            Doo::autoload('DooDbExpression');
            $p = new Post($_POST);
            $p->createtime = new DooDbExpression('NOW()');

            //insert the post along with the tags
            if(self::$tags!=Null){
                $tags = array();
                foreach(self::$tags as $t){
                    $tg = new Tag;
                    $tg->name = $t;
                    $tags[] = $tg;
                }
                $id = $p->relatedInsert($tags);
            }
            //if no tags, just insert the post
            else{
                $id = $p->insert();
            }

            //clear the sidebar cache
            Doo::cache('front')->flushAllParts();

            $data['rootUrl'] = Doo::conf()->APP_URL;
            $data['title'] =  '保存成功!';
            $data['content'] =  '<p>你的日志已经创建成功!</p>';
            if($p->status==1)
                $data['content'] .=  '<p>点击  <a href="'.$data['rootUrl'].'blog/article/'.$id.'">查看</a>.</p>';
            $this->renderc('blog_admin_msg', $data);
        }
    }


    /**
     * Delete post
     */
    function deletePost(){
        $pid = intval($this->params['pid']);
        if($pid>0){
            Doo::loadModel('Post');
            $p = new Post;
            $p->id = $pid;
            $p->delete();

            //clear the sidebar cache
            Doo::cache('front')->flushAllParts();

            $data['rootUrl'] = Doo::conf()->APP_URL;
            $data['title'] =  '日志已经上传';
            $data['content'] =  "<p>ID为 $pid 的日志被成功删除!</p>";
            $this->renderc('blog_admin_msg', $data);
        }
    }
    
    /**
     * List unapproved comments
     */
    function listComment(){
        Doo::loadModel('Comment');
        $c = new Comment;
        $c->status = 0;
        $data['comments'] = $c->find(array('desc'=>'createtime'));
        $data['rootUrl'] = Doo::conf()->APP_URL;
        $this->renderc('blog_admin_comments', $data);
    }

    /**
     * Approve a comment
     */
    function approveComment(){
        Doo::loadModel('Comment');
        $c = new Comment;
        $c->id = intval($this->params['cid']);
        $comment = $c->find(array('limit'=>1, 'select'=>'id, post_id'));

        //if not exists, show error
        if($comment==Null){
            return 404;
        }

        //change status to Approved
        $comment->status = 1;
        $comment->update(array('field'=>'status'));

        Doo::loadModel('Post');
        Doo::autoload('DooDbExpression');

        //Update totalcomment field in Post
        $p = new Post;
        $p->id = $comment->post_id;
        $p->totalcomment = new DooDbExpression('totalcomment+1');
        $p->update(array('field'=>'totalcomment'));

        $data['rootUrl'] = Doo::conf()->APP_URL;
        $data['title'] =  '评论被通过!';
        $data['content'] =  "<p>评论已经被通过!</p>";
        $data['content'] .=  "<p>点击此处查看评论 <a href=\"{$data['rootUrl']}blog/article/$p->id#comment$comment->id\">查看</a></p>";
        $this->renderc('blog_admin_msg', $data);
    }

    /**
     * Reject (delete) unapproved comment
     */
    function rejectComment(){
        Doo::loadModel('Comment');
        $c = new Comment;
        $c->id = intval($this->params['cid']);
        $c->status = 0;
        $c->delete(array('limit'=>1));

        $data['rootUrl'] = Doo::conf()->APP_URL;
        $data['title'] =  '评论被拒绝!';
        $data['content'] =  "<p>评论已经被拒绝 &amp; 并被删除!</p>";
        $this->renderc('blog_admin_msg', $data);
    }

}
?>