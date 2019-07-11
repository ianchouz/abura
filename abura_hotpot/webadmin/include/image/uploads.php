<?php
  include '../../applib.php';
include_once( '../../include/checksession.php');
  function imgtype($filename){
    $file_ext  = strtolower ( substr ( $filename , strrpos ( $filename , '.' ) + 1));
    return '.'.$file_ext;
  }

  if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
    echo '上傳失敗||';
    die("");
  }

  $file_tmp = $_FILES["Filedata"]["tmp_name"];
  $file_name = $_FILES["Filedata"]['name'];
  $filetype = strtolower(imgtype($file_name));

  //中文檔名與特殊字元自動換名稱
  $file_name = filerename($file_name);
  $newfile = substr($file_name,0,strrpos($file_name,'.'));

  $dir = @$_REQUEST["gdir"];
  if (!isset($dir) || $dir=="" || $dir=="null"){
    $dir = "";
  }
  $fixwidth  = @$_REQUEST["fixwidth"];
  $fixheight = @$_REQUEST["fixheight"];
  $rootdir = @$_REQUEST["rootdir"];
  if (!isset($rootdir) || $rootdir=="null" || $rootdir==""){
  	$rootdir = $CFG->root_user;
  }
  $path = $rootdir.$dir.$file_name;
  $fullDir = $rootdir.$dir;

  $tmpName = $newfile.'_tmp'.$filetype;
  $file_name = $newfile.$filetype;
  $upload_file = $fullDir.$tmpName;
 if(move_uploaded_file($file_tmp, $upload_file)){
    chmod ($upload_file , 0777);
    @copy($upload_file,$fullDir.$file_name);
    @unlink($upload_file);
    quickReSizeIMG(5000,5000,$fullDir,$file_name);
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($fullDir.$file_name);
    $filedesc = "檔名:$file_name,像素: $img_lwidth px * $img_lheight px,大小: $filesize KB";
    echo "上傳成功||$file_name||$filedesc||$img_width||$img_height";
  	die("");
  }else{
    echo '上傳失敗'.$upload_file.'||';
    die();
  }
?>