<?php
  $openOther = "false";
  include '../../applib.php';
  include_once( '../../include/checksession.php');

  function imgtype($filename){
    $file_ext  = strtolower ( substr ( $filename , strrpos ( $filename , '.' ) + 1));
    return '.'.$file_ext;
  }

 // The Demos don't save files 

 if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
   die ("上傳時出現了錯誤 - 無法取得檔名。");
 } 

  $file_tmp = $_FILES["Filedata"]["tmp_name"];
  $file_name = $_FILES["Filedata"]['name'];
  $filetype = strtolower(imgtype($file_name));

  //中文檔名與特殊字元自動換名稱
  $file_name = filerename($file_name);
  $newfile = substr($file_name,0,strrpos($file_name,'.'));

  $dir = @$_REQUEST["gdir"];
  if (empty($dir) || $dir=="null"){
  	$dir="";
  }
  $fixwidth = @$_REQUEST["fixwidth"];
  $fixheight = @$_REQUEST["fixheight"];
  $fullDir = $CFG->root_user.checkDIR($dir,"/",true);
  if(!is_dir($fullDir)){
    mkdir($fullDir, 0777);
  }
  
  $path = $fullDir.$file_name;

  $tmpName = $newfile.'_tmp'.$filetype;
  $file_name = $newfile.$filetype;
  $path = $fullDir.$tmpName;

  if(@move_uploaded_file($file_tmp, $path)){
    chmod ($path , 0777);
    @copy($path,$fullDir.$file_name);
    @unlink($path);
    $path = $fullDir.$file_name;
    if ($fixwidth == null || $fixwidth =="" || $fixwidth =="0"){
      $fixwidth =5000;
    }else{
      $fixwidth = (int)$fixwidth;
    }
    quickReSizeIMG($fixwidth,$fixheight,$fullDir,$file_name);
  	echo "success";
  }else{
    echo '這張圖片上傳發生錯誤 - 無法複製檔案。';
    die();
  }
?>