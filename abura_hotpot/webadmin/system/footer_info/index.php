<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../../header.php';
checkAuthority("system");
//初始化DAO
$dao = new main();
//讀取操作模式
$mod = pgParam("mod", "");
if(isset($_POST["active"]) && $_POST["active"] == "run") {
    $dao->update_onepage();
    if($dao->action_message == "true") {
        echo "<script>info_msg('操作成功!!');</script>";
    } else {
        echo "<script>info_msg('$dao->action_message')</script>";
    }
}
$dao->load_onepage();
?>
<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
    <input type="hidden" name="active" value="run"/>
    <div class="x-panel" id="x-webedit">
        <div class="x-panel-header"><?=$page_navigation?></div>
        <div class="x-panel-bbar">
            <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            </div>
        </div>
        <div id="mtabs-tw">
        <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
            <div>
                <table class="x-table" style=" border-collapse: collapse;">
                    <tr class="x-tr1">
                        <th class="x-th">頁尾單元名稱 - 英文</th>
                        <td class="x-td" valign="top"><?=$dao->html('title_en')?></td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">頁尾單元名稱</th>
                        <td class="x-td" valign="top"><?=$dao->html('title')?></td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">電話欄位</th>
                        <td class="x-td" valign="top">
                            <?=$dao->html('tel_title')?>
                            電話　　
                            <?=$dao->html('tel')?>
                        </td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">地址欄位</th>
                        <td class="x-td" valign="top">
                            <?=$dao->html('add_title')?>
                            地址　　
                            <?=$dao->html('add')?>
                        </td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">營業時間欄位</th>
                        <td class="x-td" valign="top">
                            <?=$dao->html('time_title')?>
                            營業時間
                            <?=$dao->html('time')?>
                        </td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">Facebook-網址	</th>
                        <td class="x-td" valign="top"><?=$dao->html('link_facebook')?></td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">IG-網址	</th>
                        <td class="x-td" valign="top"><?=$dao->html('link_ig')?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="x-panel-bbar">
            <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            </div>
        </div>
    </div>
</form>
<?php include('../../footer.php');?>
<script type="text/javascript">
$(document).ready(function(){
    $("#mtabs").tabs();
});

var editors = new Array();
var editor_fix_path = "<?=$CFG->footer_ed?>";

function goSubmit(){
    var elen = editors.length;
    for(var i=0; i < elen;i++){
        var tmparr = editors[i];
        var _id   = tmparr[1];
        var tmped = tmparr[0];
        var content = tmped.getData();
        $("#"+_id).val(content);
    }

    var errormessage = "";
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    $("#eForm1").submit();
}
</script>
