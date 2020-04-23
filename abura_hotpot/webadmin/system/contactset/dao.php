<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/model/controller.php");
include_once("../../include/QueryParames.php");
include_once("../../include/upFile.php");
$menu_id = "contactset";

class main extends controller {

    function __construct() {
        global $CFG;
        $this->id = "contactset";
        $this->table = $CFG->tbext . "webconfig";
        $this->cfg = $CFG->contactset; // 上傳路徑/編輯器路徑

        $this->_xmls['company'] = array('type'=>'');
        $this->_xmls['tel'] = array('type'=>'');
        $this->_xmls['email'] = array('type'=>'');
        $this->_xmls['fax'] = array('type'=>'');
        $this->_xmls['add'] = array('type'=>'');
        $this->_xmls['website'] = array('type'=>'');

        $this->_xmls['map'] = array('type'=>'img','set'=> $CFG->contactset);
        $this->_xmls['map-mbl'] = array('type'=>'img','set'=> $CFG->contactset_mbl);

        // $this->_xmls['subjects'] = array('type'=>'');
        $this->_xmls['sendsuccess'] = array('type'=>'textarea');
        $this->_xmls['recipient'] = array('type'=>'');

        $this->_xtable["Rail"]=array('fields' => array( array("name"=>"選項名稱","width"=>300),array("name"=>"選項介紹","width"=>300),array("name"=>"選項積分","width"=>100)));
    }
}
$dao = new main();
?>
