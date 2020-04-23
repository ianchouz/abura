<?php
include 'cate_dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../header.php';

checkAuthority($menu_id);

//讀取查詢條件
$dao->setQueryDefault();
echo $dao->qVO->bulidFrom('qFrom1', 'index.php');

$headstr = "";
$mod = pgParam("mod", "");
$id = pgParam("id", "");

if(!empty($id) && empty($mod)) {
    $mod = "edit";
}

if($mod == "edit") {
    $headstr = "-修改";
    $dao->load();
    if(!isset($dao->dbrow)) {
        die("無法找到此筆資料!!");
    }
} else if($mod == "copy") {
    $headstr = "-複製新增";
    $dao->load();
    $dao->dbrow["pid"] = $dao->qVO->val("qcateid");
    $seqmemo = "(系統自動取得最大編號)";
} else {
    $headstr = "-新增";
    $dao->pid = $dao->qVO->val("qcateid");
    $dao->dbrow["pid"] = $dao->pid;
    $seqmemo = "(系統自動取得最大編號)";
}
//取得所在層級
$deep_now = $dao->getTreeDeep($dao->dbrow["pid"]);
//自訂欄位
$Fields=new Fields;
?>
<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
  <input type="hidden" id="fieidsel" value="<?=($_SESSION['authority']=="all"?1:1)?>"><?// 不開放填 0?>
  <input type="hidden" name="fields_set" id="fields_set" value="<?=$dao->dbrow["fields_set"]?>"/>
<input type="hidden" name="mod" value="<?=$mod;?>"/>
<input type="hidden" name="active" value="run"/>
<input type="hidden" name="id" value="<?=$dao->dbrow["id"]?>"/>
<input type="hidden" name="leaf" id="leaf" value="true"/>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header"><?=$page_navigation."-分類".$headstr?></div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            <div class="btn_panel"><Button type="button" title="取消" class="button" onclick="goBack();"><div class="btn_cancel">取消</div></Button></div>
        </div>
    </div>
    <div class="x-panel-body">
        <table class="x-table" style=" border-collapse: collapse;">

            <?php if($dao->use_tree) {//多層分類?>
            <tr class="x-tr1" >
                <th class="x-th"><em>*</em>所屬分類</th>
                <td class="x-td" colspan="3">
                    <select id="pid" name="pid" class="act-select"><?php
                    $cc = 0;
                    $dao->loadCategoryList();
                    foreach ($dao->categoryList as $item) {
                        $isseled = ($dao->dbrow["pid"] == $item['id'])?" selected" : "";
                        echo "<option id='pid".$cc."' value='".$item['id']."' ".$isseled." data-layer=".$item['layer']." >&nbsp;".$item['fullname']."</option>";
                        $cc++;
                    }?></select>
                </td>
            </tr>
            <?php }?>
            <?php if($deep_now < $dao->tree_layer) {//層數控制?>
            <tr class="x-tr2">
                <th class="x-th"><em>*</em>分類型態</th>
                <td class="x-td" colspan="3">
                    <input type="radio" name="leaf" value="false" <?php if(!$dao->dbrow["leaf"]) echo "checked";?> /> 分類目錄
                    <input type="radio" name="leaf" value="true" <?php if($dao->dbrow["leaf"]) echo "checked";?>  /> 資料目錄
                </td>
            </tr>
            <?php }?>
            <tr class="x-tr2"><th class="x-th">編號</th><td class="x-td" colspan="3"><input type="text" name="seq" id="seq" size="6" maxLength="5" value="<?=$dao->dbrow["seq"]?>"> <?=$seqmemo;?></td></tr>
            <tr class="x-tr2" colspan="3">
                <th class="x-th"><em>*</em>分類狀態</th>
                <td class="x-td" colspan="3">
                    <input type="radio" name="inuse" value="1" <?php if($dao->dbrow["inuse"]=="1") echo "checked";?>/> 啟用
                    <input type="radio" name="inuse" value="0" <?php if($dao->dbrow["inuse"]=="0") echo "checked";?>/> 停用
                </td>
            </tr>
            <?php if($deep_now==1) {?>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>分類名稱</th>
                <td class="x-td" colspan="3"><?=$dao->html('catename')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>代表圖</th>
                <td class="x-td">
                    <table class="x-table" style=" border-collapse: collapse;">
                        <tr class="x-tr1" valign="top">
                            <?php for($i=1;$i<=5;$i++) {?>
                            <td>
                                <table class="x-table" style=" border-collapse: collapse;">
                                    <tr class="x-tr1">
                                        <th class="x-th" style="text-align:left;">圖片 <?=$i?></th>
                                    </tr>
                                    <tr class="x-tr1">
                                        <td class="x-td"><?=$dao->html('cover'.$i)?></td>
                                    </tr>
                                </table>
                            </td>
                            <?php }?>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php } else {?>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>分類名稱</th>
                <td class="x-td" colspan="3"><?=$dao->html('catename')?></td>
            </tr>
            <?php }?>
        </table>
    </div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            <div class="btn_panel"><Button type="button" title="取消" class="button" onclick="goBack();"><div class="btn_cancel">取消</div></Button></div>
        </div>
    </div>
