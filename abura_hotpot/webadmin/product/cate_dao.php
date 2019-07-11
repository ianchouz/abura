<?php
include_once("../applib.php");
include_once("../include/dbTool.php");
include_once("../include/model/controllerCate.php");
include_once("../include/model/model_fields.php");
include_once("../include/QueryParames.php");
include_once("../include/upFile.php");
$menu_id = "product";
$useCate = 1;
$useMaxAuth = 0;

if(!$useMaxAuth){
    $open=true;
}else{
    $open=false;
    if($_SESSION['authority']=="all")$open=true;
}

if(!$useCate)
    header("location:main.php");

class category extends controller {

    public $action_message = "";
    public $action_error_debug = "";
    public $dbrow = array();

    function __construct() {
        global $CFG;
        //預設值
        $this->tree_layer = 2; // 層數控制
        $this->use_tree = $this->tree_layer >= 2; // 使用多層分類? (兩層以上自動啟用)
        $leaf_default = $this->use_tree ? 0 : 1;
        $this->type = 1;

        //資料表
        $this->table_cate = $CFG->tbext . "product_cate";
        $this->table = $CFG->tbext . "product";
        $this->condition = ""; // 基礎共用條件
        $this->cfg = $CFG->product_cate; // 上傳路徑/編輯器路徑

        ##資料欄位名稱=預設值,欄位性質(int/bool;editor/editor_simple/textarea,空白表純文字)
        $this->_cols['inuse'] = array('d4'=> 1, 'type'=>'int'); //0:停用 1:啟用
        $this->_cols['seq'] = array('d4'=> $this->getMexSeq(), 'type'=>'');
        // $this->_cols['type'] = array('d4'=> $this->type, 'type'=>'int');
        $this->_cols['pid'] = array('d4'=> -1, 'type'=>'int');
        $this->_cols['leaf'] = array('d4'=> $leaf_default, 'type'=>'bool'); //0:分類目錄 1:產品目錄
        $this->_cols['catename'] = array('d4'=> null, 'type'=>'');

        // $this->_xmls['contents'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // $this->_xmls['contents'] = array('type'=>'');
        // Banner IMG
        for($i=1;$i<=5;$i++) {
            $this->_xmls['cover'.$i] = array('type'=>'img','set'=> $CFG->product_cate);
        }

        $this->dbrowDefault();
    }

    ////查詢條件區域
    public $qVO = null;
    function setQueryDefault() {
        $this->qVO = new QueryParames();
        $this->qVO->_names = array(
            "qkeyvalue",
            "qinuse",
            "qcateid"
        );
        $this->qVO->load();
    }

    function getQueryResult() {
        global $CFG;
        global $pagetool;
        $this->setQueryDefault();
        $sql = "SELECT * FROM " . $this->table_cate . " where 1 " . $this->condition;

        if($this->qVO->val("qinuse") != "all" && $this->qVO->val("qinuse") != "") {
            $sql .= " and inuse = " . pSQLBoolean($this->qVO->val("qinuse"));
        }
        if($this->qVO->val("qkeyvalue") != "") {
            $vv = sql_real_escape_string($this->qVO->val("qkeyvalue"));
            $sql .= " and (catename like '%" . $vv . "%'" . ")";
        }
        if($this->qVO->val("qcateid") != "-1" && $this->qVO->val("qcateid") != "") {
            $sql .= " and pid = " . pSQLInt($this->qVO->val("qcateid"));
        } else {
            $sql .= " and pid = -1";
        }
        $sql .= " order by inuse desc,seq";
        //p($sql);
        return $pagetool->excute($sql);
    }

    //讀取單筆資料
    function load($id = "") {
        global $CFG;
        if(empty($id)) {
            $id = pgParam("id", null);
        }
        if(empty($id)) {
            return false;
        }
        $strSQLQ = "select * from " . $this->table_cate . " where id='" . sql_real_escape_string($id) . "'" . $this->condition;
        $query = sql_query($strSQLQ);
        $this->dbrow = sql_fetch_array($query);
        //讀取XML資料
        if(isset($this->dbrow["imagexml"])) {
            $this->parseXML($this->dbrow["imagexml"]);
        }

        foreach($this->_cols as $key => $tmp) {

            if($tmp['type']=='xlang_editor_simple' || $tmp['type']=='xlang_editor'){
                $arr = $this->mergeLangArr($key);
                $this->parseXML_v2($this->dbrow[$key],$arr);
            }else if($tmp['type']=='xlang'){
                $arr = $this->mergeLangArr($key);
                $this->parseXML_v2($this->dbrow[$key],$arr);
            }else if($tmp['type']=='xml'){
                //$this->parseXML_v2($this->dbrow["html_title"],array("html_title_tw"));
            }
        }
        return true;
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
            $result = $_db->_doUpdate($this->table_cate, $datas, $keydatas);
        } else {
            $result = $_db->_doInsert($this->table_cate, $datas);
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
$dao = new category();
?>
