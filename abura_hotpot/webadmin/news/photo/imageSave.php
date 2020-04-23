<?php
include_once("fn_set.inc");
checkAuthority($menu_id);

$mainid = $_POST['mainid'];
if($mainid == "") {
    die("缺少編號");
}

$sql = "delete from " . $table . " WHERE mainid = '$mainid'";
@sql_query($sql);

if(count($_POST['filename']) != 0) {
    $arr_seq = $_POST['seqs'];
    $arr_fileMemo = $_POST['fileMemo'];
    $last_seq = '';
    foreach($_POST['filename'] as $key => $filename) {
        $seq = $arr_seq[$key];
        if($seq == '') {
            $seq = $last_seq;
            $seq = formatNUM($seq, 1, 5);
        }
        $fileMemo = $arr_fileMemo[$key];
        if(!empty($filename)) {
            quickReSizeIMG($s_width, $s_width, $fullsDir, $filename, $filename);
            $sql = "insert into " . $table . " (mainid,filename,seq,fileMemo) value('$mainid','$filename','$seq','$fileMemo')";
            @sql_query($sql);
        }
        $last_seq = $seq;
    }
}
die("");
?>