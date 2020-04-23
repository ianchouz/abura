<?include_once("../../applib.php");?>
<?
//從JS收到的檔案參數
$path = @$_REQUEST["path"];
$prefix = @$_REQUEST["pre"];
$nw = @$_REQUEST["nw"];
$nh = @$_REQUEST["nh"];
$gdir = @$_REQUEST["gdir"];
if (!isset($gdir) || $gdir==""){
  $gdir = $path;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?> 檔案上傳</title>
<link href="<?=$CFG->f_admin?>css/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts-min.js" ></script>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts.css"  />
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>js/plib.js"></script>
<link href="<?=$CFG->f_admin?>css/swfupload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/jquery.swfupload.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var par = window.opener;
    if (par){
      //alert("<?=$path?>");
    }else{
      alert("找不到父視窗!!");
      window.close();
    }
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
    var openurl="<?=$CFG->f_admin?>include/file/choice_single.php?pre=<?=$prefix?>&path=<?=$path?>&nw=<?=$nw?>&nh=<?=$nh?>&gdir=<?=$gdir?>";
    <?}else{?>
    var openurl="<?=$CFG->f_admin?>include/file/choice_editor.php?path=<?=$path?>&gdir=<?=$gdir?>";
    <?}?>
    location.href = openurl;
  }
  function bindFlashLog(logid){
    var files = 0;
    var completes = 0;
    //Flash批次上傳
    $('#swfupload-control').swfupload({
       flash_url : "<?=$CFG->f_admin?>js/swfupload/swfupload.swf",
       upload_url: "<?=$CFG->f_admin?>include/file/uploads.php",
       file_size_limit : "3MB",
       file_types : "*.*",
       file_types_description : "所有檔案",
       file_upload_limit : "20",
	  	 //Flash按鈕
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
      var param = {'gdir' : "<?=$gdir?>"};
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
      var param = {'gdir' : "<?=$gdir?>"};
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
          <input type="button" value="返回"  id="btn_goback"> <input type="button" id="btn_img_open" value="上傳檔案"/>
        </div>
        <div style="padding:5px;" id="jBox_now_info" class="jBox_now_info">
          目標目錄：<?=$gdir?>
        </div>
        <hr/>
        <div id="swfupload-control">
          <div style="font-size:11px;color:blue;">上傳檔案訊息：</div>
          <div id="logcontent" style="border:1px solid #D7D7D7;font-size:10px;height:200px;overflow:auto;">
            <ul id="swfuploadlog" style="padding:5px;margin:0px;"></ul>
          </div>
        </div>
      </td>
    </tr>
  </table>
</body>
</html>