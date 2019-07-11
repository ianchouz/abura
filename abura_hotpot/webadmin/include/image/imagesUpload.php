<?php include_once("../../applib.php");?>
<?php include_once( '../../include/checksession.php');
//從JS收到的圖片參數
$path = @$_REQUEST["path"];
$prefix = @$_REQUEST["pre"];
$nw = @$_REQUEST["nw"];
$nh = @$_REQUEST["nh"];
$gdir = @$_REQUEST["gdir"];
$back = @$_REQUEST["back"];
if (!isset($gdir) || $gdir==""){
  $gdir = $path;
}
$rootpath = pgParam("rootpath",'');
$webpath = pgParam("webpath",'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?> 大量圖片上傳</title>
<?require('../../base_header_lib.php');?>
<link href="<?=$CFG->f_admin?>css/swfupload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/jquery.swfupload.js"></script>
<script type="text/javascript">
function do_close(){
  if(typeof parent.loadimages == 'function') {
    parent.loadimages();
  }else{
    parent.$.fancybox.close();
  }
} 
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
    $("#btn_clickUpload").click(function(){
      //目前目錄
      var gdir = $("#jBox_now_info").attr("data-dir");
    });
    $("#btn_goback").click(function(){
      goBack();
    });
    bindFlashLog("#swfuploadlog");
  });
  function goBack(){
    <?if ($prefix!=""){?>
    var openurl="<?=$CFG->f_admin?>include/image/choice_single.php?pre=<?=$prefix?>&path=<?=$path?>&nw=<?=$nw?>&nh=<?=$nh?>&gdir=<?=$gdir?>";
    <?}else{?>
    var openurl="<?=$CFG->f_admin?>include/image/choice_editor.php?path=<?=$path?>&gdir=<?=$gdir?>";
    <?}?>
    location.href = openurl;
  }
  function bindFlashLog(logid){
    var files = 0;
    var completes = 0;
    //圖片批次上傳
    $('#swfupload-control').swfupload({
       flash_url : "<?=$CFG->f_admin?>js/swfupload/swfupload.swf",
       upload_url: "<?=$CFG->f_admin?>include/image/uploads.php",
       file_size_limit : "5MB",
       file_types : "*.jpg;*.jpeg;*.png;*.gif;*.svg",
       file_types_description : "圖片檔案",
       file_upload_limit : "0",
	  	 //圖片按鈕
	  	 button_image_url : '<?=$CFG->f_admin?>images/swfupload/XPButtonUploadText_61x22.png',
	  	 button_width : 61,
	  	 button_height : 22,
	  	 button_placeholder : $('#btn_img_open')[0],
       //是否debug
       debug: false
    })
    //綁定flash上傳訊息
    .bind('fileQueued', function(event, file){
      $(logid).append('<li>準備上傳 - ' + file.name +'</li>');
      files++;
      var param = {"webtechWEB": "<?php echo $_SESSION['webtechWEB']; ?>",'gdir' : "<?=$gdir?>",'rootdir':"<?=$rootpath?>"};
      $(this).swfupload('setPostParams', param);
      $(this).swfupload('startUpload');
      movescroll(logid);
    })
    .bind('fileQueueError', function(event, file, errorCode, message){
      $(logid).append(' '+file.name+' 檔案大小超過限制');
      complete++;
      if (files!=0 && files==completes){
        $(logid).append('<li>全部上傳完成</li>');
      }
    })
    .bind('uploadSuccess', function(event, file, serverData){
      var res = serverData.split("||");
      $(logid).append('<li><font style="color:green;">'+res[0]+ ' - '+res[1]+'</font></li>');
      completes++;
      if (files!=0 && files==completes){
        $(logid).append('<li>全部上傳完成</li>');
      }
    })
    .bind('uploadComplete', function(event, file){
      var param = {"webtechWEB": "<?php echo $_SESSION['webtechWEB']; ?>",'gdir' : "<?=$gdir?>",'rootdir':"<?=$rootpath?>"};
      $(this).swfupload('setPostParams', param);
      $(this).swfupload('startUpload');
      movescroll(logid);
    });
  }
  //}
  function movescroll(logid){
    $("#logcontent").animate({ scrollTop: $("#logcontent").attr("scrollHeight") }, 3000);
  }

</script>
<style>
html{
  margin: 0;
  padding: 0;
  width:100%;
  height:100%;
  overflow:auto;
}
body{
  margin: 0;
  padding: 0;
  height:100%;
  font-family: 微軟正黑體,arial,verdana,helvetica,tahoma,Sans-serif;
  font-size:12px;
}
table{
  border:0px;border-collapse:collapse;
}
#photo-list{
text-align:left;
margin-left:0px;
}
#photo-list li {
  margin:5px;
}
#album-list li, #photo-list li {
  float:left;
}
#photo-list ul {margin:0px;list-style-type:none;margin: 0;padding: 0;}
li {
  list-style:none outside none;
}
</style>
</head>
<body>
  <table width="100%" height="100%" border="1" style="width:100%;height:100%;">
    <tr>
      <td valign="top">
        <div style="background:#F1EFE2;border:1px solid #D4D0C8;padding:5px;">
          <?if ($back!='N'){?><input type="button" value="返回"  id="btn_goback"><?}?> 
    <?if ($back=='N'){
      ?><input type= "button"  name= "doclose"  value= "關閉視窗" onclick="do_close()" /><?
    }?>          
          <input type="button" id="btn_img_open" value="上傳圖片"/>
        </div>
        <div style="padding:5px;" id="jBox_now_info" class="jBox_now_info">
          目標目錄：<?=$gdir?>
        </div>
        <hr/>
        <div id="swfupload-control">
          <div style="font-size:11px;color:blue;">上傳圖片訊息：</div>
          <div id="logcontent" style="border:1px solid #D7D7D7;font-size:10px;height:200px;overflow:auto;">
            <ul id="swfuploadlog" style="padding:5px;margin:0px;"></ul>
          </div>
        </div>
      </td>
    </tr>
  </table>
</body>
</html>