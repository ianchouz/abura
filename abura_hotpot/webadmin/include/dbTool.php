<?php
class dbTool {
    var $__db__error_message = null;
    var $__db__error_no = 0;
    function _doInsert($tb, $tarr) {
        $tb_column = "";
        $tb_value = "";
        $cc = 0;
        if(count($tarr) != 0) {
            foreach($tarr as $key => $val) {
                $tb_column .= ($cc != 0 ? "," : "") . $key;
                $tb_value .= ($cc != 0 ? "," : "") . $val;
                $cc++;
            }
        } else {
            $this->__db__error_message = "no data";
            return false;
        }
        
        if($cc != 0) {
            $sql = "insert into $tb ($tb_column) value($tb_value)";
            $result = sql_query($sql);
            if($result) {
                return true;
            } else {
                $this->__db__error_no = sql_errno();
                $this->__db__error_message = sql_errno() . " : " . sql_error() . ">>" . $sql;
                return false;
            }
        } else {
            $this->__db__error_message = "no data";
            return false;
        }
    }
    
    function _doUpdate($tb, $tarr, $keyarr) {
        $tb_column = "";
        $tb_value = "";
        $cc = 0;
        $sql = "update $tb set ";
        $apchar = "";
        if(count($tarr) != 0) {
            foreach($tarr as $key => $val) {
                $sql .= $apchar . $key . "=" . $val;
                $apchar = ",";
                $cc++;
            }
        } else {
            $this->__db__error_message = "no data";
            return false;
        }
        if(count($keyarr) != 0) {
            $cc = 0;
            $apchar = " where ";
            foreach($keyarr as $key => $val) {
                $sql .= $apchar . $key . "=" . $val;
                $apchar = " and ";
                $cc++;
            }
        } else {
            $this->__db__error_message = "no key data";
            return false;
        }
        //echo $sql."<br>";
        $result = sql_query($sql);
        $this->__db__update_row = $result;
        if($result) {
            //$this->__db__error_message = ">>".$sql;
            return true;
        } else {
            $this->__db__error_no = sql_errno();
            $this->__db__error_message = sql_errno() . " : " . sql_error() . ">>" . $sql;
            return false;
        }
    }
}

$_db = new dbTool();
?>