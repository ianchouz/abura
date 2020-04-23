<?php
include_once("../../applib.php");
$menu_id = "company_info";
$page_navigation = "";

class company_info {
  //欄位
  public $id = "company_info";
  public $oldpw="";

  function __construct() {

  }

  function parseXML($xmlstring){
    global $CFG;
    if ($xmlstring!= null){
      $xmlvo = new parseXML($xmlstring);
      $this->company_name = $xmlvo->value('/content/company_name');
      // $this->company_name_en = $xmlvo->value('/content/company_name_en');
      $this->mail_receiver = $xmlvo->value('/content/mail_receiver');
      $this->mail_sender = $xmlvo->value('/content/mail_sender');

      $this->smtp = $xmlvo->value('/content/smtp');
      $this->account = $xmlvo->value('/content/account');
      $this->pwd = $xmlvo->value('/content/pwd');
      $this->port = $xmlvo->value('/content/port');
      $this->ssl = $xmlvo->value('/content/ssl');

      $this->edm_smtp = $xmlvo->value('/content/edm_smtp');
      $this->edm_account = $xmlvo->value('/content/edm_account');
      $this->edm_pwd = $xmlvo->value('/content/edm_pwd');
      $this->edm_port = $xmlvo->value('/content/edm_port');
      $this->edm_ssl = $xmlvo->value('/content/edm_ssl');


    }
  }

    function toXML(){
      $xmlstring = '<content>'
                .'<company_name>'.turnCDDATA($this->company_name).'</company_name>'
                .'<company_name_en>'.turnCDDATA($this->company_name_en).'</company_name_en>'
                .'<mail_receiver>'.turnCDDATA($this->mail_receiver).'</mail_receiver>'
                .'<mail_sender>'.turnCDDATA($this->mail_sender).'</mail_sender>'
                .'<smtp>'.turnCDDATA($this->smtp).'</smtp>'
                .'<account>'.turnCDDATA($this->account).'</account>'
                .'<port>'.turnCDDATA($this->port).'</port>'
                .'<ssl>'.turnCDDATA($this->ssl).'</ssl>'
                .'<edm_smtp>'.turnCDDATA($this->edm_smtp).'</edm_smtp>'
                .'<edm_account>'.turnCDDATA($this->edm_account).'</edm_account>'

                .'<edm_port>'.turnCDDATA($this->edm_port).'</edm_port>'
                .'<edm_ssl>'.turnCDDATA($this->edm_ssl).'</edm_ssl>'
                ;
                if($this->pwd!="#####"){
                  $xmlstring .='<pwd>'.turnCDDATA($this->pwd).'</pwd>';
                }else{
                  $xmlstring .='<pwd>'.turnCDDATA($this->oldpwd).'</pwd>';
                }
                if($this->edm_pwd!="#####"){
                  $xmlstring .='<edm_pwd>'.turnCDDATA($this->edm_pwd).'</edm_pwd>';
                }else{
                  $xmlstring .='<edm_pwd>'.turnCDDATA($this->edm_oldpwd).'</edm_pwd>';
                }
                $xmlstring .='</content>';
                return $xmlstring;
    }

  function loadForm(){
    $this->company_name = pgParam("company_name",null);
    $this->company_name_en = pgParam("company_name_en",null);
    $this->mail_receiver = pgParam("mail_receiver",null);
    $this->mail_sender = pgParam("mail_sender",null);

    $this->smtp = pgParam("smtp",null);
    $this->account = pgParam("account",null);
    $this->pwd = pgParam("pwd",null);
    $this->port = pgParam("port",null);
    $this->ssl = pgParam("ssl",null);

    $this->edm_smtp = pgParam("edm_smtp",null);
    $this->edm_account = pgParam("edm_account",null);
    $this->edm_pwd = pgParam("edm_pwd",null);
    $this->edm_port = pgParam("edm_port",null);
    $this->edm_ssl = pgParam("edm_ssl",null);

    $this->loadpw();
    $this->xmlcontent = $this->toXML();
  }

  //讀取單筆資料
  function load(){
    global $CFG;
    $strSQLQ = "select * from ".$CFG->tbext."webconfig where id='".mysql_escape_string($this->id)."'";
   // echo "<br>strSQLQ:".$strSQLQ;
    $query = mysql_query($strSQLQ);
    while ($row = mysql_fetch_array($query)) {
      $this->xmlcontent =$row['xmlcontent'];
    }
     //echo "<br>xmlcontent:".$this->xmlcontent;
    if (isset($this->xmlcontent)){
      $this->parseXML($this->xmlcontent);
    }
  }
  function loadpw(){
    global $CFG;
    $query = mysql_query("select * from ".$CFG->tbext."webconfig where id='".mysql_escape_string($this->id)."'");
    $row = mysql_fetch_array($query);
    if (isset($row['xmlcontent'])){
      $xmlvo = new parseXML($row['xmlcontent']);
       $this->edm_oldpwd = $xmlvo->value('/content/edm_pwd');
       $this->oldpwd = $xmlvo->value('/content/pwd');
    }
  }
  //修改
  function update(){
    global $CFG;

    $this->loadForm();

    $strSQL = "delete from ".$CFG->tbext."webconfig where id=".pSQLStr($this->id);
    $result = mysql_query($strSQL);
    $strSQL = "insert into ".$CFG->tbext."webconfig (xmlcontent,id) values("
               ." ".pSQLStr($this->xmlcontent)
               ." ,".pSQLStr($this->id).")";
    $result = mysql_query($strSQL);
    if ($result){
      $this->action_message = "true";
    }else{
      $this->action_message = "修改失敗";
      echo "<br>".mysql_error()."<br>".$strSQL."<br>";
    }
  }
}
?>
