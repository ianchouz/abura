<?include_once("../../applib.php");?>
<?
//從JS收到的圖片參數
$path = $_REQUEST["path"];
$gdir = $_REQUEST["gdir"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?> 檔案 選擇</title>
<link href="<?=$CFG->f_admin?>css/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts-min.js" ></script>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts.css"  />
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>js/plib.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>include/jqueryFileTree/jqueryFileTree.css"  />
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>include/jqueryFileTree/jqueryFileTree.js"></script>
<script type="text/javascript">
  var gdir = "<?=$gdir?>";
  var par = window.opener;
  $(document).ready(function(){
    if (par){
      //alert("<?=$path?>");
    }else{
      alert("找不到父視窗!!");
      window.close();
    }
    showRoot();
    $("#btn_clickUpload").click(function(){
      //目前目錄
      var gdir = $("#jBox_now_info").attr("data-dir");
      var openurl="<?=$CFG->f_admin?>include/file/fileUpload.php?pre=<?=$prefix?>&path=<?=$path?>&nw=<?=$nw?>&nh=<?=$nh?>&gdir="+gdir;
      location.href = openurl;
    });
  });
  function showRoot(){
    $('#jBox-dirtree').fileTree({ root: '<?=$path?>'
      ,script:'<?=$CFG->f_admin?>include/jqueryFileTree/jqueryDirTree.php'
      ,listurl:'<?=$CFG->f_admin?>include/file/fileBrowser.php'
      ,delurl:'<?=$CFG->f_admin?>include/file/fileDelete.php'
      ,mkdirurl:'<?=$CFG->f_admin?>include/file/fileDir.php'
      ,gdir:gdir
    },function(data) {
      if (data != null){
        par.set2FileEditor(data);
        window.close();
        par.focus();
      }
    });
    gdir = "";
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
      <th width="20%" valign="top" style="border-right:5px solid #F1EFE2;text-align:left;">
        <div style="background:#F1EFE2;border:1px solid #D4D0C8;padding:2px;">資料夾</div>
        <div class="jqueryFileTree">
          <li class="directory expanded">
            <span style="padding-left: 20px;cursor:pointer;" onclick="showRoot();" title="重新載入"><?=$path?> </span>
            <div id="jBox-dirtree" style="padding-left: 20px;"></div>
          </li>
        </div>
      </th>
      <td width="80%" valign="top">
        <div style="background:#F1EFE2;border:1px solid #D4D0C8;padding:5px;">
          <input type="button" value="選擇" id="btn_choiceIMG"> <input type="button" value="刪除選取檔案" id="btn_clickdeleteimg"> <input type="button" value="刪除目錄" id="btn_clickdeletedir"> <input type="button" value="建立新目錄" id="btn_clickcreatedir"> <input type="button" value="上傳檔案" id="btn_clickUpload">
        </div>
        <div style="padding:5px;" id="jBox_now_info" class="jBox_now_info">
          <div id="jBox_now_dir">目前目錄：<?=$path?></div>
          <div id="jBox_now_fileinfo">目前選中檔案：</div>
          <div>每頁筆數:<select id="jBox_image_pagesize" style="font-size:9px;hiehgt:12px;">
            <option value="20">20</option><option value="50">50</option><option value="100">100</option>
          </select> &nbsp;檔名過濾：<input type="text" size="10" maxlength="10" id="jBox_filterkey"> &nbsp;
          <span id="jBox_page_list"></span>
          </div>
        </div>
        <hr/>
        <div style="padding:5px;" id="photo-list">
          <ul></ul>
        </div>
      </td>
    </tr>
  </table>
</body>
</html>