<?php

include_once("fn_set.inc");
checkAuthority($menu_id);


$filenames = $_POST['filename'];
if(!isset($mainid) || $mainid == "") {
    die("缺少編號");
}
if(!isset($filenames) || $filenames == "") {
    die("缺少資料");
}

if(count($filenames) != 0) {
    foreach($filenames as $key => $filename) {
        $seq = formatNUM($key, 1, 5);
        if(!empty($filename)) {
            $sql = "UPDATE $table SET seq='$seq' WHERE `cateid`='$mainid' AND `filename`='$filename' ";
            @sql_query($sql);
        }
    }
}

die("");
?>