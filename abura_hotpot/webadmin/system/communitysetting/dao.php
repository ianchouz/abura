<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/model/controller.php");
include_once("../../include/QueryParames.php");
include_once("../../include/upFile.php");
$menu_id = "communitysetting";
class main extends controller {  
    function __construct() {
        global $CFG;
        $this->id = "communitysetting";
        $this->table = $CFG->tbext . "webconfig";
        $this->cfg = $CFG->communitysetting; // 上傳路徑/編輯器路徑

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        
        $this->_xmls['fbid'] = array('type'=>'');
        $this->_xmls['fbpwd'] = array('type'=>'');
        $this->_xmls['fbadmin'] = array('type'=>'');
        $this->_xmls['mapkey'] = array('type'=>'');
        //$this->_xmls['fblink'] = array('type'=>'');
        //$this->_xmls['vImg'] = array('type'=>'img','set'=>$CFG->communitysetting);
    }
}
$dao = new main();
?>

