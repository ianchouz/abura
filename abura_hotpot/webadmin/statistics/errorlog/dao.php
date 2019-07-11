<?php
/*******************************
 *user_errorlog
 ********************************/
include("../../applib.php");
$menu_id = "errorlog";
class QueryParames {
    public $logintime;
    public $userid;
    
    //page
    public $rowsperpage;
    public $currentpage;
    
    function __construct() {
        $this->logintime = pgParam("logintime", "");
        $this->userid = pgParam("userid", "");
        $this->actipaddress = pgParam("actipaddress", "");
        
        $this->rowsperpage = pgParam("rowsperpage", "50");
        $this->currentpage = pgParam("currentpage", "1");
    }
    function buildColumn() {
        $str = "<input type='hidden' id='logintime' name='logintime' value='" . $this->logintime . "'/>";
        $str .= "<input type='hidden' id='userid' name='userid' value='" . $this->userid . "'/>";
        $str .= "<input type='hidden' id='rowsperpage' name='rowsperpage' value='" . $this->rowsperpage . "'/>";
        $str .= "<input type='hidden' id='currentpage' name='currentpage' value='" . $this->currentpage . "'/>";
        return $str;
    }
    function bulidFrom($formname, $url) {
        $str = "<form name='" . $formname . "' id='" . $formname . "' method='post' action='" . $url . "'>";
        $str .= $this->buildColumn();
        $str .= "</form>";
        return $str;
    }
}

class user_errorlog {
    public $action_message = "";
    public $action_error_debug = "";
    
    function __construct() {
        
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
            $sql = "delete from " . $CFG->tbext . "user_errorlog where id in ($idvals)";
            $result = sql_query($sql);
            
            $this->action_message = "總共刪除: $cc 筆！";
            $this->action_error_debug = $sql . "<br>" . sql_error();
        }
    }
}
?>