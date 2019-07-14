<?php
include_once("../applib.php");
include_once("../include/dbTool.php");
include_once("../include/model/controller.php");
include_once("../include/QueryParames.php");
include_once("../include/upFile.php");
$menu_id = "meal";
$useCate = 0;

class meal extends controller {
    public $fileds = array("summary");
    public $action_message = "";
    public $action_error_debug = "";
    public $dbrow = array();

    function __construct() {
        global $CFG;
        //資料表
        $this->table_cate = $CFG->tbext . "meal_cate";
        $this->table = $CFG->tbext . "meal";
        $this->table_stand = $CFG->tbext . "meal_stand";
        $this->condition = ""; // 基礎共用條件
        $this->condition_cate = ""; // 基礎共用條件
        $this->cfg = $CFG->meal; // 上傳路徑/編輯器路徑

        ##資料欄位名稱=預設值,欄位性質(int/bool;editor/editor_simple/textarea,空白表純文字)
        $this->_cols['seq'] = array('d4'=> $this->getMexSeq(), 'type'=>'');
        $this->_cols['inuse'] = array('d4'=> 1, 'type'=>'int'); //0:停用 1:啟用
        $this->_cols['title'] = array('d4'=> null, 'type'=>'');
        $this->_cols['type'] = array('d4'=> null, 'type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_cols['price'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['cover'] = array('type'=>'img','set'=> $CFG->meal_cover);
        $this->_xmls['cover'.'_alt'] = array('type'=>'');
        for($i=1;$i<=5;$i++) {
            $this->_xmls['cover'.$i] = array('type'=>'img','set'=> $CFG->meal);
            $this->_xmls['cover'.$i.'_alt'] = array('type'=>'');
        }
        $this->_xmls['broth'] = array('d4'=> null, 'type'=>'');
        for($i=1;$i<=3;$i++) {
          $this->_xmls['broth_items'.$i] = array('d4'=> null, 'type'=>'textarea', 'style'=>'textarea_small_height');
        }

        $this->dbrowDefault();

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        // $this->_xmls['cover'] = array('type'=>'img','set'=> $CFG->meal);
        //$this->_xmls['filename1'] = array('type'=>'file');
        //$this->_xmls['cover_large'] = array('type'=>'img','set'=> $this->cfg['cover_large']);
        //$this->_xmls['filename1'] = array('type'=>'file');
    }

    ////查詢條件區域
    public $qVO = null;
    function setQueryDefault() {
        $this->qVO = new QueryParames();
        $this->qVO->_names = array(
            "qkeyvalue",
            "qinuse",
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
        if($this->qVO->val("qinuse") != "all" && $this->qVO->val("qinuse") != "") {
            $sql .= " and inuse = " . pSQLBoolean($this->qVO->val("qinuse"));
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
    function loadForm() {

    }

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
        $datas =$this->toDATA();
                //$datas = array_merge($this->toXML_v2(),$this->toDATA());

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
                            if($column1=='') continue;

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
$dao = new meal();
?>
