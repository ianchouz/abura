<?include_once("../../applib.php");?>
<?include_once("thumb_tool.php");?>
<?
  //要設定的圖片參數
  $path = $_REQUEST["path"];
  $prefix = $_REQUEST["pre"];
  $nw = $_REQUEST["nw"];
  $nh = $_REQUEST["nh"];

  $back  = $_REQUEST["back"];

  $newfile = $_REQUEST["newfile"];

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
  $thumb_web = $fixweb.$upload_dir."s/";
  $lastchar = $upload_path_web{strlen($upload_path_web)-1};
  if ( $lastchar !="/"){
    $upload_path_web.="/";
  }

  $max_width     = 1024 ;//上傳圖片的最大寬度
  $max_height    = 1024 ;
  $thumb_width   = intval($nw,10);//裁切後小圖的初始寬度
  $thumb_height  = intval($nh,10);//裁切後小圖的初始高度
  
  //echo $thumb_width.','.$thumb_height;

  $large_image_location = $upload_path.$file_name;

  if  (isset( $_POST [ "upload_thumbnail" ])) {
    $thumb_is = true;
    $large_image_location = $upload_path.$file_name;

    if (is_file($large_image_location)){
      $x1  = $_POST [ "x1" ];
      $y1  = $_POST [ "y1" ];
      $x2  = $_POST [ "x2" ];
      $y2  = $_POST [ "y2" ];
      $w  = $_POST [ "w" ];
      $h  = $_POST [ "h" ];

      $_thumb_height = $thumb_height;
      $_thumb_width = $thumb_width;
      //echo $_thumb_height.' , '.$_thumb_width.'<br>';
      
      
      if ($_thumb_height==0 && $_thumb_width ==0){
      	$_thumb_height = $h;
      	$_thumb_width = $w;
      }else if($_thumb_height==0){
      	$_thumb_height =round( ($h/$w)*$_thumb_width);
      }else if($_thumb_width==0){
      	$_thumb_width =round( ($w/$h)*$_thumb_height);
      }

      //echo $_thumb_height.' , '.$_thumb_width.'<br>';

      $scale  = $_thumb_width / $w ;


      $newImageWidth  = round ( $w  * $scale );
      $newImageHeight  = round ( $h  * $scale );
      //echo $w.' , '.$h.' , '.$x1.' , '.$y1.' , '.$scale.'<br>';

      if ($newfile=='N'){
        $thumb_file_name = $file_name;
      }else{
        $thumb_file_name = getThumbName($file_name,$newImageWidth,$newImageHeight);
      }
      //echo $newImageWidth.' , '.$newImageHeight.'<br>';
      $thumb_image_location = $upload_path."s/".$thumb_file_name;
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
<?require('../../base_header_lib.php');?>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>js/css/imgareaselect-default.css" />
<script type= "text/javascript"  src= "<?=$CFG->f_admin?>js/jquery.imgareaselect.pack.js" ></script>
<script type="text/javascript">
  var par = window.opener;
  function setImg(file_name,upload_path_web,width,height){
    if (par!=null){
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
        var hh = '<img src="' + upload_path_web + file_name + '" style="top:'+thumbftop+'px; left:'+thumbfleft+'; width:'+thumbfwidth+'; height:'+thumbfheight+';">';
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
    }else{
      // update dropzone
      if (parent.$('.dropzone')){
        var fildid = "_"+file_name.replace(".", "");
        var hh = '<img src="' + upload_path_web + file_name + '" style="top:'+thumbftop+'px; left:'+thumbfleft+'; width:'+thumbfwidth+'; height:'+thumbfheight+';" data-dz-thumbnail />';
		if(parent.$("#"+fildid+" .dz-image").length>0){
			parent.$("#"+fildid+" .dz-image").html(hh);
		}
      }
      if (parent){
        parent.$.fancybox.close();
      }
    }
  }
</script>
<?php
  $current_width  = getWidth( $large_image_location );
  $current_height  = getHeight( $large_image_location );
?>
<script type= "text/javascript" >
var zoomrate = 1;
var current_width = '<?=$current_width?>';
var current_height = '<?=$current_height?>';
var fix_width = '<?=$current_width?>';
var fix_height = '<?=$current_height?>';
//需要裁切的尺寸
var need_w = parseInt('<?=$thumb_width?>',10);
var need_h = parseInt('<?=$thumb_height?>',10);
var ias = null;
$(document).ready(function() {
	$(window).load(function() {
		setTimeout(function(){
			if ('<?=$noneWidth?>'=='Y'){
			  need_w = 0;
			}
			if ('<?=$noneHeight?>'=='Y'){
			  need_h = 0;
			}
			
			var fix_max_w = $(window).width() * 0.9;
			var fix_max_h = $(window).height() * 0.7;
			
			//如果圖片超過操做視窗大小，就縮圖
			if (current_height > fix_max_h){
			  //以Height為主
			  fix_height  = fix_max_h;
			  zoomrate = fix_max_h/current_height;
			  fix_width = zoomrate * current_width;
			}else{
			  //以Width為主
			  var fix_max_w = fix_max_w;
			  fix_width  = fix_max_w;
			  zoomrate = fix_max_w/current_width;
			  fix_height = zoomrate * current_height;
			}
			//$("#oth_msg").html('need_w:'+need_w+'px ,need_h:'+need_h+'px <br>');
			//$("#oth_msg").append('<br>rate:'+(need_w/need_h)+' <br>');
			zoomrate = formatFloat(zoomrate,4);
			need_w = Math.ceil(zoomrate * need_w);
			need_h = Math.ceil(zoomrate * need_h);
			current_w = Math.ceil(zoomrate * current_width);
			current_h = Math.ceil(zoomrate * current_height);
			
			//$("#oth_msg").append('圖片與實際尺寸縮放比例：'+zoomrate);
			$("#thumbnail").css({'width':fix_width+'px','height':fix_height+'px'});
			//$("#oth_msg").append('<br>need_w：'+need_w +',need_h:'+need_h);
			
			//如果是圖片小於尺寸，則調整縮放限度
			if (current_w < need_w){
			    need_w_fix = current_w;
			}else{
			    need_w_fix = need_w;
			}
			if (current_h < need_h){
				need_h_fix = current_h;
			}else{
			    need_h_fix = need_h;
			}
			
			if (need_w_fix !=0 && need_h_fix !=0){
			  //有限制長寬
			  //$("#oth_msg").append('<br>rate:'+(need_h_fix/need_w_fix));
			  ias = $( '#thumbnail' ).imgAreaSelect({ minWidth: need_w_fix, minHeight: need_h_fix,handles: true, aspectRatio: '1:<?php echo $thumb_height/($thumb_width==0?1:$thumb_width);?>', onSelectChange: preview});
			}else if (need_w_fix !=0){
			  ias = $( '#thumbnail' ).imgAreaSelect({ instance: true,minWidth: need_w_fix,handles: true, onSelectChange: preview });
			}else if (need_h_fix !=0){
			  ias = $( '#thumbnail' ).imgAreaSelect({ instance: true,minHeight: need_h_fix,handles: true , onSelectChange: preview});
			}else{
			  ias = $( '#thumbnail' ).imgAreaSelect({instance: true,handles: true, onSelectChange: preview});
			}
			
			$( '#save_thumb' ).click( function () {
			  var  x1 = $( '#x1' ).val();
			  var  y1 = $( '#y1' ).val();
			  var  x2 = $( '#x2' ).val();
			  var  y2 = $( '#y2' ).val();
			  var  w = $( '#w' ).val();
			  var  h = $( '#h' ).val();
			  if (x1== ""  || y1== ""  || x2== ""  || y2== ""  || w== ""  || h== "" ){
				alert( "請選擇您要裁切的區塊!!" );
				return  false;
			  } else {
				return  true;
			  }
			});
		}, 0);
	});
});

function preview(img, selection)
{
  $( '#x1' ).val(Math.round(selection.x1/zoomrate));
  $( '#y1' ).val(Math.round(selection.y1/zoomrate));
  $( '#x2' ).val(Math.round(selection.x2/zoomrate));
  $( '#y2' ).val(Math.round(selection.y2/zoomrate));
  $( '#w' ).val(Math.round(selection.width/zoomrate));
  $( '#h' ).val(Math.round(selection.height/zoomrate));
  $('#show_w').html(Math.round(selection.width/zoomrate));
  $('#show_h').html(Math.round(selection.height/zoomrate));
}
function formatFloat(num, pos)
{
  var size = Math.pow(10, pos);
  return Math.round(num * size) / size;
}
function setReat(){
	var setRate = $('input[name=setRate]:checked').val();
	if (setRate=='Y'){
		var nnw = parseInt($("#nw").val()) * zoomrate;
		var nnh = parseInt($("#nh").val())* zoomrate;   
		if (isNaN(nnw)){
      alert( "請輸入目標寬度!!" );
      $('input[name=setRate]').attr('checked',false);
      $("#nw").focus();
      return  false;			
		}
		if (isNaN(nnh)){
      alert( "請輸入目標高度!!" );
      $('input[name=setRate]').attr('checked',false);
      $("#nh").focus();
      return  false;			
		}

 		//if (!ias.getSelection().width){
 		//  alert("請先選擇區塊!!");
 		//  return  false;		
 		//}else{
 		//  ias.animateSelection(100, 75, 300, 225, 'slow');		
 		//}
 		//ias.update();
		//ias.setSelection(50, 50, 150, 200, true);
		//ias.setOptions({ show: true });
		
		//if (need_w !=0){
		//	ias = ias.setOptions({ minWidth: need_w,aspectRatio: ''});
		//}else if (need_h !=0){
   	//ias = ias.setOptions({ minHeight: need_h,aspectRatio: ''});
  	//}else{
   	//ias = ias.setOptions({minWidth: '', minHeight: '',aspectRatio: ''});
  	//}
		//ias.update();		
		
		//alert(!ias.getSelection().width);
		var rate_ = nnh/nnw;
		ias.setOptions({ aspectRatio: '1:'+ rate_,minWidth: nnw, minHeight: nnh,show: true});  
		if (ias.getSelection().width){
			ias.setSelection(ias.getSelection().x1, ias.getSelection().y1, 300, 225);
		}
		ias.update();	
		
	}else{
		if (need_w !=0){
			ias.setOptions({ minWidth: need_w, minHeight: '',aspectRatio: ''});
		}else if (need_h !=0){
    	ias.setOptions({ minHeight: need_h,minWidth: '',aspectRatio: ''});
  	}else{
    	ias.setOptions({minWidth: '', minHeight: '',aspectRatio: ''});
  	}
		ias.update();		
	}

}
function do_close(){
  if(typeof parent.loadimages == 'function') {
    parent.loadimages();
  }else{
    parent.$.fancybox.close();
  }
}
function fixshowIMG(w,h){
  $(window).load(function() {
	setTimeout(function(){
	  var fix_max_w = $(window).width() * 0.9;
	  var fix_max_h = $(window).height() * 0.7;
	  
	  var new_w = w;
	  var new_h = h;
	  var new_rate = 1;
	
	  //如果圖片超過操做視窗大小，就縮圖
	  if ((w - fix_max_w) > 0 || (h - fix_max_h) > 0 ){
		if ((w - fix_max_w) > (h - fix_max_h)){
		  //以Width為主
		  new_w  = fix_max_w;
		  new_rate = fix_max_w/w;
		  new_h = new_rate * h;
		}else{
		  //以Height為主
		  new_h  = fix_max_h;
		  new_rate = fix_max_h/h;
		  new_w = new_rate * w;
		}
	  }
	  $("#success_img IMG").css({'width':new_w+'px','height':new_h+'px'});
	}, 0);
  });
}
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
    原檔尺寸：<?=$current_width?> px * <?=$current_height?> px。
    <?if ($nw !=0 && $nh !=0){
    ?><br>目標尺寸：寬 <?=$nw?> px * 高 <?=$nh?> px。<?
    }else if ($nw !=0){
    ?><br>目標尺寸：寬 <?=$nw?> px 。<?
    }else if ($nh !=0){
    ?><br>目標尺寸：高 <?=$nh?> px 。<?
    }?>
    <div id="oth_msg"></div>
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
      <div style="padding:5px;">操作成功!! <?=$thumbwidth?> px * <?=$thumbheight ?> px</div>
      <div id="success_img"><img src="<?php echo $thumb_web.$thumb_file_name;?>?time=<?=time()?>" style= "border:1px #e5e5e5 solid;"/></div>
      <?if($back==''){?>
      <div>
        <input type="button" value="確認返回" onclick="do_close()"  style="padding:4px;padding-left:10px;padding-right:10px;">
      </div>
      <?}else{?>
      <div>
        <input type="button" value="關閉視窗" onclick="do_close()"  style="padding:4px;padding-left:10px;padding-right:10px;">
      </div>
      <?}?>
    </div>
    <script>
    $(document).ready( function  () {
      fixshowIMG(<?=$thumbwidth?>,<?=$thumbheight?>);
    });
    </script>
    <?
  }
?>
  <div id="thumb_area" style="padding:5px;display:<?=$thumb_is?"block":"block"?>">
    <div style="padding:5px;">請拖動滑鼠選擇裁切區域：</div>
    <div align= "center">
      <div>目前選擇尺寸：<span id="show_w"></span> px * <span id="show_h"></span> px </div>
      <img src= "<?php echo $upload_path_web.$file_name;?>" style= "cursor:crosshair;border:1px #e5e5e5 solid; "  id= "thumbnail"  alt= "原圖"  />
    </div>
    <br/>
      <form name= "thumbnail"  action= "<?php echo $_SERVER[" PHP_SELF "];?>"  method= "post" >
        <input type="hidden" name="back" value="<?=$back?>">
        <input type="hidden" name="path" value="<?=$path?>">
        <input type="hidden" name="pre"  value="<?=$prefix?>">
        <?if($nw==0 || $nw=='0'){?>
        目標寬度：<input type="text" name="nw" id="nw" value="" size="10"> px
      	<?}else{?>
        <input type="hidden" name="nw" id="nw"  value="<?=$nw?>">
      	<?}?>      	
        <?if($nh==0 || $nh=='0'){?>
        目標高度：<input type="text" name="nh" id="nh" value="" size="10"> px 
      	<?}else{?>
        <input type="hidden" name="nh" id="nh"   value="<?=$nh?>">
      	<?}?>
      	
      	<?if($nh==0 || $nh=='0' || $nw==0 || $nw=='0'){?>
      		<input type="checkbox" name="setRate" value="Y" onclick="setReat()"> 等比
      	<?}?>
      	
        <input type="hidden" name="file_name" value="<?=$file_name?>">
        <input type="hidden"   name="newfile" value="<?=$newfile?>">
        <input type="hidden" name="fixroot" value="<?=$fixroot?>">
        <input type="hidden" name="fixweb"  value="<?=$fixweb?>">
        <input type= "hidden"  name= "x1"  value= ""  id= "x1"  />
        <input type= "hidden"  name= "y1"  value= ""  id= "y1"  />
        <input type= "hidden"  name= "x2"  value= ""  id= "x2"  />
        <input type= "hidden"  name= "y2"  value= ""  id= "y2"  />
        <input type= "hidden"  name= "w"  value= ""  id= "w"  />
        <input type= "hidden"  name= "h"  value= ""  id= "h"  />
        <div style="margin-top:10px;">
        <input type= "submit"  name= "upload_thumbnail"  value= "確認裁切"  id= "save_thumb" style="padding:4px;padding-left:10px;padding-right:10px;" />
       
      	<?if($back==''){?>
      	  <input type="button" value="直接返回" onclick="setImg('<?=$file_name?>','<?=$upload_path_web?>',<?=$current_width?>,<?=$current_height?>)"  style="padding:4px;padding-left:10px;padding-right:10px;">
      	<?}else{?>
      	  <input type="button" value="關閉視窗" onclick="do_close()"  style="padding:4px;padding-left:10px;padding-right:10px;">
      	<?}?>
      	</div>
      </form>
  </div>
</body>
</html>