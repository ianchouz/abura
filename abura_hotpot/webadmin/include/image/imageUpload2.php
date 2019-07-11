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

  $dir = @$_REQUEST["gdir"];
  if (empty($dir) || $dir=="null"){
  	$dir="";
  }

  $rootdir = @$_REQUEST["rootdir"];
  if (empty($rootdir) || $rootdir=="null"){
  	$rootdir = $CFG->root_web;
  }
  $fixwidth = @$_REQUEST["fixwidth"];
  $fixheight = @$_REQUEST["fixheight"];
  $baseRoot = $rootdir.checkDIR($dir,"/",true);

  if(!is_dir($baseRoot)){
    mkdir($baseRoot, 0777);
  }else{
    chmod($baseRoot, 0777);
  }
  
  $path = $baseRoot.$file_name;

  if(@move_uploaded_file($file_tmp, $path)){
    //進行圖片縮放
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($path);

    if ($fixwidth == null || $fixwidth =="" || $fixwidth =="0"){
      $fixwidth =2048;
    }else{
      $fixwidth = intval($fixwidth);
    }
    if ($fixheight == null || $fixheight =="" || $fixheight =="0" || $fixheight ==0){
      $fixheight =2048;
    }else{
      $fixheight = intval($fixheight);
    }
    //要縮圖
    if( (( $img_width != 0 ) || ( $img_width != 0 )) && ($img_width > $fixwidth) || ($img_height > $fixheight)){
      $imgresize = new resize_img();
      $imgresize->sizelimit_x = $fixwidth;
      $imgresize->sizelimit_y = $fixheight;

      if($imgresize->resize_image($path) === false ){
        echo '上傳檔案發生錯誤，無法建立縮圖';
      }
      else{
        $imgresize->save_resizedimage($baseRoot,$file_name);
      }
      $imgresize->destroy_resizedimage();
    }
    //列出大圖的尺寸
    list($img_lwidth, $img_lheight, $img_ltype, $img_lattr) = @getimagesize($path);
    $filesize = floor(filesize($path)/1024);
    $filedesc = "檔名:$file_name,像素: $img_lwidth px * $img_lheight px,大小: $filesize KB";
    //產生其他訊息,filedesc,width,height
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($path);
    echo "上傳成功||$file_name||$filedesc||$img_width||$img_height";
  }else{
    echo '上傳失敗||';
    die();
  }
?>