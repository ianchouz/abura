<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../header.php';
checkAuthority($menu_id);

//讀取查詢條件
$dao->setQueryDefault();
echo $dao->qVO->bulidFrom('qFrom1', 'main.php');

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
    $dao->dbrow["seq"] = $dao->getMexSeq();
    $seqmemo = "(系統自動取得最大編號)";
} else {
    $headstr = "-新增";
    $dao->dbrow["cateId"] = $dao->qVO->val("qcateid");
    $dao->dbrow["seq"] = $dao->getMexSeq();
    $seqmemo = "(系統自動取得最大編號)";
}
?>


<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
<input type="hidden" name="mod" value="<?=$mod;?>"/>
<input type="hidden" name="active" value="run"/>
<input type="hidden" name="mainid" value="<?=$_REQUEST["mainid"]?>"/>
<input type="hidden" name="id" value="<?=$dao->dbrow["id"]?>"/>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header"><?=$page_navigation.$headstr?></div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            <div class="btn_panel"><Button type="button" title="取消" class="button" onclick="goBack();"><div class="btn_cancel">取消</div></Button></div>
        </div>
    </div>
    <div class="x-panel-body">
        <table class="x-table" style=" border-collapse: collapse;">
            <tr class="x-tr1"><th class="x-th">發佈日期</th><td class="x-td"><?=$dao->html('createdate', 'text_date')?></td></tr>
            <tr class="x-tr2">
                <th class="x-th"><em>*</em>上架期間</th>
                <td class="x-td">
                    <div style="float:left">
                        <div style="float:left;padding-left:3px;"><input type="radio" name="publishtype" id="publishtype1" value="A" <?php if(($dao->dbrow["publishtype"]=="A")) echo "checked";?>> 永久 </div>
                        <div style="float:left;padding-left:3px;"><input type="radio" name="publishtype" id="publishtype2" value="P" <?php if(($dao->dbrow["publishtype"]=="P")) echo "checked";?>> 暫停 </div>
                        <div style="float:left;padding-left:3px;"><input type="radio" name="publishtype" id="publishtype3" value="D" <?php if(($dao->dbrow["publishtype"]=="D")) echo "checked";?>> 指定 </div>
                        <div style="float:left;padding-left:3px;"><?=$dao->html('startdate', 'text_date')?>  </div>
                        <div style="float:left;padding-left:3px;">至</div>
                        <div style="float:left;padding-left:3px;"><?=$dao->html('stopdate', 'text_date')?>  </div>
                    </div>
                </td>
            </tr>
            <?php if($useCate == 1){?>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>所屬分類</th>
                <td class="x-td" colspan="3">
                    <select id="cateId" name="cateId"><?php
                    $cc = 0;
                    $dao->loadCategoryList();
                    foreach ($dao->categoryList as $item) {
                        $isseled = ($dao->dbrow["cateId"] == $item['id'])?" selected" : "";
                        echo "<option id='pid".$cc."' value='".$item['id']."' ".$isseled."/>&nbsp;".$item['fullname']."</option>";
                        $cc++;
                    }?></select>
                </td>
            </tr>
            <?php }?>
            <tr class="x-tr2"><th class="x-th">編號</th><td class="x-td"><input type="text" name="seq" id="seq" size="6" maxLength="5" value="<?=$dao->dbrow["seq"]?>"> <?=$seqmemo;?></td></tr>
            <!--  <tr class="x-tr1" >
                <th class="x-th">設定為</th>
                <td class="x-td">
                <?=$dao->html('showindex', 'checkbox')?> 首頁文章(最多6則，以排序高為優先順序)<br>
                <?=$dao->html('showtop', 'checkbox')?> 列表置頂(最多1則，以排序高為優先順序)
                </td>
            </tr> -->
             <tr class="x-tr1" >
                <th class="x-th">設定為</th>
                <td class="x-td">
                <?=$dao->html('latest', 'checkbox')?> 最新(可多則，以排序高為優先順序)
                </td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>標題</th>
                <td class="x-td"><?=$dao->html('title')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th">副標</th>
                <td class="x-td"><?=$dao->html('summary')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th">列表圖</th>
                <td class="x-td" colspan="3"><?=$dao->html('cover')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th"><em>*</em>內容</th>
                <td class="x-td"><?=$dao->html('content')?></td>
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
<!--編輯器設定-->
var editors = new Array();
var editor_fix_path = "<?=$dao->cfg["ed"]?>";

$(document).ready(function(){
    $("#mtabs").tabs();
});

function goSubmit(){
    $("#eForm1").submit();
}
$('#eForm1').submit(function(event) {
    event.preventDefault();
    <?php if($useckeditor){?>
    deal_editor_to_sendajaxform();
    <?php }?>

    var errormessage = "";
    if ($("form[name='eForm1'] select[name=cateId]").val()==""){
        errormessage +="請選擇分類<br/>";
    }


    if ($('#title').val()==""){
        errormessage +="[標題]不可以空白<br/>";
    }
    if ($("#content").val() =="" && $('#webURL').val()==""){
        errormessage +="[網址]或[內容]請擇一填寫!!<br/>";
    }

    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");

    $(this).ajaxSubmit({url: "action.php",
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
