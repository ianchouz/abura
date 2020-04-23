<?include_once("../../applib.php");?>
<?include_once("thumb_tool.php");?>
<?
  //從JS收到的圖片參數
  $path = $_REQUEST["path"];
  $rootpath = pgParam("rootpath",$CFG->root_user);
  $webpath = pgParam("webpath",$CFG->web_user);

  $back = $_REQUEST["back"];
  $prefix = $_REQUEST["pre"];
  $nw = $_REQUEST["nw"];
  $nh = $_REQUEST["nh"];
  if($nw=='100%') $nw = '1500';
  $noneWidth = $_REQUEST["noneWidth"];
  $noneHeight = $_REQUEST["noneHeight"];
  if (isset($noneWidth) && $noneWidth !=''){
    //$noneWidth = 'Y';
  }else{
    $noneWidth = '';
  }
  if (isset($noneHeight) && $noneHeight !=''){
    //$noneHeight = 'Y';
  }else{
    $noneHeight = '';
  }


    //以下參數設定
    $upload_dir   = $path ;                             //存放上傳圖片的目錄
    $upload_path  = $rootpath.$upload_dir ;       //存放裁切後的圖像
    $lastchar = $upload_path{strlen($upload_path)-1};
    if ( $lastchar !="/"){
      $upload_path.="/";
    }
    $upload_path_web  = $webpath.$upload_dir ;       //存放裁切後的圖像
    $lastchar = $upload_path_web{strlen($upload_path_web)-1};
    if ( $lastchar !="/"){
      $upload_path_web.="/";
    }

    $max_file  = 3 ;              //上傳圖片大小，這裡默認3M
    $max_width  = 50000 ;             //上傳圖片的最大寬度
    $max_height = 50000;
    $thumb_width  = $nw ;             //裁切後小圖的初始寬度
    $thumb_height  = $nh ;            //裁切後小圖的初始高度

    $upload_is = false;
    $need_thumb = false;
    $thumb_is = false;

    if (! is_dir ( $upload_path )){
      mkdir ( $upload_path , 0777);
      chmod ( $upload_path , 0777);
    }

    //上傳檔案
    if  (isset( $_POST [ "upload" ])) {
      $upload_is = true;
      $userfile_name  = $_FILES [ 'image' ][ 'name' ];
      $userfile_tmp  = $_FILES [ 'image' ][ 'tmp_name' ];
      $userfile_size  = $_FILES [ 'image' ][ 'size' ];
      $userfile_type  = $_FILES [ 'image' ][ 'type' ];
      $filename  = basename ( $_FILES [ 'image' ][ 'name' ]);
      $file_ext  = strtolower ( substr ( $filename , strrpos ( $filename , '.' ) + 1));
      if ((! empty ( $_FILES [ "image" ])) && ( $_FILES [ 'image' ][ 'error' ] == 0)) {
        foreach  ( $allowed_image_types  as  $mime_type  => $ext ) {
          if ( $file_ext == $ext  && $userfile_type == $mime_type ){
            $error  = "" ;
            break ;
          } else {
            $error  = "此格式<strong>" . $file_ext .'('.$userfile_type.')'. "</strong>不允許上傳。<br />" ;
          }
        }
        if  ( $userfile_size  > ( $max_file *1048576)) {
          $error .= "圖片不要超過" . $max_file . "MB" ;
        }
      } else {
        $error = "請先選擇圖片!!" ;
      }
      if  ( strlen ( $error )==0){
        if  (isset( $_FILES [ 'image' ][ 'name' ])){
          $file_name = $_FILES [ 'image' ][ 'name' ];
		  //禁止特殊符號檔名
		  $chars = array('(',')',' ','\'','"','$','%','&','<','>','=',';','?','/','<!--','-->','%20','%22','%3c','%253c','%3e','%0e','%28','%29','%2528','%26','%24','%3f','%3b','%3d');
		  $file_name = str_replace($chars, '_', $file_name);
          if (!(mb_strlen($file_name,"Big5") == strlen($file_name))){
            $file_name = strtotime ( date ( 'Ymd H:i:s' )).".".$file_ext;
          }
          $newfile = substr($file_name,0,strrpos($file_name,'.'));
          $tmpName = $newfile.'_tmp'.'.'.$file_ext;
          $file_name = $newfile.'.'.$file_ext;
          $large_image_location  = $upload_path.$file_name;
          $upload_file = $upload_path.$tmpName;

          @move_uploaded_file( $userfile_tmp , $upload_file );
          @chmod ($upload_file , 0777);
          @copy($upload_file,$large_image_location);
          @unlink($upload_file);

          $width  = getWidth( $large_image_location );
          $height  = getHeight( $large_image_location );
          $scale  = 1;
          if  ($width > $max_width && $height > $max_height){
            resizeImage( $large_image_location , $width , $height , $max_width , $max_height);
          }
          //判斷是否有上傳檔案成功
          if (is_file($large_image_location)){
            if ($nw !='' || $nh !=''){
              if  ($width > $nw || $height > $nh){
                header("location:thumb_image.php?rootpath=$rootpath&webpath=$webpath&path=$path&pre=$prefix&nw=$nw&nh=$nh&file_name=$file_name&noneWidth=$noneWidth&noneHeight=$noneHeight");
                exit();
              }
            }
          }else{
            $error="上傳檔案失敗!!";
          }
        }else{
          $error = "上傳檔案失敗!!";
        }
      }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?></title>
<?require('../../base_header_lib.php');?>
<script type="text/javascript">
function do_close(){
  if(typeof parent.loadimages == 'function') {
    parent.loadimages();
  }else{
    parent.$.fancybox.close();
  }
}
  var par = window.opener;
  $(document).ready(function(){
    <?if ($back=='N'){
      }else{?>
    var par = window.opener;
    if (par){
      //alert("<?=$path?>");
    }else{
      alert("找不到父視窗!!");
      window.close();
    }
    <?}?>
  });
  function setImg(file_name,upload_path_web,width,height){
    var fixwidth = par.$('#<?=$prefix?>imagearea').attr("data-w");
    var fixheight = par.$('#<?=$prefix?>imagearea').attr("data-h");
    var scalex = 1;
    var scaley = 1;
    if (width - fixwidth >0 && ((width-fixwidth) > (height-fixheight))) {
      scalex = ( fixwidth / width );
      scaley = scalex;
    }else if (height-fixheight >0){
      scalex = ( fixheight / height );
      scaley = scalex;
    }
    var thumbfwidth = width * scalex;
    var thumbfheight = height * scaley;

    var thumbfleft = (Math.round((fixwidth - thumbfwidth) / 2)) + "px";
    var thumbftop = (Math.round((fixheight - thumbfheight) / 2)) + "px";
    var thumbfwidth = Math.round(thumbfwidth) + "px";
    var thumbfheight = Math.round(thumbfheight) + "px";
    if (par && "<?=$prefix?>" !=""){
      var hh = '<img src="' + upload_path_web + file_name + '" style="top:'+thumbftop+'; left:'+thumbfleft+'; width:'+thumbfwidth+'; height:'+thumbfheight+';">';
      par.$('#<?=$prefix?>imagearea').html(hh);
      par.$('#<?=$prefix?>path').val("<?=$path?>" + file_name);
      par.$('#<?=$prefix?>').val(file_name);
      par.$('#<?=$prefix?>owidth').text(width);
      par.$('#<?=$prefix?>oheight').text(height);
      par.$('#<?=$prefix?>opath').text(file_name);
      window.close();
      par.focus();
    }else{
      window.close();
    }
  }
</script>
<?php
if ($need_thumb){
  $current_large_image_width  = getWidth( $large_image_location );
  $current_large_image_height  = getHeight( $large_image_location );?>
<script type= "text/javascript" >
function  preview(img, selection) {
  var  scaleX = <?php echo  $thumb_width ;?> / selection.width;
  var  scaleY = <?php echo  $thumb_height ;?> / selection.height;
  $( '#thumbnail + div > img' ).css({
    width: Math. round (scaleX * <?php echo  $current_large_image_width ;?>) + 'px' ,
    height: Math. round (scaleY * <?php echo  $current_large_image_height ;?>) + 'px' ,
    marginLeft: '-'  + Math. round (scaleX * selection.x1) + 'px' ,
    marginTop: '-'  + Math. round (scaleY * selection.y1) + 'px'
  });
  $( '#x1' ).val(selection.x1);
  $( '#y1' ).val(selection.y1);
  $( '#x2' ).val(selection.x2);
  $( '#y2' ).val(selection.y2);
  $( '#w' ).val(selection.width);
  $( '#h' ).val(selection.height);
}
$(document).ready( function  () {
  $( '#save_thumb' ).click( function () {
    var  x1 = $( '#x1' ).val();
    var  y1 = $( '#y1' ).val();
    var  x2 = $( '#x2' ).val();
    var  y2 = $( '#y2' ).val();
    var  w = $( '#w' ).val();
    var  h = $( '#h' ).val();
    if (x1== ""  || y1== ""  || x2== ""  || y2== ""  || w== ""  || h== "" ){
      alert( "請先選擇上傳圖片" );
      return  false;
    } else {
      return  true;
    }
  });
});

$(window).load( function  () {
  $( '#thumbnail' ).imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>' , onSelectChange: preview });
});
<?php }?>


</script>
<style>
html{
  margin: 0;
  padding: 0;
  width:100%;
  overflow:auto;
}
body{
  margin: 0;
  padding: 0;
  font-family: 微軟正黑體,arial,verdana,helvetica,tahoma,Sans-serif;
  font-size:12px;
}
table{
  border:0px;border-collapse:collapse;
}
</style>
</head>
<body>
<div style="background:#F1EFE2;border:1px solid #D4D0C8;padding:5px;">
  圖片上傳
  <?if ($noneWidth!='' || $nw !='' || $noneHeight!='' || $nh !=''){?>
  <div>圖片尺寸：<?
    if ($noneWidth=='Y'){?> 最寬 <?}?> <?=$nw?> px 
    * <?if ($noneHeight=='Y'){?> 最高 <?}?> <?=$nh?> px，圖片格式：jpg、gif、png。</div>
  <?}?>
</div>
<div style="border:1px solid #D4D0C8;padding:5px;">
<?php
if ( strlen ($error)>0){
  echo  "<ul><li><strong>錯誤!</strong></li><li>" . $error . "</li></ul>" ;
}else if ($upload_is && is_file($large_image_location)){
  $thumbwidth =  getWidth($large_image_location);
  $thumbheight =  getHeight($large_image_location);
  if ($back=='N'){
    ?><script>alert("上傳檔案完畢!! 可繼續上傳或關閉視窗。");</script><?
  }else{
?>
<script>setImg('<?=$file_name?>','<?=$upload_path_web?>',<?=$thumbwidth?>,<?=$thumbheight?>);</script>
<?
  }
}
?>
  <form name= "photo"  enctype= "multipart/form-data"  action= "<?php echo $_SERVER[" PHP_SELF "];?>"  method= "post" >
    <input type="hidden" name="path" value="<?=$path?>">
    <input type="hidden" name="pre" value="<?=$prefix?>">
    <input type="hidden" name="nw" value="<?=$nw?>">
    <input type="hidden" name="nh" value="<?=$nh?>">
    <input type= "file"  name= "image"  size= "30"  /> <input type= "submit"  name= "upload"  value= "上傳"  />
    <?if ($back=='N'){
      ?><input type= "button"  name= "doclose"  value= "關閉視窗" onclick="do_close()" /><?
    }?>
  </form>
</div>
</body>
</html>