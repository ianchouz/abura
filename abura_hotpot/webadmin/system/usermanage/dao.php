<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/QueryParames.php");
$menu_id = "userdata";
class userdata {
  //欄位
  public $dbrow = null;

  function __construct() {
    global $CFG;
    $this->dbrow = array();
    $this->dbrow["inuse"] = true;
  }

////查詢條件區域
  public $qVO = null;
  function setQueryDefault(){
    $this->qVO = new QueryParames();
    $this->qVO->_names = array("qinuse","qkeyvalue","qkeyword");
    $this->qVO->load();
  }
  function getQueryResult(){
    global $CFG;
    global $pagetool;

    $this->setQueryDefault();

    $sql = "SELECT * FROM ".$CFG->tbuserext."userdata  where 1 ";
    if ($this->qVO->val("qinuse") !="all" && $this->qVO->val("qinuse") !=""){
      $sql .= " and inuse = ".$this->qVO->val("qinuse");
    }
    if ($this->qVO->val("qkeyvalue") !=""){
      $sql .= " and (userid like '%".sql_real_escape_string($this->qVO->val("qkeyvalue"))."%'"
           ." or username like '%".sql_real_escape_string($this->qVO->val("qkeyvalue"))."%'"
           .")";
    }
    $tmpsql = "select topadmin from ".$CFG->tbuserext."userdata where inuse=true and userid='".$_SESSION['sess_uid']."'";
    $subquery = sql_query($tmpsql) or trigger_error("SQL", E_USER_ERROR);
    $r = sql_fetch_row($subquery);
    if (!$r[0]){
      $sql .= " and topadmin = false and userid not in ('Project','Maintain1','Maintain2')";
    }


    // echo "$sql<br>";
    return $pagetool->excute($sql);
  }

  function loadForm(){
    $this->userid = pgParam("userid",null);;
    $this->username = pgParam("username",null);
    $this->useremail = pgParam("useremail",null);
    $this->authority = "";
    if(isset($_POST['authority'])){
      foreach((array)$_POST['authority'] as $key => $value) {
        $this->authority .= ";".$value.";";
      }
    }
    //echo "<Br>authority:".$this->authority."<br>";
    $this->newuserpwd = pgParam("newuserpwd",null);
    $this->reuserpwd = pgParam("reuserpwd",null);
    $this->inuse = pgParam("inuse",true);
  }

  //讀取單筆資料
  function load(){
    global $CFG;
    $userid =pgParam("userid",null);
    if ($userid != null){
      $this->userid = $userid;
      $strSQLQ = "select * from ".$CFG->tbuserext."userdata where userid='".sql_real_escape_string($this->userid)."'";
      $query = @sql_query($strSQLQ);
      $this->dbrow = @sql_fetch_array($query);
    }
  }

  function insert(){
    global $CFG,$_db;
    $this->loadForm();
    if ($this->newuserpwd == $this->reuserpwd){
      if ($this->userid=="worldadm"){
        throw new Exception('此帳號無法新增!!');
      }
      $datas = array();
      $datas["userid"] = pSQLStr($this->userid);
      $datas["userpwd"] ='PASSWORD('.pSQLStr($this->newuserpwd).')';
      $datas["username"] = pSQLStr($this->username);
      $datas["useremail"] = pSQLStr($this->useremail);
      $datas["authority"] = pSQLStr($this->authority);
      $datas["inuse"] = pSQLBoolean($this->inuse);

      if (!$_db->_doInsert($CFG->tbuserext."userdata",$datas)){
        if ($_db->__db__error_no=="1062"){
          $this->action_message = "主鍵重複!!";
        }else{
          $this->action_message = $_db->__db__error_message;
        }
        return false;
      }
    }else{
      $this->action_message = "確認密碼與密碼不相同";
    }
  }

