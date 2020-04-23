<?php
include("../../applib.php");
$menu_id = "header_info";
$page_navigation = "";

class header_info {
  //欄位
  public $id = "header_info";

  public $html_title = "";
  public $html_keywords = "";
  public $html_description = "";

  public $xmlcontent = "";

  public $action_message = "";

  public $keyname = array();
  public $keynameshow = array();
  public $keyarray = array();
  public $drow=array();

  public $en_switch ="";

  function __construct() {
    global $CFG;
    //撈取需要設定的網頁標題
    $strSQLQ = "select distinct keyname,text from ".$CFG->tbext."webmenu where htmlset=true and inuse=true order by uplevel,seq";
    $query = mysql_query($strSQLQ);
    while ($row = mysql_fetch_array($query)){
      $this->keyname[] = $row['keyname'];
      $this->keynameshow[$row['keyname']] = $row['text'];
    }
  }

  function parseXML($xmlstring){
    global $CFG;
    if ($xmlstring!= null){
      $xmlvo = new parseXML($xmlstring);

      $this->drow["en_switch"] = $xmlvo->value('/content/en_switch');
      
      foreach($CFG->langs  as $lkey=>$lval){
      $this->drow["html_title_".$lkey] = $xmlvo->value('/content/html_title_'.$lkey);
      $this->drow["html_keywords_".$lkey] = $xmlvo->value('/content/html_keywords_'.$lkey);
      $this->drow["html_description_".$lkey] = $xmlvo->value('/content/html_description_'.$lkey);
      $this->drow["headscript_".$lkey] = $xmlvo->value('/content/headscript_'.$lkey);
      $this->drow["endbodyscript_".$lkey] = $xmlvo->value('/content/endbodyscript_'.$lkey);

      $this->drow["index_html_title_".$lkey] = $xmlvo->value('/content/index_html_title_'.$lkey);
      $this->drow["index_html_keywords_".$lkey] = $xmlvo->value('/content/index_html_keywords_'.$lkey);
      $this->drow["index_html_description_".$lkey] = $xmlvo->value('/content/index_html_description_'.$lkey);
      $this->drow["index_headscript_".$lkey] = $xmlvo->value('/content/index_headscript_'.$lkey);
      $this->drow["index_endbodyscript_".$lkey] = $xmlvo->value('/content/index_endbodyscript_'.$lkey);
      }
      //其他動態
      foreach ($this->keyname as $key =>$value){
         foreach($CFG->langs  as $lkey=>$lval){
        $tmp1 = $xmlvo->value('/content/'.$value."_".$lkey.'/html_title');
        $tmp2 = $xmlvo->value('/content/'.$value."_".$lkey.'/html_keywords');
        $tmp3 = $xmlvo->value('/content/'.$value."_".$lkey.'/html_description');
        $tmp4 = $xmlvo->value('/content/'.$value."_".$lkey.'/headscript');
        $tmp5 = $xmlvo->value('/content/'.$value."_".$lkey.'/endbodyscript');

        $tmparray = array("html_title"=>$tmp1,"html_keywords"=>$tmp2,"html_description"=>$tmp3,"headscript"=>$tmp4,"endbodyscript"=>$tmp5);
        $this->keyarray[$value."_".$lkey] =$tmparray;
        }
      }
    }
  }

