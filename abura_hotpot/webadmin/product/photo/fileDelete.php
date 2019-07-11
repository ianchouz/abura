<?php
include_once("fn_set.inc");
checkAuthority($menu_id);

$filename = $_POST['filename'];
if(!isset($mainid) || $mainid == "") {
    die("缺少編號");
}
if(!isset($filename) || $filename == "") {
    die("缺少檔名");
}

if(!isset($filename) || $filename == "") {
    die("沒有刪除的檔名");
}
$fullPath = $fullDir . $filename;
if(!is_dir($fullPath)) {
    @unlink($fullPath);
}
if($incSet[$typid]["crops"]) {
    $fullsPath = $fullsDir . $filename;
    if(!is_dir($fullsPath)) {
        @unlink($fullsPath);
    }
}

//刪除DB資料
if($mainid != "" && $filename != "") {
    $sql = "delete from $table WHERE cateid = '$mainid' and filename='$filename'";
    
    @sql_query($sql);
}
die(true);
?>