  function update(){
    global $CFG,$_db;

    try{
      $this->loadForm();
      if ($this->userid==""){
        throw new Exception('缺少使用者代號');
      }

      if ($this->userid=="worldadm"){
        throw new Exception('此帳號無法修改!!');
      }
      //主鍵設定
      $keydatas = array();
      $keydatas["userid"] = pSQLStr($this->userid);

      $datas = array();
      if($this->newuserpwd!=''){
        if ($this->newuserpwd != $this->reuserpwd){
          throw new Exception('新密碼與確認密碼不相同!!');
        }
        $datas["userpwd"] ='PASSWORD('.pSQLStr($this->newuserpwd).')';
      }
      $datas["username"] = pSQLStr($this->username);
      $datas["useremail"] = pSQLStr($this->useremail);
      $datas["authority"] = pSQLStr($this->authority);
      $datas["inuse"] = pSQLBoolean($this->inuse);

      if (!$_db->_doUpdate($CFG->tbuserext."userdata",$datas,$keydatas)){
        if ($_db->__db__error_no=="1062"){
          throw new Exception('主鍵重複!!');
        }
        throw new Exception($_db->__db__error_message);
      }
      return true;
    } catch (Exception $e) {
      $this->action_message = $e->getMessage();
      return false;
    }
  }


  function delrow(){
    global $CFG;
    //先取得要刪除的編號
    $cc = 0;
    $option = $_POST['sel'];
    $idvals = "";
    if(isset($option)) {
      foreach ((array)$option as $key =>$value){
        $idvals .= (($cc!=0)?",":"")."'$value'";
        $cc++;
      }
      $sql="delete from ".$CFG->tbuserext."userdata where userid in ($idvals)";
      $result = sql_query($sql) or die("Query failed : " . sql_error());

      $this->action_message = "總共刪除: $cc 筆！";
    }
  }

  function activerow(){
    global $CFG;
    //先取得要啟用的編號
    $cc = 0;
    $option = $_POST['sel'];
    $idvals = "";
    if(isset($option)) {
      foreach ((array)$option as $key =>$value){
        //echo "{$value}<br />";
        $idvals .= (($cc!=0)?",":"")."'$value'";
        $cc++;
      }
      $sql="update ".$CFG->tbuserext."userdata set inuse=true where userid in ($idvals)";
      $result = sql_query($sql) or die("Query failed : " . sql_error());

      $this->action_message = "總共啟用: $cc 筆！";
    }
  }
  function unlockrow(){
    global $CFG;
    //先取得要啟用的編號
    $cc = 0;
    $option = $_POST['sel'];
    $idvals = "";
    if(isset($option)) {
      foreach ((array)$option as $key =>$value){
        //echo "{$value}<br />";
        $idvals .= (($cc!=0)?",":"")."'$value'";
        $cc++;
      }
      $sql="update ".$CFG->tbuserext."userdata set lockaccount=false,locktime=null where userid in ($idvals)";
      @sql_query($sql);
      $cc = sql_affected_rows();
      $this->action_message = "總共解鎖: $cc 筆！";
    }
  }
  function stoprow(){
    global $CFG;
    //先取得要啟用的編號
    $cc = 0;
    $option = $_POST['sel'];
    $idvals = "";
    if(isset($option)) {
      foreach ((array)$option as $key =>$value){
        //echo "{$value}<br />";
        $idvals .= (($cc!=0)?",":"")."'$value'";
        $cc++;
      }
      $sql="update ".$CFG->tbuserext."userdata set inuse=false where userid in ($idvals)";
      $result = sql_query($sql) or die("Query failed : " . sql_error());

      $this->action_message = "總共停用: $cc 筆！";
    }
  }

  function loadMenu(){
    global $CFG;
    $slq = "select id,text from ".$CFG->tbext."webmenu where uplevel = -1 and inuse=true and authority=true order by seq";
    return sql_query($slq);
  }
  function loadSubMenu($uplevel){
    global $CFG;
    $slq = "select id,text from ".$CFG->tbext."webmenu where uplevel = $uplevel and inuse=true and authority=true order by seq";
    return sql_query($slq);
  }
}
?>
