<?php
  include '../../applib.php';

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

  $dir = @$_REQUEST["gdir"];
  if (!isset($dir) || $dir=="" || $dir=="null"){
    $dir = "";
  }
  $rootdir = @$_REQUEST["rootdir"];
  if (!isset($rootdir) || $rootdir=="null" || $rootdir==""){
  	$rootdir = $CFG->root_user;
  }
  $path = $rootdir.$dir.$file_name;
  if(@move_uploaded_file($file_tmp, $path)){
    echo "上傳成功||$file_name";
  	die("");
  }else{
    echo '上傳失敗||';
    die();
  }
?>