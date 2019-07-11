<?php
include_once("include/config.php");
require 'include/checksession.php';
function checkAuthority($menuid, $isDie = true) {
    global $CFG;
    //echo "menuid:$menuid<br>";
    if(empty($menuid)) {
        if($isDie) {
            die("您無權限觀察此頁面!!");
        }
        return false;
    }
    $authority = $_SESSION['authority'];
    //echo "authority:$authority<br>";
    if($authority == "all") {
        return true;
    }
    if($authority == null && $authority != "all") {
        if($isDie) {
            die("您無權限觀察此頁面!!");
        }
        return false;
    }
    
    $sql = "select id,uplevel from " . $CFG->tbext . "webmenu where inuse=true and keyname ='" . $menuid . "'";
    $result = sql_query($sql);
    $row = sql_fetch_array($result);
    $id = $row["id"];
    //echo "$authority:".$row["uplevel"]."<br>";
    $pos = strpos($authority, "'" . $id . "'");
    $pos2 = strpos($authority, ";" . $id . ";");
    $pos3 = strpos($authority, "'" . $row["uplevel"] . "'");
    $pos4 = strpos($authority, ";" . $row["uplevel"] . ";");
    
    if($pos === false && $pos2 === false) {
        if($isDie) {
            die("您無權限觀察此頁面!!");
        }
        return false;
    }
    return true;

}
?>