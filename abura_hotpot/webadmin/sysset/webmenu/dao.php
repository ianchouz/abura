<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/QueryParames.php");
$menu_id = "sysset-webmenu";
$page_navigation = "系統選單";


class webmenu {
    ////查詢條件區域
    var $qVO = null;
    function setQueryDefault() {
        $this->qVO = new QueryParames();
        $this->qVO->_names = array(
            "qinuse",
            "qkeyvalue",
            "quplevel"
        );
        $this->qVO->load();
    }
    function getQueryResult() {
        global $CFG;
        global $pagetool;
        $this->setQueryDefault();
        $sql = "SELECT * FROM " . $CFG->tbext . "webmenu  where 1 ";
        if($this->qVO->val("qinuse") != "all" && $this->qVO->val("qinuse") != "") {
            $sql .= " and inuse = " . pSQLBoolean($this->qVO->val("qinuse"));
        }
        if($this->qVO->val("qkeyvalue") != "") {
            $vv = sql_real_escape_string($this->qVO->val("qkeyvalue"));
            $sql .= " and (text like '%" . $vv . "%'" . " or keyname like '%" . $vv . "%'" . ")";
        }
        
        if($this->qVO->val("quplevel") != "") {
            $sql .= " and uplevel = " . $this->qVO->val("quplevel");
        } else {
            $sql .= " and uplevel = -1";
        }
        
        $sql .= " order by seq";
        //echo "$sql<br>";
        return $pagetool->excute($sql);
    }
    ////============
    //欄位
    public $id;
    public $uplevel = "";
    public $text = "";
    public $seq = "";
    public $inuse = true;
    public $url = "";
    public $leaf = true;
    public $keyname = "";
    public $hidden = false;
    public $authority = false;
    //是否設定網頁標題關鍵字
    public $htmlset = false;
    
    public $action_message = "";
    
    function __construct() {
        
    }
    
    public $categoryList = array();
    
    function loadCategoryList() {
        global $CFG;
        $this->categoryList[] = array(
            'id' => '-1',
            'text' => '根目錄'
        );
        $pid = -1;
        $slq = "select id,text,uplevel from " . $CFG->tbext . "webmenu where leaf = false and inuse=true order by seq";
        $query = sql_query($slq);
        while($row = sql_fetch_array($query)) {
            $this->categoryList[] = array(
                'id' => $row['id'],
                'text' => $row['text']
            );
        }
    }
    
    function loadForm() {
        $this->id = pgParam("id", null);
        $this->seq = pgParam("seq", null);
        $this->text = pgParam("text", null);
        $this->uplevel = pgParam("uplevel", -1);
        $this->url = pgParam("url", null);
        $this->leaf = pgParam("leaf", true);
        $this->inuse = pgParam("inuse", true);
        $this->hidden = pgParam("hidden", true);
        $this->keyname = pgParam("keyname", null);
        $this->authority = pgParam("authority", false);
        $this->htmlset = pgParam("htmlset", false);
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
        $strSQLQ = "select * from " . $CFG->tbext . "webmenu where id='" . sql_real_escape_string($id) . "'";
        $query = sql_query($strSQLQ);
        $this->dbrow = sql_fetch_array($query);
        return true;
    }
    ////==新增
    function insert() {
        global $CFG;
        //加入陣列
        $datas = array();
        $datas["seq"] = pSQLStr($this->seq);
        $datas["text"] = pSQLStr($this->text);
        $datas["keyname"] = pSQLStr($this->keyname);
        $datas["uplevel"] = pSQLInt($this->uplevel);
        $datas["url"] = pSQLStr($this->url);
        $datas["leaf"] = pSQLBoolean($this->leaf);
        $datas["inuse"] = pSQLBoolean($this->inuse);
        $datas["authority"] = pSQLBoolean($this->authority);
        $datas["htmlset"] = pSQLBoolean($this->htmlset);
        $datas["hidden"] = pSQLBoolean($this->hidden);
        global $_db;
        if(!$_db->_doInsert("" . $CFG->tbext . "webmenu", $datas)) {
            if($_db->__db__error_no == "1062") {
                $this->action_message = "主鍵重複!!";
            } else {
                $this->action_message = $_db->__db__error_message;
            }
            return false;
        }
        return true;
    }
    
