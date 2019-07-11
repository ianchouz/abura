<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../../header.php';
checkAuthority($menu_id);
//初始化DAO

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
    <div>
        <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
            <div>
                <table class="x-table" style=" border-collapse: collapse;">
                    <tr class="x-tr1">
                        <td valign="top">
                            <table class="x-table" style=" border-collapse: collapse;">
                                <tr class="x-tr1">
                                    <th class="x-th" colspan="4" style="text-align:left;">聯絡資料</th>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">公司名稱</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('company')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">電話</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('tel')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">信箱</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('email')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">傳真</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('fax')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">住址</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('add')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">官網</th>
                                    <td class="x-td" valign="top" colspan="3"><?=$dao->html('website')?></td>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th">地圖</th>
                                    <td class="x-td" valign="top"><?=$dao->html('map')?></td>
                                    <th class="x-th">地圖-手機</th>
                                    <td class="x-td" valign="top"><?=$dao->html('map-mbl')?></td>
                                </tr>
                            </table>
                            <!-- <table class="x-table" style=" border-collapse: collapse;">
                                <tr class="x-tr1">
                                    <th class="x-th" colspan="2" style="text-align:left;">表單類別</th>
                                </tr>
                                <tr class="x-tr1">
                                    <th class="x-th"></th>
                                    <td class="x-td" valign="top"><?=$dao->html('subjects')?>
                                      <strong>選項以逗點分隔</strong>
                                    </td>
                                </tr>
                            </table> -->
                        </td>
                    </tr>
                </table>
                <table class="x-table" style=" border-collapse: collapse;">
                    <tr class="x-tr1">
                        <th class="x-th" colspan="2" style="text-align:left;">聯絡表單</th>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th">送出後訊息</th>
                        <td class="x-td"><?=$dao->html('sendsuccess')?></td>
                    </tr>
                     <tr class="x-tr1" style="display: none;">
                        <th class="x-th">收件人</th>
                        <td class="x-td" valign="top">
                            <div style="padding:5px;"><b>若無填寫，則以系統收件者為主</b></div>
                            <?=$dao->html('recipient')?>
                        </td>
                    </tr>
                </table>
            </div>
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
var editor_fix_path = "<?=$CFG->contact_us_ed?>";
function goSubmit(){
    deal_editor_to_sendajaxform();

    var errormessage = "";
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
   $("#eForm1").submit();
}
</script>
