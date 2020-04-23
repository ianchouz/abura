<?php
include_once("../fn_arr.php");
include_once("fn_set.inc");
include '../../header.php';
checkAuthority($menu_id);

$mainid = $_REQUEST["id"];
if($mainid == '') {
    buildMsg('請重新點選!!', 'index.php');
}


if($fn_arr[$mainid] == '') {
    buildMsg('無法找到此資料!!', 'index.php');
}
?>
<script  type="text/javascript" src="<?=$CFG->fix_admin?>js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$CFG->fix_admin?>js/swfupload/jquery.swfupload.js"></script>
<!-- @ STEP: 1, includes -->
<!-- Customized CSS -->
<link rel="stylesheet" href="../../css/fileUpload.css">
<div class="x-panel" id="x-webedit">
  <div class="x-panel-header">
    <?="[".$fn_arr[$mainid]['name']."]".$page_navigation."-形象圖片"?>
  </div>
  <div class="x-panel-body" style="padding:10px;">
     <!-- @ STEP: 2, HTML -->
     <?php
/*?><a href="index.php?id=<?=$mainid?>" class="fallback_solution">回到友善上傳</a><div style="clear: both;"></div><?php */
?>
     <div id="swfupload-control">
       <div style="font-size:11px;color:blue;">上傳圖片訊息：</div>
       <div id="logcontent" style="border:1px solid #D7D7D7;font-size:10px;height:50px;overflow:auto;">
         <ul id="swfuploadlog" style="padding:5px;margin:0px;"></ul>
       </div>
       <input type="button" id="btn_img_open" value="上傳圖片"/>
     </div>
     <div style="height:5px;"></div>
     <div style="padding:5px;color:red;">
       圖片請上傳 .jpg , .png , .gif , 圖片大小請勿超過: <?=$CFG->imgsize_limit?> MB。大圖尺寸：<?=$m_width?> px * <?=$m_height?> px。
     </div>
     <form name="eForm1" id="eForm1" method="post">
       <input type="hidden" name="mainid" value="<?=$mainid?>">
       <table style="border-collapse: collapse;" width="100%" id="list" class="x-table">
         <tr>
           <th class="nth" width="10%" align="center">動作</th>
           <th class="nth" width="10%" align="center">順序</th>
           <th class="nth" width="15%" align="center">檔名</th>
           <th class="nth" width="55%" align="left"></th>
         </tr>
         <?
//先列表出資料庫裡面的
$cc = 1;
$dbkey = array();
$strSQLQ = "select * from ".$table." where mainid='" . sql_real_escape_string($mainid) . "' order by seq asc";
$rs = @sql_query($strSQLQ);
$maxseq = "";

$show_width = $s_width;
if($show_width > 200)
    $show_width = 200;
