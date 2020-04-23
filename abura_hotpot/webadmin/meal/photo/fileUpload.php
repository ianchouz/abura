<?php
include_once("fn_set.inc");
checkAuthority($menu_id);
//    print_r($_POST);
//    print_r($_FILES);


if($mainid == "") {
    jsonDie("fail", "loss main id");
}
if($typid == "") {
    jsonDie("fail", "loss type id");
}

if(!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    jsonDie("fail", "上傳時出現了錯誤 - 無法取得檔名。");
}

$file_tmp = $_FILES["Filedata"]["tmp_name"];
$file_name = $_FILES["Filedata"]['name'];
$filetype = strtolower(imgtype($file_name));

//中文檔名與特殊字元自動換名稱
$file_name = filerename($file_name);
$newfile = substr($file_name, 0, strrpos($file_name, '.'));

//型錄的ID
$dir = @$_REQUEST["mainid"];
if($dir == '') {
    unlink($_FILES["Filedata"]["tmp_name"]);
    jsonDie("fail", "無法取得上傳目錄$dir");
}

$tmpName = $newfile . '_tmp' . $filetype;
$file_name = $newfile . $filetype;
$upload_file = $fullDir . $tmpName;

////重複檔案判斷 ??
//$is_repeat = sql2var("select filename FROM ".$CFG->tbext."banner1 WHERE mainid='$mainid' AND filename='$file_name' ");
//if(!empty($is_repeat)){
//    jsonDie ("fail", "重複的檔案");
//}

//已上傳數量
$total = sql2var("select count(filename) AS total from $table where cateid='" . sql_real_escape_string($mainid) . "' and typid='$typid'");
if($total > $uSet->maxFiles - 1) {
    jsonDie("fail", "已超過數量上限");
}

if(@move_uploaded_file($file_tmp, $upload_file)) {
    
    @copy($upload_file, $fullDir . $file_name);
    chmod($fullDir.$file_name, 0777);
    @unlink($upload_file);
    quickReSizeIMG($m_width, $m_height, $fullDir, $file_name);
    $upload_file = $fullDir . $file_name;
    //複製到小圖
    if($uSet->use_cut_s) {
        @copy($upload_file, $fullsDir . $file_name);
        chmod ($fullsDir.$file_name , 0777);
        quickReSizeIMG($s_width, $s_height, $fullsDir, $file_name);
    }
    //列出大圖的尺寸
    list($img_width, $img_height, $img_ltype, $img_lattr) = @getimagesize($upload_file);
    $filesize = floor(filesize($upload_file) / 1024);
    $filedesc = "檔名:$file_name,像素: $img_lwidth px * $img_lheight px,大小: $filesize KB";
    //產生其他訊息,filedesc,width,height
    // list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($fullsDir . $file_name);
    
    
   /* //縮圖處理
    if($uSet->use_cut_s) {
        quickReSizeIMG($s_width, $s_height, $fullsDir, $file_name, $file_name);
    }
    */
    //計算排序
    $qry = sql_query("select * FROM $table WHERE mainid='$mainid' ORDER BY `seq` DESC ");
    $row = sql_fetch_assoc($qry);
    $seq = $row['seq'];
    $seq = formatNUM($seq, 1, 5);
    
    //寫入資料庫
    $sql = "insert into $table (cateid,typid,filename,seq,fileMemo) value('$mainid','$typid','$file_name','$seq','')";
    @sql_query($sql);
    
    $args = array(
        'seq' => $seq,
        'width' => $img_width,
        'height' => $img_height,
        'file_name' => $file_name
    );
    jsonDie("success", "上傳成功||$file_name||$filedesc||$img_width||$img_height", $args);
} else {
    jsonDie("fail", "上傳失敗");
}

//----------------------------
// Extend Function
//----------------------------
function imgtype($filename){
    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    return '.' . $file_ext;
}
function chkDIR($dd){
    if(!is_dir($dd)) {
        mkdir($dd, 0777);
    } else if(!is_writeable($dd)) {
        chmod($dd, 0777);
    }
}
function jsonDie($status, $message, $args = false){
    $array = array(
        "status" => $status,
        "message" => $message
    );
    if(!empty($args))
        $array = $array + $args;
    die(json_encode($array));
}
?>