<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
$use_color = false;

include '../../header.php';
checkAuthority($menu_id);

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
    <div class="x-panel-header">系統設定</div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
        </div>
    </div>
    <div class="x-panel-body">
        <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
            <ul>
                <li><a href='#mtabs-000' style='font-size:12px;padding:5px 5px;'>形象圖</a></li>
            </ul>
            <div id="mtabs-000">
                <table class="x-table" style=" border-collapse: collapse;">
                    <tr class="x-tr1">
                        <th class="x-th" colspan="4" style="text-align:left;padding:5px 10px;">登入頁</th>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th"><em></em>背景圖</th>
                        <td class="x-td" colspan="3">
                        	<?=$dao->html('bg')?>
                        </td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th"><em></em>Logo</th>
                        <td class="x-td" colspan="3"><?=$dao->html('logo')?></td>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th" colspan="4" style="text-align:left;padding:5px 10px;">內頁</th>
                    </tr>
                    <tr class="x-tr1">
                        <th class="x-th"><em></em>Logo</th>
                        <td class="x-td" colspan="3"><?=$dao->html('logo1')?></td>
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
<?php  include '../../footer.php';?>
<script type="text/javascript">
//編輯器設定-->
var editors = new Array();
var editor_fix_path = "<?=$dao->cfg["ed"]?>";
$(document).ready(function(){
    $("#mtabs").tabs();
});
function goSubmit(){
    <?php if($useckeditor){?>
    deal_editor_to_sendajaxform();
    <?php }?>

    var errormessage = "";
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    $("#eForm1").submit();
}


$(document).ready(function(){
    $("input[name^=open_][type=checkbox]").click(function(){
        process($(this));
    });
});

process();

function process(obj){
    var sta=obj.prop('checked');
    alert(status);
}

</script>
