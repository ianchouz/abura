<?php
  $openOther = "false";
  include '../../applib.php';

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

  $pos = strpos ($file_name, ".swf");
  if ($pos === false) {
    $dir = @$_REQUEST["gdir"];
    if (empty($dir) || $dir=="null"){
    	$dir="";
    }
    $fixwidth = @$_REQUEST["fixwidth"];
    $fixheight = @$_REQUEST["fixheight"];
    
    if(!is_dir($CFG->root_user.checkDIR($dir,"/",true))){
      mkdir($CFG->root_user.checkDIR($dir,"/",true), 0644);
    }
    
    $path = $CFG->root_user.checkDIR($dir,"/",true).$file_name;
    
    if(@move_uploaded_file($file_tmp, $path)){
      //進行圖片縮放
      list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($path);
    
      if ($fixwidth == null || $fixwidth =="" || $fixwidth =="0"){
        $fixwidth =5000;
      }else{
        $fixwidth = intval($fixwidth);
      }
      if ($fixheight == null || $fixheight =="" || $fixheight =="0" || $fixheight ==0){
        $fixheight =5000;
      }else{
        $fixheight = intval($fixheight);
      }
      if( (( $img_width != 0 ) || ( $img_width != 0 )) && ($img_width > $fixwidth) || ($img_height > $fixheight)){
        $imgresize = new resize_img();
        $imgresize->sizelimit_x = $fixwidth;
        $imgresize->sizelimit_y = $fixheight;
    
        if($imgresize->resize_image($path) === false ){
          echo '上傳檔案發生錯誤，無法建立縮圖';
        }
        else{
          $imgresize->save_resizedimage($upload_dir,$newfile);
        }
        $imgresize->destroy_resizedimage();
      }
    	chmod($path, 0777);
    	echo "success";
    }else{
      echo '這張圖片上傳發生錯誤 - 無法複製檔案。';
      die();
    }
  }else{
	$dir = $_REQUEST["gdir"];
    $ccdir = $CFG->root_user.checkDIR($dir,"/",true);
    $path = $ccdir.$file_name;
    if(!is_dir($CFG->root_user)){
      mkdir($CFG->root_user, 0644);
    }
    if(!is_dir($ccdir)){
      mkdir($ccdir, 0644);
    }
    
    //檢查要寫入的目錄是否存在
    if(!is_dir($ccdir)){
      die("目錄不存在或無法寫入");
    }else if (!is_writeable($ccdir)){
      die("目錄無法寫入");
    }
    
    if (move_uploaded_file($file_tmp,$path)) {
      chmod($path, 0644);
      echo "success";
    }
  }
?>