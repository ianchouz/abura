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
  $filename = $_POST['filename'];
  $goaction = $_POST['goaction'];
  $localDir = $CFG->root_user;

  if (!isSet($goaction) ||  $goaction !="run"){
  	printjson(false,"非法來源");
  }
  if (!isset($filename) || $filename==""){
    printjson(false,"沒有刪除的檔名");
  }
  $fulldir = $localDir.checkDIR($pdir,"/",true).checkDIR($gdir,"/",true);
  if (!is_dir($fulldir.$filename)){
    unlink($fulldir.$filename);
  }else{
    deleteDirectory($fulldir.$filename);
  }
  printjson(true,"");
?>