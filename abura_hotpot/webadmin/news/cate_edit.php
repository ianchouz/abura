<?php
include 'cate_dao.php';
$useimgbrowser = true;
$useckeditor = false;
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
?>
<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
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
                        echo "<option id='pid".$cc."' value='".$item['id']."' ".$isseled."/>&nbsp;".$item['fullname']."</option>";
                        $cc++;
                    }?></select>
                </td>
            </tr>
            <?php }?>
            <?php if($deep_now < $dao->tree_layer) {//層數控制?>
            <tr class="x-tr2">
                <th class="x-th"><em>*</em>分類型態</th>
                <td class="x-td">
                    <input type="radio" name="leaf" value="false" <?php if(!$dao->dbrow["leaf"]) echo "checked";?> onclick="chk_leaf()"/> 分類目錄
                    <input type="radio" name="leaf" value="true" <?php if($dao->dbrow["leaf"]) echo "checked";?>  onclick="chk_leaf()"/> 資料目錄
                </td>
            </tr>
            <?php }?>
            <tr class="x-tr2"><th class="x-th">編號</th><td class="x-td"><input type="text" name="seq" id="seq" size="6" maxLength="5" value="<?=$dao->dbrow["seq"]?>"> <?=$seqmemo;?></td></tr>
           
            <tbody class="cate" <?=($dao->dbrow["leaf"])?'style="display:none;"':''?>>
            <?php if($dao->_xmls['cover']) {// 預設一組?>
            <tr class="x-tr1">
                <th class="x-th">列表圖</th>
                <td class="x-td" colspan="3"><?=$dao->html('cover')?></td>
            </tr>
            <?php }?>
            <?php if($dao->_xmls['filename1']) {// 預設一組?>
            <tr class="x-tr1">
                <th class="x-th">上傳檔案</th>
                <td class="x-td"><?=$dao->html('filename1')?></td>
            </tr>
            <?php }?>
            </tbody>
            <tr class="x-tr2">
                <th class="x-th"><em>*</em>分類狀態</th>
                <td class="x-td" colspan="3">
                    <input type="radio" name="inuse" value="1" <?php if($dao->dbrow["inuse"]=="1") echo "checked";?>/> 啟用
                    <input type="radio" name="inuse" value="0" <?php if($dao->dbrow["inuse"]=="0") echo "checked";?>/> 停用
                </td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>分類名稱</th>
                <td class="x-td"><?=$dao->html('catename')?></td>
            </tr>
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
$(document).ready(function(){
    $("#mtabs").tabs();
});

<!--編輯器設定-->
var editors = new Array();
var editor_fix_path = "<?=$dao->cfg["ed"]?>";

$(document).ready(function(){
});

function chk_leaf(){
    var leaf =$("input[name='leaf']:checked").val();
    if( typeof(leaf) == "undefined"){
        $("input[name='leaf']").attr("checked",'true');
        leaf = 'true';
    }
    if(leaf=='true'){
        $(".cate").hide();
    }else{
        $(".cate").show();
    }
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
