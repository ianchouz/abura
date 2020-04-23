<?include_once("../../applib.php");?>
<?include_once("thumb_tool.php");?>
<?
  //要設定的圖片參數
  $path = $_REQUEST["path"];
  $nw = $_REQUEST["nw"];
  $nh = $_REQUEST["nh"];
  $file_name = $_REQUEST["file_name"];
  $fixroot = $_REQUEST["fixroot"];
  if (!isset($fixroot) || $fixroot ==""){
    $fixroot = $CFG->root_user;
  }
  $fixweb = $_REQUEST["fixweb"];
  if (!isset($fixweb) || $fixweb ==""){
    $fixweb = $CFG->web_user;
  }
  //以下參數設定
  $upload_dir   = $path ; //存放上傳圖片的目錄
  $upload_path  = $fixroot.$upload_dir ; //存放裁切後的圖像
  $lastchar = $upload_path{strlen($upload_path)-1};
  if ( $lastchar !="/"){
    $upload_path.="/";
  }
  $upload_path_web  = $fixweb.$upload_dir ;       //存放裁切後的圖像
  $thumb_web = $fixweb.$upload_dir."s/";       //存放裁切後的圖像
  $lastchar = $upload_path_web{strlen($upload_path_web)-1};
  if ( $lastchar !="/"){
    $upload_path_web.="/";
  }

  $max_width     = 1024 ;//上傳圖片的最大寬度
  $max_height    = 1024 ;
  $thumb_width   = $nw ;//裁切後小圖的初始寬度
  $thumb_height  = $nh ;//裁切後小圖的初始高度

  $large_image_location = $upload_path.$file_name;

  if  (isset( $_POST [ "upload_thumbnail" ])) {
    $thumb_is = true;
    $large_image_location = $upload_path.$file_name;
    $thumb_file_name = $file_name;
    $thumb_image_location = $upload_path."s/".$thumb_file_name;
    if (is_file($large_image_location)){
      $x1  = $_POST [ "x1" ];
      $y1  = $_POST [ "y1" ];
      $x2  = $_POST [ "x2" ];
      $y2  = $_POST [ "y2" ];
      $w  = $_POST [ "w" ];
      $h  = $_POST [ "h" ];
      $scale  = $thumb_width / $w ;
      $cropped  = resizeThumbnailImage( $thumb_image_location , $large_image_location , $w , $h , $x1 , $y1 , $scale );
      if (is_file($thumb_image_location)){

      }else{
        $error = "裁切圖片失敗!!";
      }
    }else{
      $error = "遺漏大圖位置,請重新操作!!";
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?> 圖片裁切</title>
<link href="<?=$CFG->f_admin?>css/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts-min.js" ></script>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts.css"  />
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>js/plib.js"></script>
<script type= "text/javascript"  src= "<?=$CFG->f_admin?>js/jquery.imgareaselect.pack.js" ></script>
<script type="text/javascript">
  var par = window.opener;
  function setImg(file_name,upload_path_web,width,height){
    if (par){
      var trid = "_"+file_name.replace(".", "");

      var obj = par.$("#"+trid);
      var imagesrc = upload_path_web+file_name+"?rad=<?=time();?>";
      var imgstyle = "width:<?=$nw?>px'; height:<?=$nh?>px";
      obj.find(".dz-image img").attr("src",imagesrc).attr("style",imgstyle);
      window.close();
      par.focus();
    }else{
      window.close();
    }
  }
</script>
<?php
  $current_width  = getWidth( $large_image_location );
  $current_height  = getHeight( $large_image_location );
?>
<script type= "text/javascript" >
function  preview(img, selection) {
  var scaleX = <?php echo  $thumb_width ;?> / selection.width;
  var scaleY = <?php echo  $thumb_height ;?> / selection.height;
  $( '#thumbnail + div > img' ).css({
    width: Math. round (scaleX * <?php echo  $current_width ;?>) + 'px' ,
    height: Math. round (scaleY * <?php echo  $current_height ;?>) + 'px' ,
    marginLeft: '-'  + Math. round (scaleX * selection.x1) + 'px' ,
    marginTop: '-'  + Math. round (scaleY * selection.y1) + 'px'
  });
  $( '#x1' ).val(selection.x1);
  $( '#y1' ).val(selection.y1);
  $( '#x2' ).val(selection.x2);
  $( '#y2' ).val(selection.y2);
  $( '#w' ).val(selection.width);
  $( '#h' ).val(selection.height);
  $('#show_w').html(selection.width);
  $('#show_h').html(selection.height);
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
      $('#save_thumb').hide();
      return  true;
    }
  });
});

