<?php
include 'dao.php';
$useckeditor = true;
include '../header.php';
//讀取查詢條件
$querypars = new QueryParames();
echo $querypars->bulidFrom('qFrom1', 'index.php');
$headstr = "";
$id = pgParam("id", null);
$sql = "SELECT * FROM " . $CFG->tbext . "contact_us WHERE id=" . $id;
$result = @sql_query($sql);
$item = @sql_fetch_array($result);
if($item['read_status'] <> 'Y') {
    $strSQL = "UPDATE " . $CFG->tbext . "contact_us SET " . " read_status='Y'" . " WHERE id=" . pSQLStr($id);
    $result = @sql_query($strSQL);
}
?>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header"><?=$page_navigation?></div>
    <div class="x-panel-body">
        <div>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="返回" class="button" onclick="goBack();"><div class="btn_cancel">返回</div></Button></div>
                </div>
            </div>
        </div>
        <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
            <ul>
                <li><a href='form_list.php?id=<?=$item["id"]?>' style='font-size:12px;padding:5px 5px;'>聯絡內容</a></li>
                <li><a href='form_replylist.php?id=<?=$item["id"]?>' style='font-size:12px;padding:5px 5px;'>回覆記錄</a></li>
                <li><a href='form_reply.php?id=<?=$item["id"]?>' style='font-size:12px;padding:5px 5px;'>回覆郵件</a></li>
            </ul>
        </div>
    </div>
</div>
<?php  include '../footer.php';?>
<script>
$(function() {
    $tabs = $("#mtabs").tabs({
        fx: [{opacity: 'toggle'}, { height: 'show' }],
        spinner: '<img src="../images/loading.gif">',
        select: function(e, ui) {
            //allClearEditor();
        },
        show: function(e, ui) {
            var thistab = ui;
            currentTab="#"+ui.panel.id;
            switch (thistab.index){
                case 1: //客服記錄
                    //loadQA_detail();
                    break;
                case 2: //寄發email
                    loadReSend();
                    break;
                default:
                    break;
            }
        }
    });
});
var editor_mailcontent = null;
function loadReSend(){
    var instance = CKEDITOR.instances['mailcontent'];
    if(instance){
      CKEDITOR.remove(instance);
    }
    editor_mailcontent = CKEDITOR.replace('mailcontent',{height:200} );
}
</script>
<script type="text/javascript">
function reSend(msg){
    wait_msg("<div style='margin:5px;'>" + msg +"<br>重新載入頁面中!!</div>");
    location.replace("show.php?id=<?=$item["id"]?>");
}
function goBack(){
    transPageInfoForm('','qFrom1');
}
</script>