<?php
$menu_id = "contact_us";
class contact_us {
    public $action_message = "";
    public $action_error_debug = "";
    public $dbrow = null;
    ////查詢條件區域
    public $qVO = null;

    function setQueryDefault() {
        $this->qVO = new QueryParames();
        $this->qVO->_names = array(
            "qkeyvalue",
            "qread_status",
            "qcreatedate"
        );
        $this->qVO->load();
    }

    function getQueryResult() {
        global $CFG;
        global $pagetool;
        $this->setQueryDefault();
        $sql = "SELECT * FROM " . $CFG->tbext . "contact_us where 1 ";
        $qread_status = $this->qVO->val("qread_status");
        if(!empty($qread_status) && $qread_status != "all") {
            if($qread_status == "Y") {
                $sql .= " and read_status = 'Y'";
            } else {
                $sql .= " and (read_status is null or read_status <> 'Y')";
            }
        }
        $qkeyvalue = $this->qVO->val("qkeyvalue");
        if($qkeyvalue != "") {
            $sql .= " and (";
            $sql .= " xmlcontent like '%<mail><![CDATA[%" . $qkeyvalue . "%]]></mail>%'";

            $sql .= " or xmlcontent like '%<name><![CDATA[%" . $qkeyvalue . "%]]></name>%'";
            $sql .= ")";
        }
        $qcreatedate = $this->qVO->val("qcreatedate");

        if($qcreatedate != "") {
            $sql .= " and DATE_FORMAT(create_time,'%Y-%m-%d') =" . pSQLStr($qcreatedate) ;
        }

        $sql .= " order by create_time desc";
        //echo "$sql<br>";
        return $pagetool->excute($sql);
    }

    public $id = "";
    public $subject = "";
    public $content = "";
    public $xmlcontent = "";

    public $create_time = "";
    public $read_status = "";
    public $read_status_time = "";
    public $reply_status = "";
    public $reply_status_time = "";
    public $reply_content = "";
    public $reply_id = "";

    //--xml裡面的定義
    var $_names = array(
      'mail_title',
      'products',
      'company',
      'phone',
      'name',
      'fax',
      'unit',
      'mail',
      // 'subject',
      'memo',
    );
    var $_vals;
    //--end

    function val($key) {
        return $this->_vals[$key];
    }

    function setval($key, $val) {
        $this->_vals[$key] = $val;
    }

    function __construct() {

    }

    function readXML() {
        if($this->xmlcontent != "") {
            $xmlvo = new parseXML($this->xmlcontent);
            foreach($this->_names as $idx => $key) {
                $this->_vals[$key] = $xmlvo->value("/content/$key");
            }
        }
    }

    function toXML() {
        $xmlstring .= '<content>';
        foreach($this->_names as $idx => $key) {
            $xmlstring .= "<$key>" . turnCDDATA($this->_vals[$key]) . "</$key>";
        }
        $xmlstring .= '</content>';
        $this->xmlcontent = $xmlstring;
    }

    //讀取單筆資料
    function load() {
        global $CFG;
        $id = pgParam("id", null);
        if($id != null) {
            $this->id = $id;
            $strSQLQ = "select * from " . $CFG->tbext . "contact_us where id='" . mysql_escape_string($this->id) . "'";
            //echo $strSQLQ.'<br>';
            $query = sql_query($strSQLQ);
            $row = sql_fetch_array($query);
            $this->setItem($row);
        }
    }

    function setItem($row) {
        $this->id = $row['id'];
        $this->lang = $row['lang'];
        $this->subject = $row['subject'];
        $this->content = $row['content'];
        $this->xmlcontent = $row['xmlcontent'];
        $this->create_time = $row['create_time'];
        $this->read_status = $row['read_status'];
        $this->read_status_time = $row['read_status_time'];
        $this->reply_status = $row['reply_status'];
        $this->reply_status_time = $row['reply_status_time'];
        $this->reply_content = $row['reply_content'];
        $this->reply_id = $row['reply_id'];
        $this->readXML();
    }

    //取得表單輸入的資料
    function loadForm() {
        $this->subject = pgParam("subject", null);
        $this->content = pgParam("content", null);
        foreach($this->_names as $idx => $key) {
            $this->_vals[$key] = pgParam("$key", null);
        }
        $this->toXML();
    }

    function updateRead_status() {
        global $CFG;
        $this->id = pgParam("id", null);
        $this->read_status = pgParam("read_status", null);
        $this->read_status_time = date("Y-m-d H:i:s");

        $strSQL = "UPDATE " . $CFG->tbext . "contact_us SET " . " read_status='Y'" . ",read_status_time=" . pSQLStr($this->read_status_time) . " WHERE id=" . pSQLStr($this->id);
        //echo "<br>$strSQL<br>";
        $result = sql_query($strSQL);
        //echo "<br>".sql_error();
        if($result) {
            $this->action_message = "true";
            $this->id = mysql_insert_id();
        } else {
            $this->action_message = "修改失敗";
            $this->action_error_debug = $strSQL . "<br>" . sql_error();
        }
    }

    function insert() {
        global $CFG;
        $this->loadForm();
        $this->create_time = date("Y-m-d H:i:s");
        $strSQL = "INSERT INTO " . $CFG->tbext . "contact_us(" . "subject,content,xmlcontent,create_time) VALUES (" . pSQLStr($this->mail_title) . "," . pSQLStr($this->memo) . "," . pSQLStr($this->xmlcontent) . "," . pSQLStr($this->create_time) . "" . ");";
       //echo $strSQL;
        $result = sql_query($strSQL);
        if($result) {
            $this->id = mysql_insert_id();
            $this->action_message = "true";
        } else {
            $this->action_message = "新增失敗" . sql_error();
            $this->action_error_debug = $strSQL . "<br>" . sql_error();
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
            $sql = "delete from " . $CFG->tbext . "contact_us where id in ($idvals)";
            $result = sql_query($sql);
            //$sql="delete from ".$CFG->tbext."contact_us where id in ($idvals)";
            //$result = sql_query($sql);
            $this->action_message = "總共刪除: $cc 筆！";
            $this->action_error_debug = $sql . "<br>" . sql_error();
        }
    }

    function getHtml($id) {
        global $CFG;
        $sql = "select * from " . $CFG->tbext . "contact_us where id='$id'";
        $query = @sql_query($sql);
        $queryItem = @sql_fetch_array($query);
        $xmlvo = new parseXML($queryItem['xmlcontent']);

        foreach($this->_names as $idx => $key) {
            $var->$key = $xmlvo->value("/content/$key");
        }
        $var->create_time = $queryItem["create_time"];
        // $var->subject = $queryItem["subject"];
        $var->content = nl2br($queryItem["content"]);
        $emailbody = read_template($CFG->root_web . "webadmin/contact_us/form_data.html", $var);
        return $emailbody;
    }
}
?>
