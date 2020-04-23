<?php
//模擬繞過checksession.php
date_default_timezone_set('Asia/Taipei');
session_start();
$_SESSION['sess_uid'] = $_SESSION['sess_name'] = $_SESSION['logintime'] = date('Y-m-d H:i:s');

include_once("fn_set.inc");
function imgtype($filename)
{
    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    return '.' . $file_ext;
}

function chkDIR($dd)
{
    if(!is_dir($dd)) {
        mkdir($dd, 0777);
    } else if(!is_writeable($dd)) {
        chmod($dd, 0777);
    }
}

if(!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    die("上傳時出現了錯誤 - 無法取得檔名。");
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
    die("無法取得上傳目錄$dir||");
}

$tmpName = $newfile . '_tmp' . $filetype;
$file_name = $newfile . $filetype;
$upload_file = $fullDir . $tmpName;

if(@move_uploaded_file($file_tmp, $upload_file)) {
    chmod($upload_file, 0777);
    @copy($upload_file, $fullDir . $file_name);
    @unlink($upload_file);
    quickReSizeIMG(2048, 2048, $fullDir, $file_name);
    $upload_file = $fullDir . $file_name;
    //複製到小圖
    @copy($upload_file, $fullsDir . $file_name);
    quickReSizeIMG(2048, 2048, $fullsDir, $file_name);
    //列出大圖的尺寸
    list($img_lwidth, $img_lheight, $img_ltype, $img_lattr) = @getimagesize($upload_file);
    $filesize = floor(filesize($upload_file) / 1024);
    $filedesc = "檔名:$file_name,像素: $img_lwidth px * $img_lheight px,大小: $filesize KB";
    //產生其他訊息,filedesc,width,height
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($fullsDir . $file_name);
    echo "上傳成功||$file_name||$filedesc||$img_width||$img_height";
} else {
    echo '上傳失敗||';
    die();
}
?>