<?php
include_once("fn_set.inc");
checkAuthority($menu_id);

$filename = $_POST['filename'];
if(!isset($mainid) || $mainid == "") {
    die("缺少編號");
}
if(!isset($filename) || $filename == "") {
    die("缺少資料");
}

$sql = "UPDATE $table SET `fileMemo`='" . $_POST['fileMemo'] . "' WHERE `cateid`='$mainid' AND `filename`='$filename' ";
echo $sql;
@sql_query($sql);
die("");
?>