<?php
if(!isset($_SESSION['visitcounter']) || $_SESSION['visitcounter'] == "") {
    $yy = date("Y");
    $mm = date("m");
    $dd = date("d");
    //統計由哪邊來的
    $formurl = $_SERVER['HTTP_REFERER'];
    if(isset($formurl)) {
        $formurl = str_replace("http://", "", $formurl);
        $pos = strpos($formurl, "/");
        if($pos === false) {
            $comfrom = $formurl;
        } else {
            $urls = explode("/", $formurl);
            $comfrom = $urls[0];
        }
        $strSQLQ = "select frequency from " . $CFG->tbext . "counter_from where yy='$yy' and mm='$mm' and dd='$dd' and url='$comfrom'";
        $query = sql_query($strSQLQ);
        $rc = sql_num_rows($query);
        if($rc == 0) {
            $sql = "insert into " . $CFG->tbext . "counter_from (yy,mm,dd,url,frequency) value('$yy','$mm','$dd','$comfrom',1);";
            $query = sql_query($sql);
        } else {
            $row = sql_fetch_array($query);
            $frequency = $row[0] + 1;
            $sql = "update " . $CFG->tbext . "counter_from set frequency = $frequency where yy='$yy' and mm='$mm' and dd='$dd' and url='$comfrom'";
            $query = sql_query($sql);
        }
        //新增至資料庫中
        //統計登入次數
        $strSQLQ = "select frequency from " . $CFG->tbext . "counter_visit where yy='$yy' and mm='$mm' and dd='$dd'";
        $query = sql_query($strSQLQ);
        $rc = sql_num_rows($query);
        if($rc == 0) {
            $sql = "insert into " . $CFG->tbext . "counter_visit (yy,mm,dd,frequency) value('$yy','$mm','$dd',1);";
            $query = sql_query($sql);
        } else {
            $row = sql_fetch_array($query);
            $frequency = $row[0] + 1;
            $sql = "update " . $CFG->tbext . "counter_visit set frequency = $frequency where yy='$yy' and mm='$mm' and dd='$dd'";
            $query = sql_query($sql);
        }
        $_SESSION["visitcounter"] = date("Y-m-d H:i:s");
    }
}
?>