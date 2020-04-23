<?include_once("../../applib.php");?>
<?include_once( '../../include/checksession.php');
function getDirectoryTree( $outerDir ){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
    $dir_array = Array();
    foreach( $dirs as $d ){
        if( is_dir($outerDir."/".$d) ) $dir_array[ $d ] = getDirectoryTree( $outerDir."/".$d );
        else $dir_array[ $d ] = $d;
    }
    return $dir_array;
}

//從JS收到的圖片參數
$path = $_REQUEST["path"];
$prefix = $_REQUEST["pre"];
$nw = $_REQUEST["nw"];

$nh = $_REQUEST["nh"];
$sw = $_REQUEST["sw"];
$sh = $_REQUEST["sh"];
if ($nw=='') $nw=0;
if ($nh=='') $nh=0;
$gdir = $_REQUEST["gdir"];
  $noneWidth = $_REQUEST["noneWidth"];
  $noneHeight = $_REQUEST["noneHeight"];
  if (isset($noneWidth)){
    //$noneWidth = 'Y';
  }else{
    $noneWidth = '';
  }
  if (isset($noneHeight)){
    //$noneHeight = 'Y';
  }else{
    $noneHeight = '';
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$CFG->company_name?> 圖片選擇</title>
<?require('../../base_header_lib.php');?>
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
      var openurl="<?=$CFG->f_admin?>include/image/imagesUpload.php?pre=<?=$prefix?>&path=<?=$path?>&nw=<?=$nw?>&nh=<?=$nh?>&gdir="+gdir;
      location.href=openurl;
    });
  });
  function uHTML5(){
  }
  function uFLASHS(){
  }
  function uSINGLE(){
  }    
  function showRoot(){
    var fx = par.$('#<?=$prefix?>imagearea').attr("data-w");
    var fh = par.$('#<?=$prefix?>imagearea').attr("data-h");
    $('#jBox-dirtree').fileTree({ root: '<?=$path?>'
      ,script:'<?=$CFG->f_admin?>include/jqueryFileTree/jqueryDirTree.php'
      ,listurl:'<?=$CFG->f_admin?>include/image/imageBrowser.php'
      ,delurl:'<?=$CFG->f_admin?>include/image/imageDelete.php'
      ,mkdirurl:'<?=$CFG->f_admin?>include/image/imageDir.php'
      ,gdir:gdir
      ,fixwidth:fx
      ,fixheight:fh
    },function(data) {
      if (data != null){
        if (data.width > <?=$nw?> || data.height > <?=$nh?>){
          var filename = data.name;
          location.href="thumb_image.php?path="+data.topdir+"&pre=<?=$prefix?>&noneWidth=<?=$noneWidth?>&noneHeight=<?=$noneHeight?>&nw=<?=$nw?>&nh=<?=$nh?>&file_name="+filename;
        }else{
          setImg(data);
        }
      }
    });
    gdir = "";
  }
  function setImg(data){
    if (par && "<?=$prefix?>" !=""){
      var hh = '<img src="' + data.url + '" style="top:' + data.thumbftop + '; left:' + data.thumbfleft + '; width:' + (data.thumbfwidth?data.thumbfwidth:"") + '; height:' + (data.thumbfheight?data.thumbfheight:"") + ';">';
      par.$('#<?=$prefix?>imagearea').html(hh);
      par.$('#<?=$prefix?>path').val((data.topdir==""?"":data.topdir)+data.name);
      par.$('#<?=$prefix?>').val(data.name);
      par.$('#<?=$prefix?>owidth').text(data.width);
      par.$('#<?=$prefix?>oheight').text(data.height);
      par.$('#<?=$prefix?>opath').text(data.name);
      window.close();
      par.focus();
    }else{
      window.close();
    }
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
          <input type="button" value="選擇" id="btn_choiceIMG"> 
          <input type="button" value="刪除選取檔案" id="btn_clickdeleteimg"> 
          <input type="button" value="刪除目錄" id="btn_clickdeletedir"> 
          <input type="button" value="建立新目錄" id="btn_clickcreatedir">           
          <input type="button" value="上傳圖片" id="btn_clickUpload">
        </div>
        <div style="padding:5px;" id="jBox_now_info" class="jBox_now_info">
          <div>圖片尺寸：<?if ($nw >0){ echo '寬 '.$nw.'px';}?> <?if ($nh >0){ echo '高 '.$nh.'px';}?> </div>
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