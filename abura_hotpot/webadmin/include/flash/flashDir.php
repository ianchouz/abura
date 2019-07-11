<?php
  include '../../applib.php';
  function printjson($success,$message){
    $json = array(
      success => "$success",
      message => "$message"
    );
    echo json_encode($json);
    die("");
  }  
  $gdir = $_POST['gdir'];
  $pdir = $_POST['pdir'];
  $newdir= $_POST['newdir'];
  $goaction = $_POST['goaction'];
  $localDir = $CFG->root_user;
  //echo "root_web:   $CFG->root_web<br>";
  //echo "root_user_fla:  $CFG->root_user<br>";

  if (!isSet($goaction) ||  $goaction !="run"){
  	printjson(false,"非法來源");
  }
  if (!isset($newdir)){
    printjson(false,"沒有輸入新目錄");
  }
  if (!(mb_strlen($newdir,"Big5") == strlen($newdir))){
    printjson(false,"請勿使用中文");
  }

  $topDir = checkDIR($pdir,"/",true).checkDIR($gdir,"/",true);
  if(!is_dir($localDir)){
    mkdir($localDir, 0777); 
  }
  if(!is_dir($localDir.$topDir)){
    mkdir($localDir.$topDir, 0777); 
  }
  if(!is_dir($localDir.$topDir)){
    printjson(false,"目錄不存在或無法寫入$localDir$topDir");
  }else if (!is_writeable($localDir.$topDir)){
   printjson(false,"目錄無法寫入");
  }else if (is_dir($localDir.$topDir.$newdir)){
    printjson(false,"目錄已經存在");
  }
  mkdir($localDir.$topDir.$newdir, 0777); 
  printjson(true,"success");
?>