$(window).load( function  () {
  $( '#thumbnail' ).imgAreaSelect({  minWidth: <?=$thumb_width?>, minHeight: <?=$thumb_height?>,handles: true, aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>' , onSelectChange: preview });
});

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
<div style="background:#F1EFE2;border:1px solid #D4D0C8;padding:2px;">
  <div style="padding:5px;">
    原檔尺寸：<?=$current_width?> px * <?=$current_height?> px。<br>
    目標尺寸：<?=$nw?> px * <?=$nh?> px。
  </div>
</div>
<?php
  if ( strlen ( $error )>0){
    echo  "<ul><li><strong>錯誤!</strong></li><li>" . $error . "</li></ul>" ;
  }

  if ($thumb_is && strlen($error)==0){
    list($thumbwidth, $thumbheight) = @getimagesize($thumb_image_location);
    $filesize = floor(filesize($thumb_image_location)/1024);
    ?>
  <div style="padding:5px" id="act_area">
    <div style="padding:5px;">操作成功!!：</div>
    <div><img src="<?php echo $thumb_web.$thumb_file_name;?>" style= "border:1px #e5e5e5 solid;"/></div>
    <div>
      <input type="button" value="確認返回" onclick="setImg('<?=$thumb_file_name?>','<?=$thumb_web?>',<?=$thumbwidth?>,<?=$thumbheight?>)"  style="padding:4px;padding-left:10px;padding-right:10px;">
    </div>
  </div>
      <?
    }
?>
  <div id="thumb_area" style="padding:5px;display:<?=$thumb_is?"block":"block"?>">
    <div style="padding:5px;">請拖動滑鼠選擇裁切區域，右邊為裁切後預覽區塊：</div>
    <div>目前選擇尺寸：<span id="show_w"></span> px * <span id="show_h"></span> px </div>
    <div align= "center">
      <img src= "<?php echo $upload_path_web.$file_name;?>" style= "float: left; margin-right: 10px;cursor:crosshair;border:1px #e5e5e5 solid; "  id= "thumbnail"  alt= "原圖"  />
      <div style= "border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px; " >
        <img src= "<?php echo $upload_path_web.$file_name;?>"  style= "position: relative;"  alt= "裁圖預覽"  />
      </div>
      <br style= "clear:both;" />
    </div>
    <br/>
      <form name= "thumbnail"  action= "<?php echo $_SERVER[" PHP_SELF "];?>"  method= "post" >
        <input type="hidden" name="path" value="<?=$path?>">
        <input type="hidden" name="pre" value="<?=$prefix?>">
        <input type="hidden" name="nw" value="<?=$nw?>">
        <input type="hidden" name="nh" value="<?=$nh?>">
        <input type="hidden" name="file_name" value="<?=$file_name?>">

        <input type= "hidden"  name= "x1"  value= ""  id= "x1"  />
        <input type= "hidden"  name= "y1"  value= ""  id= "y1"  />
        <input type= "hidden"  name= "x2"  value= ""  id= "x2"  />
        <input type= "hidden"  name= "y2"  value= ""  id= "y2"  />
        <input type= "hidden"  name= "w"  value= ""  id= "w"  />
        <input type= "hidden"  name= "h"  value= ""  id= "h"  />
        <input type= "submit"  name= "upload_thumbnail"  value= "存檔"  id= "save_thumb" style="padding:4px;padding-left:10px;padding-right:10px;" />
      </form>
  </div>
</body>
</html>