while($tmpItem = @sql_fetch_array($rs)) {
    $filename = $tmpItem["filename"];
    $trid = str_replace(".", "", $filename);
    $trid = str_replace("!", "", $trid);
    $seq = $tmpItem["seq"];
    $fileMemo = $tmpItem["fileMemo"];
    $dbkey["$filename"] = $tmpItem;
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($fullDir . $filename);
?>
         <tr class='valueRowPIC' id="<?=$trid?>">
           <td align="center" class='ntd tc'>
             <input type='hidden' name='filename[]' value='<?=$filename?>'/>
             <div onclick='act_delDetailRow("<?=$trid?>");' title='删除' class='ddbtn' style='color:blue;cursor:pointer;'>刪除</div>
             <div onclick='act_thumb_b("<?=$trid?>");' title='裁切' class='dtbtn' style='color:blue;cursor:pointer;'>裁切</div>
           </td>
           <td align="center" class='ntd tc'><input type="text" name="seqs[]" size="6" maxlength="5" value="<?=$seq?>"></td>
           <td align="center" class='ntd tc'><span name='filenameshow'><?php
           echo "<img class='loadimages' alt='圖片載入中...' src='../../images/unknow.png' ref=\"" . $webfullsDir . $filename . "\" style=\"width:" . $show_width . "px;\">";
?></span></td>
           <!--<td class='ntd'><input type="text" name="fileMemo[]" size="10" maxlength="40" value="<?=$fileMemo?>"></td>-->
           <td style="text-align:left;" valign="top" class='ntd tc'><span name='othdesc'>檔名: <?=$filename?>,像素: <?=$img_width?> px * <?=$img_height?> px,大小: <?=floor(filesize($fullDir . $filename) / 1024)?> KB</span></td>
         </tr><?
    $maxseq = $seq;
    $cc++;
}
$cc = 0;
//列出資料夾裡面的圖片 (可能有新增的圖片)
if(is_dir($fullDir)) {
    $dh = opendir($fullDir);
    while(($file = readdir($dh)) !== false) {
        if(is_file($fullDir . $file) && basename($file) != '.' && basename($file) != '..') {
            //讀取資料庫的值
            $filename = "";
            if(isset($dbkey["$file"])) {
                $tmpItem = $dbkey["$file"];
                $filename = $tmpItem["filename"];
            }
            if($filename != "") {
                continue;
            }
            $filename = $file;
            $trid = str_replace(".", "", $filename);
            $trid = str_replace("!", "", $trid);
            $seq = "";
            $fileMemo = "";
            if(!is_file($fullsDir . $filename)) {
                @copy($fullDir . $filename, $fullsDir . $filename);
                //複製到小圖
                quickReSizeIMG($s_width, $s_height, $fullsDir, $filename);
            }
            list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($fullDir . $filename);
?>
         <tr class='valueRowPIC' id="<?=$trid?>" bgColor="#FFFF99">
           <td align="center" class='ntd tc'>
             <input type='hidden' name='filename[]' value='<?=$filename?>'/>
             <div onclick='act_delDetailRow("<?=$trid?>");' title='删除' class='ddbtn' style='color:blue;cursor:pointer;'>刪除</div>
             <div onclick='act_thumb_b("<?=$trid?>");' title='裁切' class='dtbtn' style='color:blue;cursor:pointer;'>裁切</div>
             
           </td>
           <td align="center" class='ntd tc'><input type="text" name="seqs[]" size="6" maxlength="5" value="<?=$seq?>"></td>
           <td align="center" class='ntd tc'><span name='filenameshow'><?
            $resizetool = new reSizeImage($s_width, $s_height, $fullsDir . $filename);
            echo "<img class='loadimages' alt='圖片載入中...' src='../../imgaes/unknow.png' ref=\"" . $webfullsDir . $filename . "\" style=\"width:" . $show_width . "px;\">";
?></span></td>
           <!--<td class='ntd'><input type="text" name="fileMemo[]" size="10" maxlength="40" value="<?=$fileMemo?>"></td>-->
           <td style="text-align:left;" valign="top" class='ntd tc'><span name='othdesc'>檔名: <?=$filename?>,像素: <?=$img_width?> px * <?=$img_height?> px,大小: <?=floor(filesize($fullDir.$filename) / 1024)?> KB</span></td>
         </tr>
            <?
            $cc++;
        }
    }
    closedir($dh);
}
?>
         <tr class='templeteRowPIC' style='display:none;' bgColor="#FFFF99">
           <td  class="ntd" align="center">
             <input type='hidden' name='filename[]' id="filename" value=''/>
             <div onclick='return false;' title='删除' class='ddbtn' style='color:blue;cursor:pointer;'>刪除</div>
             <div onclick='return false;' title='裁切' class='dtbtn' style='color:blue;cursor:pointer;'>裁切</div>
           </td>
           <td class="ntd"  align="center"><input type="text" name="seqs[]" size="6" maxlength="5" value=""></td>
           <td class="ntd"  align="center"><span name='filenameshow'><img src=""></span></td>
           <!--<td class="ntd" >
             <input type="text" name="fileMemo[]" size="10" maxlength="40" value="">
           </td>-->
           <td class="ntd"  style="text-align:left;" valign="top"><span name='othdesc'></span></td>
         </tr>
       </table>
      </form>
    </div>
    <div class="x-panel-bbar">
      <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
        <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
        <div class="btn_panel"><Button type="button" title="返回" class="button" onclick="goBack();"><div class="btn_cancel">返回</div></Button></div>
      </div>
    </div>
  </div>
