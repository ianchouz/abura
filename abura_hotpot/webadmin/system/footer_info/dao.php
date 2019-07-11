<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/model/controller.php");
include_once("../../include/QueryParames.php");
include_once("../../include/upFile.php");
$menu_id = "footer_info";

class main extends controller {

    function __construct() {
        global $CFG;
        $this->id = "footer_info";
        $this->table = $CFG->tbext . "webconfig";
        $this->cfg = $CFG->footer_info; // 上傳路徑/編輯器路徑

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        $this->_xmls['title'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['tel_title'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['tel'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['add_title'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['add'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['time_title'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['time'] = array('type'=>'', 'style'=>'input_half');
        $this->_xmls['link_facebook'] = array('type'=>'');
        $this->_xmls['link_ig'] = array('type'=>'');

        // $this->_xmls['description_'.$lkey] = array('type'=>'editor_simple');
        // $this->_xmls['copyright_index_'.$lkey] = array('type'=>'editor_simple');
        // $this->_xmls['fb_'.$lkey] = array('type'=>'');
    }
}
$dao = new main();
?>
