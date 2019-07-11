<?php
include_once("../applib.php");
include_once("../include/dbTool.php");
include_once("../include/model/controller.php");
include_once("../include/QueryParames.php");
include_once("../include/upFile.php");
$menu_id = "product";
$useCate = 1;

class news extends controller {

    public $action_message = "";
    public $action_error_debug = "";
    public $dbrow = array();

    function __construct() {
        global $CFG;
        //資料表
        $this->table_cate = $CFG->tbext . "product_cate";
        $this->table = $CFG->tbext . "product";
        $this->table_stand = $CFG->tbext . "product_stand";
        $this->condition = ""; // 基礎共用條件
        $this->condition_cate = ""; // 基礎共用條件
        $this->cfg = $CFG->product; // 上傳路徑/編輯器路徑

        ##資料欄位名稱=預設值,欄位性質(int/bool;editor/editor_simple/textarea,空白表純文字)
        $this->_cols['seq'] = array('d4'=> $this->getMexSeq(), 'type'=>'');
        $this->_cols['inuse'] = array('d4'=> 1, 'type'=>'int'); //0:停用 1:啟用
        $this->_cols['cateId'] = array('d4'=> -1, 'type'=>'int');
        $this->_cols['title'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_cols['note'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_cols['price'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // $this->_cols['content'] = array('type'=>'editor');

        // $this->_cols['showtop'] = array('d4'=> "N", 'type'=>'');
        // $this->_cols['showindex'] = array('d4'=> "N", 'type'=>'');

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        $this->_xmls['cover'] = array('type'=>'img','set'=> $CFG->product);

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
        if($this->qVO->val("qkeyvalue") != "") {
            $vv = sql_real_escape_string($this->qVO->val("qkeyvalue"));
            $sql .= " and (title like '%" . $vv . "%'" . ")";
        }
        if($this->qVO->val("qcreatedate") != "") {
            $sql .= " and createdate = " . pSQLStr($this->qVO->val("qcreatedate"));
        }
        $sql .= " order by seq asc";
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
        $this->deal_other();
        return true;
    }


    function deal_other() {
        global $CFG, $_db;

        // 動態條列工具
        if($this->id) {
            if(is_array($_POST['subs'])) {
                foreach($_POST['subs'] as $subkey => $item) {
                    //先刪除原本的所有關聯
                    $delsql = "delete from " . $CFG->tbext . "product_rel where typeid='".$this->id."' AND subkey='$subkey'";
                    @sql_query($delsql);
                    ##相關
                    if(is_array($item['column1'])) {
                        foreach($item['column1'] as $key => $column1) {
                            $column2 = $item['column2'][$key];
                            $column3 = $item['column3'][$key];
                            $column4 = $item['column4'][$key];
                            $column5 = $item['column5'][$key];
                            $column6 = $item['column6'][$key];
                            $column7 = $item['column7'][$key];
                            $column8 = $item['column8'][$key];
                            $column9 = $item['column9'][$key];
                            $column10 = $item['column10'][$key];
                            if($column1=='' || $column2=='') continue;

                            $datas = array();
                            $datas["typeid"] = pSQLStr($this->id);
                            $datas["subkey"] = pSQLStr($subkey);
                            // $datas["xmlcontent"] = pSQLStr($this->toXML());
                            $datas["column1"] = pSQLStr($column1);
                            $datas["column2"] = pSQLStr($column2);
                            $datas["column3"] = pSQLStr($column3);
                            $datas["column4"] = pSQLStr($column4);
                            $datas["column5"] = pSQLStr($column5);
                            $datas["column6"] = pSQLStr($column6);
                            $datas["column7"] = pSQLStr($column7);
                            $datas["column8"] = pSQLStr($column8);
                            $datas["column9"] = pSQLStr($column9);
                            $datas["column10"] = pSQLStr($column10);
                            $result = $_db->_doInsert($CFG->tbext . "product_rel", $datas);
                        }
                    }
                }
            }
        }
    }

    ##############  自訂  ##############
}
$dao = new news();
?>