    ////==修改
    function update() {
        global $CFG;
        if(empty($this->id)) {
            $this->action_message = "缺少編號";
            return false;
        }
        
        //主鍵設定
        $keydatas = array();
        $keydatas["id"] = pSQLStr($this->id);
        
        //其他修改的資料欄位
        $datas = array();
        $datas["seq"] = pSQLStr($this->seq);
        $datas["text"] = pSQLStr($this->text);
        $datas["keyname"] = pSQLStr($this->keyname);
        $datas["uplevel"] = pSQLInt($this->uplevel);
        $datas["url"] = pSQLStr($this->url);
        $datas["leaf"] = pSQLBoolean($this->leaf);
        $datas["inuse"] = pSQLBoolean($this->inuse);
        $datas["authority"] = pSQLBoolean($this->authority);
        $datas["htmlset"] = pSQLBoolean($this->htmlset);
        $datas["hidden"] = pSQLBoolean($this->hidden);
        global $_db;
        if(!$_db->_doUpdate("" . $CFG->tbext . "webmenu", $datas, $keydatas)) {
            if($_db->__db__error_no == 1062) {
                $this->action_message = "主鍵重複!!";
            } else {
                $this->action_message = $_db->__db__error_message;
            }
            return false;
        }
        return true;
    }
    
    function updaterowdata() {
        global $CFG;
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $tmpseq = pgParam("seq_$value", null);
                if($tmpseq == null) {
                    $tmpseq = "";
                }
                $sql = "update " . $CFG->tbext . "webmenu set seq='$tmpseq' where id =$value";
                $result = sql_query($sql);
                if($result) {
                    $cc++;
                }
            }
            $this->action_message = "總共更新: $cc 筆！";
        }
    }
    function delrow() {
        global $CFG;
        //先取得要刪除的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "delete from " . $CFG->tbext . "webmenu where id in ($idvals) or uplevel in ($idvals)";
            $result = @sql_query($sql);
            if($result) {
                $this->action_message = "總共刪除: $cc 筆！";
            } else {
                $this->action_message = "刪除失敗!!";
            }
        }
    }
    
    function activerow() {
        global $CFG;
        //先取得要啟用的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                //echo "{$value}<br />";
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "update " . $CFG->tbext . "webmenu set inuse=true where id in ($idvals)";
            $result = @sql_query($sql);
            if($result) {
                $this->action_message = "總共啟用: $cc 筆！";
            } else {
                $this->action_message = "啟用失敗!!";
            }
        }
    }
    
    function stoprow() {
        global $CFG;
        //先取得要啟用的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                //echo "{$value}<br />";
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "update " . $CFG->tbext . "webmenu set inuse=false where id in ($idvals) ";
            $result = @sql_query($sql);
            if($result) {
                $this->action_message = "總共停用: $cc 筆！";
            } else {
                $this->action_message = "停用失敗!!";
            }
        }
    }
    
    function getMexSeq() {
        global $CFG;
        $strSQLQ = "select max(seq) as seq from " . $CFG->tbext . "webmenu";
        if($this->uplevel != "") {
            $strSQLQ .= " where uplevel=" . $this->uplevel;
        }
        //echo "$strSQLQ<br>";
        $query = sql_query($strSQLQ);
        while($row = sql_fetch_array($query)) {
            $seq = $row['seq'];
        }
        $seq = formatNUM($seq, 1, 5);
        return $seq;
    }
    
    function countSubCategory($uplevel) {
        global $CFG;
        $subsql = "select count(*) as cc from " . $CFG->tbext . "webmenu where uplevel=" . $uplevel;
        $subquery = sql_query($subsql);
        $r = sql_fetch_row($subquery);
        return $r[0];
    }
    function getPIDData($pid) {
        global $CFG;
        $subsql = "select text,uplevel,id from " . $CFG->tbext . "webmenu where id=" . $pid;
        $subquery = sql_query($subsql);
        if($subquery) {
            return sql_fetch_row($subquery);
        } else {
            return null;
        }
    }
}


$dao = new webmenu();

?>