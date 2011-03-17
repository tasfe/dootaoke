<?php
Doo::loadCore('db/DooModel');
class Category extends DooModel{

    /**
     * @var char Max length is 8.
     */
    public $cid;

    /**
     * @var char Max length is 8.
     */
    public $parent_cid;

    /**
     * @var varchar Max length is 8.
     */
    public $is_parent;

    /**
     * @var varchar Max length is 100.
     */
    public $name;

    /**
     * @var int Max length is 4.
     */
    public $sort_order;

    /**
     * @var varchar Max length is 8.
     */
    public $status;

    /**
     * @var varchar Max length is 255.
     */
    public $title;

    /**
     * @var varchar Max length is 255.
     */
    public $keywords;

    /**
     * @var varchar Max length is 255.
     */
    public $description;

    /**
     * @var date
     */
    public $update_at;

    public $_table = 'category';
    public $_primarykey = 'cid';
    public $_fields = array('cid','parent_cid','is_parent','name','sort_order','status','title','keywords','description','update_at');

    public function getVRules() {
        return array(
                'cid' => array(
                        array( 'maxlength', 8 ),
                        array( 'notnull' ),
                ),

                'parent_cid' => array(
                        array( 'maxlength', 8 ),
                        array( 'notnull' ),
                ),

                'is_parent' => array(
                        array( 'maxlength', 8 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'sort_order' => array(
                        array( 'integer' ),
                        array( 'maxlength', 4 ),
                        array( 'notnull' ),
                ),

                'status' => array(
                        array( 'maxlength', 8 ),
                        array( 'notnull' ),
                ),

                'title' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'keywords' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'description' => array(
                        array( 'maxlength', 255 ),
                        array( 'optional' ),
                ),

                'update_at' => array(
                        array( 'date' ),
                        array( 'notnull' ),
                )
            );
    }

    public function validate($checkMode='all'){
        //You do not need this if you extend DooModel or DooSmartModel
        //MODE: all, all_one, skip
        Doo::loadHelper('DooValidator');
        $v = new DooValidator;
        $v->checkMode = $checkMode;
        return $v->validate(get_object_vars($this), $this->getVRules());
    }
    
    /**
     * 通过cid查询
     */
    public function selectCatByCid($cid){
    	$opt['where'] = 'cid=' . $cid;
    	return $this->getOne($opt);
    }
    
    /**
     * 所有分类
     */
    public function selectAllCats(){
    	$opt = array();
    	
    	$all_cats = $this->find($opt);
    	$cats = array();
    	foreach($all_cats as $cat) {
    		// 去掉多余属性
    		$cat->_fields = null;
    		$cat->_table = null;
    		$cat->_primarykey = null;
    		$cats[$cat->cid] = $cat;
    	}
    	return $cats;
    }
    
	/*
	* 获得所有子类数据
	*/
	public function selectAllSubCatsByPid($pid) {
		if($pid == null || $pid == '') return null;

		$opt['where'] = 'parent_cid=' . $pid;
		return $this->find($opt);
	}
	
	/**
	* 获得指定分类的上层分类
	*/
	public function getParentCatsByCid($catid) {
		$cat = $this->selectCatByCid($catid);

		if($cat == null) return null;
		$result[$cat->cid] = $cat;
		$parent_cid = $cat->parent_cid;
		$cid = $cat->cid;
		while($cid != '0' && isset($cid) && $cid != null) {
			$tmp = $this->selectCatByCid($parent_cid);
			if(!$tmp) break; 
			$result[$tmp->cid] = $tmp;
			$cid = $tmp->cid;
			$parent_cid = $tmp->parent_cid;
		}
		
		return $result;
	}

}
?>