  function toXML(){
    global $CFG;
      $xmlstring = '<content>';
      foreach($CFG->langs  as $lkey=>$lval){
      $xmlstring.='<html_title_'.$lkey.'>'.turnCDDATA($this->drow["html_title_".$lkey]).'</html_title_'.$lkey.'>'
                .'<html_keywords_'.$lkey.'>'.turnCDDATA($this->drow["html_keywords_".$lkey]).'</html_keywords_'.$lkey.'>'
                .'<html_description_'.$lkey.'>'.turnCDDATA($this->drow["html_description_".$lkey]).'</html_description_'.$lkey.'>'
                .'<headscript_'.$lkey.'>'.turnCDDATA($this->drow["headscript_".$lkey]).'</headscript_'.$lkey.'>'
                .'<endbodyscript_'.$lkey.'>'.turnCDDATA($this->drow["endbodyscript_".$lkey]).'</endbodyscript_'.$lkey.'>'
                .'<index_html_title_'.$lkey.'>'.turnCDDATA($this->drow["index_html_title_".$lkey]).'</index_html_title_'.$lkey.'>'
                .'<index_html_keywords_'.$lkey.'>'.turnCDDATA($this->drow["index_html_keywords_".$lkey]).'</index_html_keywords_'.$lkey.'>'
                .'<index_headscript_'.$lkey.'>'.turnCDDATA($this->drow["index_headscript_".$lkey]).'</index_headscript_'.$lkey.'>'
                .'<index_endbodyscript_'.$lkey.'>'.turnCDDATA( $this->drow["index_endbodyscript_".$lkey]).'</index_endbodyscript_'.$lkey.'>'
                .'<index_html_description_'.$lkey.'>'.turnCDDATA($this->drow["index_html_description_".$lkey]).'</index_html_description_'.$lkey.'>'
                ;
      }     
      //其他動態
      foreach ($this->keyname as $key =>$value){
        foreach($CFG->langs  as $lkey=>$lval){
        $tmparray = $this->keyarray[$value."_".$lkey];
        $xmlstring .="<".$value."_".$lkey.">";
        $xmlstring .='<html_title>'.turnCDDATA($tmparray['html_title']).'</html_title>';
        $xmlstring .='<html_keywords>'.turnCDDATA($tmparray['html_keywords']).'</html_keywords>';
        $xmlstring .='<html_description>'.turnCDDATA($tmparray['html_description']).'</html_description>';
        $xmlstring .='<headscript>'.turnCDDATA($tmparray['headscript']).'</headscript>';
        $xmlstring .='<endbodyscript>'.turnCDDATA($tmparray['endbodyscript']).'</endbodyscript>';
        $xmlstring .="</".$value."_".$lkey.">";
        }
      }

      $xmlstring .='<en_switch>'.turnCDDATA($this->en_switch).'</en_switch>';
      $xmlstring .='</content>';
      return $xmlstring;
  }

  function loadForm(){
    global $CFG;
    $this->en_switch = pgParam("en_switch",null);
    foreach($CFG->langs  as $lkey=>$lval){
    $this->drow["html_title_".$lkey] = pgParam("html_title_".$lkey,null);
    $this->drow["html_keywords_".$lkey] = pgParam("html_keywords_".$lkey,null);
    $this->drow["html_description_".$lkey] = pgParam("html_description_".$lkey,null);
    $this->drow["headscript_".$lkey] = pgParam("headscript_".$lkey,null);
    $this->drow["endbodyscript_".$lkey] = pgParam("endbodyscript_".$lkey,null);

    $this->drow["index_html_title_".$lkey] = pgParam("index_html_title_".$lkey,null);
    $this->drow["index_html_keywords_".$lkey] = pgParam("index_html_keywords_".$lkey,null);
    $this->drow["index_html_description_".$lkey] = pgParam("index_html_description_".$lkey,null);
    $this->drow["index_headscript_".$lkey] = pgParam("index_headscript_".$lkey,null);
    $this->drow["index_endbodyscript_".$lkey] = pgParam("index_endbodyscript_".$lkey,null);
    }
    foreach ($this->keyname as $key =>$value){
        foreach($CFG->langs  as $lkey=>$lval){
            $tmp1 = pgParam($value."html_title_$lkey",null);
            $tmp2 = pgParam($value."html_keywords_$lkey",null);
            $tmp3 = pgParam($value."html_description_$lkey",null);
            $tmp4 = pgParam($value."headscript_$lkey",null);
            $tmp5 = pgParam($value."endbodyscript_$lkey",null);
            $tmparray = array("html_title"=>$tmp1,"html_keywords"=>$tmp2,"html_description"=>$tmp3,"headscript"=>$tmp4,"endbodyscript"=>$tmp5);
            $this->keyarray[$value."_".$lkey] = $tmparray;
        }      
    }
    $this->xmlcontent = $this->toXML();
  }

  //讀取單筆資料
  function load(){
    global $CFG;
    $strSQLQ = "select * from ".$CFG->tbext."webconfig where id='".mysql_escape_string($this->id)."'";
    $query = mysql_query($strSQLQ);
    while ($row = mysql_fetch_array($query)) {
      $this->xmlcontent =$row['xmlcontent'];
    }
    if (isset($this->xmlcontent)){
      $this->parseXML($this->xmlcontent);
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