</div>
<?php
include('../../footer.php');
?>
<script type="text/javascript">
$(document).ready(function(){
    bindFlashLog("#swfuploadlog");
    $('.loadimages').each(function(){
      var pp = $(this).attr("ref");
      $(this).attr("src",pp).attr("alt","");
    });
});
function goBack(){
    wait_msg("請稍等...");
    location.href="index.php";
}
function goSubmit(){
    hiConfirm('<h3>您確認要存檔？</h3>存檔前請先確認圖片已經裁切程系統需要的尺寸。<br> 若圖片比需要的尺寸大，系統將自動等比例縮圖。', '確認對話', function(r) {
        if (r){
            wait_msg("請稍等...");
            $.ajax({url: "imageSave.php",
                type: 'POST',
                data: $("#eForm1").serialize(),
                dataType: 'html',
                error: function(){warning_msg('請重新送出!!');},
                success: function(html){
                    var showmessage = "";
                    if (html==""){
                        info_msg('作業成功!!');
                        location.replace(location);
                    }else{
                        warning_msg(html);
                    }
                }
            });
        }else{
            return false;
        }
    });
}
//FLASH 圖片大量上傳
var predir = "<?=$webbaseRoot?>";
//{
function addtoList(filename,filedesc,width,height){
    if (filename==""){
        return false;
    }
    fullfilename = "<?=$webfullsDir?>"+filename;
    //檢查此檔明是否已經存在
    var trid = filename.replace(".", "");
    trid = trid.replace("!", "");
    $("#"+trid).remove();
    //取得這個圖片的資訊
    var imagesrc = fullfilename;
    var imgwidth = width;
    var imgheight = height;

    var imgstyle = "";
    var scalex = 1;
    var scaley = 1;
    var fixwidth = '<?=$s_width?>';
    var fixheight = '<?=$s_height?>';
    var thumbfwidth = fixwidth;
    if (thumbfwidth > 200) {
        thumbfwidth = 200;
    }

    var imgstyle ="width:"+thumbfwidth+'px';

    var templeteRow=$("tr.templeteRowPIC");
    var newRow = templeteRow.clone(true);
    newRow.attr("class", "valueRowPIC");
    newRow.attr("id", trid);
    newRow.find("#filename").attr("value",filename);
    newRow.find("img").attr("src",imagesrc).attr("style",imgstyle);
    newRow.find("span[name=othdesc]").html(filedesc);
    newRow.find("input[type=text]").each(
        function() {
            var nn = this.getAttribute('name');
            this.setAttribute("name",nn+trid);
        }
    );
    newRow.insertBefore(templeteRow);
    newRow.show();

    newRow.find("div.ddbtn").click(function(){
        act_delDetailRow(filename);
    });
    newRow.find("div.dtbtn").click(function(){
        act_thumb(filename);
    });
    newRow.find("div.dtbbtn").click(function(){
        act_thumb_b(filename);
    });
}
//{刪除tr的一行
function act_delDetailRow(who){
    hiConfirm('<h3>您確認要刪除？</h3>', '確認對話', function(r) {
        if (r){
            var trid = who.replace(".", "");
            
            var filename = $("#"+trid).find("[name='filename\[\]']").attr("value");
            $.post(
                "imageDelete.php",
                {mainid:"<?=$mainid?>",filename:filename},
                function(html){
                    if (html==""){
                        $("#swfuploadlog").append('<li>'+ filename +'刪除完成!!</li>');
                        movescroll();
                        $("#"+trid).remove();
                    }else{
                        alert("<font color='red'><b>刪除失敗：</b></font><br>" + html);
                    }
                },
                "html"
            );
        }else{
            return false;
        }
    });
}
//}
//{刪除tr的一行
function act_thumb(who){
    var trid = who.replace(".", "");
  
    var nw = '<?=$s_width?>';
    var nh = '<?=$s_height?>';
    var filename = $("#"+trid).find("[name='filename\[\]']").attr("value");

    var fixdir = "<?=$mainid?>";
    var openurl=fulladmin+"include/image/thumb_image2.php?file_name="+filename+"&fixroot=<?=$fullDir?>&fixweb=<?=$webfullDir?>&nw="+nw+"&nh="+nh;
    winopen(0,0,openurl,"裁圖");
}
function act_thumb_b(who){
    var trid = who.replace(".", "");
   
    var nw = <?=$m_width?>;
    var nh = <?=$m_height?>;
    var filename = $("#"+trid).find("[name='filename\[\]']").attr("value");

    var fixdir = "<?=$mainid?>";
    var openurl=fulladmin+"include/image/thumb_image2_b.php?noneRate=Y&file_name="+filename+"&fixroot=<?=$fullDir?>&fixweb=<?=$webfullDir?>&fixsweb=<?=$webfullsDir?>&nw="+nw+"&nh="+nh;
    winopen(0,0,openurl,"裁圖");
}
//}
function movescroll(){
    $('#logcontent').scrollTop($('#logcontent')[0].scrollHeight);
}
function bindFlashLog(logid){
    //圖片批次上傳
    $('#swfupload-control').swfupload({
        flash_url : "<?=$CFG->fix_admin?>js/swfupload/swfupload.swf",
        upload_url: "imageUpload.php",
        file_size_limit : (1024 * <?=$CFG->imgsize_limit?>),
        file_types : "*.jpg;*.jpeg;*.png;*.gif",
        file_types_description : "圖片",
        file_upload_limit : "0",
        //圖片按鈕
        button_image_url : '<?=$CFG->fix_admin?>images/swfupload/XPButtonUploadText_61x22.png',
        button_width : 61,
        button_height : 22,
        button_placeholder : $('#btn_img_open')[0],
        //是否debug
        debug: false
    })
    //綁定flash上傳訊息
    .bind('fileQueued', function(event, file){
        $(logid).append('<li>準備上傳 - ' + file.name +'</li>');
        var param = {'mainid' : "<?=$mainid?>"};
        $(this).swfupload('setPostParams', param);
        $(this).swfupload('startUpload');
        movescroll();
    })
    .bind('fileQueueError', function(event, file, errorCode, message){
        $(logid).append('<li>'+file.name+' 圖片大小超過限制 '+message+'</li>');
    })
    .bind('uploadSuccess', function(event, file, serverData){
        var res = serverData.split("||");
        $(logid).append('<li>'+res[0]+ ' - '+res[1]+'</li>');
        //filename,filedesc
        addtoList(res[1],res[2],res[3],res[4]);
    })
    .bind('uploadComplete', function(event, file){
        var param = {'mainid' : "<?=$mainid?>"};
        $(this).swfupload('setPostParams', param);
        $(this).swfupload('startUpload');
        movescroll();
    });
}
//}

<!-- @ STEP: 3, JS-->
//if(!isBrowserSupported()){
//    $('.fallback_solution').hide();
//}
</script>