</div>
</form>
<?php  include '../footer.php';?>
<script type="text/javascript">
//typ-summary typ-cover typ-banner
<!--編輯器設定-->
var editors = new Array();
var editor_fix_path = "<?=$dao->cfg["ed"]?>";

// $(document).ready(function(){
//     // $("#mtabs").tabs();
//     process();
//     $("#pid").change(function(){
// // process();
//
//     });
//
// });
function process(){
    var now=$("#pid option:selected").attr("data-layer");
    $("tr[class*='typ-']").hide();
    $("input[type=radio][value=false]").prop('checked', true);
    if(now==0){
        $(".typ-banner").show();
    }
    if(now==1){
        $(".typ-summary").show();
    }
    if(now==2){

    }
    if(now==3){
        $(".typ-summary").show();
        $(".typ-app").show();
        $(".typ-fields").show();
        $(".typ-cover").show();
        $("input[type=radio][value=true]").prop('checked', true);
    }


}

function chk_leaf(){
    // var leaf =$("input[name='leaf']:checked").val();
    // if( typeof(leaf) == "undefined"){
    //     $("input[name='leaf']").attr("checked",'true');
    //     leaf = 'true';
    // }
    // if(leaf=='true'){
    //     $(".cate").hide();
    // }else{
    //     $(".cate").show();
    // }
}

function goSubmit(){
    $("#eForm1").submit();
}
$('#eForm1').submit(function(event) {
    event.preventDefault();
    <?php if($useckeditor){?>
    deal_editor_to_sendajaxform();
    <?php }?>

    var errormessage = "";
    if ($('#catename').val()==""){
        errormessage +="[分類名稱]不可以空白<br/>";
    }

    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");

    var qtype="";
    $("input[name^='usefileds'][type=checkbox]:checked").each(function(indx){
        qtype += (qtype==""?"":",")+$(this).val();
    });
    if($("#fieidsel").val()==1)$("#fields_set").val(qtype);

    var app="";
    $("input[name^='ttapp'][type=checkbox]:checked").each(function(indx){
        app += ";"+$(this).val()+";";

    });
    $("#application").val(app);

    $(this).ajaxSubmit({url: "cate_action.php",
        type: 'POST',
        dataType: 'json',
        timeout: 5000,
        error: function(){warning_msg('發生錯誤,回傳資料無法解析');},
        success: function(json) {
            var showmessage = "";
            if (json.success=="true" || json.success=="1"){
                transPageInfoForm('作業成功!!','qFrom1');
            }else{
                warning_msg("<font color='red'><b>送出失敗:</b></font><br>" + json.message);
            }
        }
    });
});
</script>
