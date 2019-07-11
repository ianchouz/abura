<?php
class QueryParames {
    //page
    var $rowsperpage;
    var $currentpage;
    function __construct() {
        
    }
    
    var $_names = array();
    var $_vals;
    function val($key) {
        return $this->_vals[$key];
    }
    
    function setval($key, $val) {
        $this->_vals[$key] = $val;
    }
    
    function load() {
        if(count($this->_names) != 0) {
            foreach($this->_names as $idx => $key) {
                $this->_vals[$key] = pgParam($key, "");
            }
        }
        $this->rowsperpage = pgParam("rowsperpage", "50");
        $this->currentpage = pgParam("currentpage", "1");
    }
    
    function buildColumn() {
        foreach($this->_names as $idx => $key) {
            $str .= "<input type='hidden' name='$key' value='" . $this->_vals[$key] . "'/>";
        }
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
?>