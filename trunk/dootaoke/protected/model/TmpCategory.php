<?php
Doo::loadCore('db/DooModel');
class TmpCategory extends DooModel{

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
     * @var date
     */
    public $update_at;

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

    public $_table = 'tmp_category';
    public $_primarykey = 'cid';
    public $_fields = array('cid','parent_cid','is_parent','name','sort_order','status','update_at','title','keywords','description');

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

                'update_at' => array(
                        array( 'date' ),
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

}
?>