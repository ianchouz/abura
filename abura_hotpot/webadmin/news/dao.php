<?php
include_once("../applib.php");
include_once("../include/dbTool.php");
include_once("../include/model/controller.php");
include_once("../include/QueryParames.php");
include_once("../include/upFile.php");
$menu_id = "news";
$useCate = 0;

class news extends controller {

    public $action_message = "";
    public $action_error_debug = "";
    public $dbrow = array();

    function __construct() {
        global $CFG;
        //資料表
        $this->table_cate = $CFG->tbext . "news_cate";
        $this->table = $CFG->tbext . "news";
        $this->table_stand = $CFG->tbext . "news_stand";
        $this->condition = ""; // 基礎共用條件
        $this->condition_cate = ""; // 基礎共用條件
        $this->cfg = $CFG->news;
        $this->cfg_inner = $CFG->news_inner; // 上傳路徑/編輯器路徑

        ##資料欄位名稱=預設值,欄位性質(int/bool;editor/editor_simple/textarea,空白表純文字)
        $this->_cols['seq'] = array('d4'=> $this->getMexSeq(), 'type'=>'');
        $this->_cols['cateId'] = array('d4'=> -1, 'type'=>'int');

        $this->_cols['createdate'] = array('d4'=> date("Y-m-d"), 'type'=>'');
        $this->_cols['publishtype'] = array('d4'=> "A", 'type'=>'');
        $this->_cols['startdate'] = array('d4'=> null, 'type'=>'');
        $this->_cols['stopdate'] = array('d4'=> null, 'type'=>'');
        $this->_cols['title'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_cols['summary'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_cols['content'] = array('type'=>'editor');
        $this->_cols['latest'] = array('d4'=> "N", 'type'=>'');

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        $this->_xmls['cover'] = array('type'=>'img','set'=> $this->cfg);
        $this->_xmls['cover'.'_alt'] = array('type'=>'');
        //$this->_xmls['cover_large'] = array('type'=>'img','set'=> $this->cfg['cover_large']);
        //$this->_xmls['filename1'] = array('type'=>'file');


        // $this->_cols['showtop'] = array('d4'=> "N", 'type'=>'');
        // $this->_cols['showindex'] = array('d4'=> "N", 'type'=>'');
        // $this->_cols['html_title'] = array('type'=>'xlang');
        // $this->_cols['html_keywords'] = array('type'=>'xlang');
        // $this->_cols['html_description'] = array('type'=>'xlang');
        // $this->_cols['webURL'] = array('d4'=> null, 'type'=>'xlang');
        // $this->_cols['webNewWin'] = array('d4'=> null, 'type'=>'xlang');
        $this->dbrowDefault();
    }

    ////查詢條件區域
    public $qVO = null;
    function setQueryDefault() {
        $this->qVO = new QueryParames();
        $this->qVO->_names = array(
            "qkeyvalue",
            "qpublishtype",
            "qshowindex",
            "qshowtop",
            "qlatest",
            "qcateid",
            "qcreatedate"
        );
        $this->qVO->load();
    }

    function getQueryResult() {
        global $CFG;
        global $pagetool;
        $this->setQueryDefault();

        if($GLOBALS['useCate'] == 1) {
            $select=",(select catename from " . $this->table_cate . " tableB where tableB.id=mTable.cateId) as catename";
        }
        $sql = "SELECT mTable.* $select FROM " . $this->table . " mTable where 1 ";
        if($this->qVO->val("qcateid") != "") {
            $sql .= " and cateId = " . pSQLStr($this->qVO->val("qcateid"));
        }
        if($this->qVO->val("qpublishtype") != "all" && $this->qVO->val("qpublishtype") != "") {
            $sql .= " and publishtype = " . pSQLStr($this->qVO->val("qpublishtype"));
        }
        if($this->qVO->val("qshowindex") != "") {
            $sql .= " and showindex = " . pSQLStr($this->qVO->val("qshowindex"));
        }
        if($this->qVO->val("qshowtop") != "") {
            $sql .= " and showtop = " . pSQLStr($this->qVO->val("qshowtop"));
        }
        if($this->qVO->val("qlatest") != "") {
            $sql .= " and latest = " . pSQLStr($this->qVO->val("qlatest"));
        }
        if($this->qVO->val("qkeyvalue") != "") {
            $vv = sql_real_escape_string($this->qVO->val("qkeyvalue"));
            $sql .= " and (title like '%" . $vv . "%'" . " or summary like '%" . $vv . "%'" . ")";
        }
        if($this->qVO->val("qcreatedate") != "") {
            $sql .= " and createdate = " . pSQLStr($this->qVO->val("qcreatedate"));
        }
        $sql .= " order by createdate desc, seq asc";
        //p($sql);
        return $pagetool->excute($sql);
    }



    //取得表單輸入的資料
    function loadForm() {}

    /*新增/編輯*/
    function saveData($typ = "insert") {
        global $CFG;
        global $_db, $_set;

        //建立表單輸入的資料
        $this->id = pgParam("id", null);
        if($typ == "update" && empty($this->id)) {
            $this->action_message = "無法取得編號";
            return false;
        }

        //建立一般輸入資料 FORM + XML
        $datas = $this->toDATA();

        //個性化欄位
        //$seq = pgParam("seq", null);
        //$datas["seq"] = pSQLStr($this->seq);

        //action
        if($typ == "update") {
            //主鍵設定
            $keydatas = array();
            $keydatas["id"] = pSQLInt($this->id);
            $result = $_db->_doUpdate($this->table, $datas, $keydatas);
        } else {
            $result = $_db->_doInsert($this->table, $datas);

            //相片
            $newid = sql_insert_id();
            include_once("photo/fn_set.inc");
            $mainid=$_POST["mainid"];
            $sql = sql_query("UPDATE ".$this->table."_photo SET cateid='$newid' WHERE cateid='$mainid'");
            rename($baseRoot."/$mainid", $baseRoot.$newid);
        }
        //error
        if(!$result) {
            if($_db->__db__error_no == "1062") {
                $this->action_message = "主鍵重複!!";
            } else {
                $this->action_message = $_db->__db__error_message;
            }
            return false;
        }
        return true;
    }


    ##############  自訂  ##############
}
$dao = new news();
?>
