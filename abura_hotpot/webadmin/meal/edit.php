<?php
include 'dao.php';
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

            <tr class="x-tr1">
              <th class="x-th"><em>*</em>名稱</th>
              <td class="x-td" colspan="3"><?=$dao->html('title')?></td>
            </tr>
            <tr class="x-tr1">
              <th class="x-th"><em>*</em>名稱圖</th>
              <td class="x-td" colspan="3"><?=$dao->html('cover')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th">類型</th>
                <td class="x-td" colspan="3"><?=$dao->html('type')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th">價格</th>
                <td class="x-td" colspan="3"><?=$dao->html('price')?></td>
            </tr>
        </table>
        <table class="x-table" style=" border-collapse: collapse;">
            <tr class="x-tr1" valign="top">
                <th class="x-th"> </th>
                <?php for($i=1;$i<=5;$i++) {?>
                <td>
                    <table class="x-table" style=" border-collapse: collapse;">
                        <tr class="x-tr1">
                            <th class="x-th">圖片 <?=$i?></th>
                        </tr>
                        <tr class="x-tr1">
                            <td class="x-td"><?=$dao->html('cover'.$i)?></td>
                        </tr>
                    </table>
                </td>
                <?php }?>
            </tr>
        </table>
        <table class="x-table" style=" border-collapse: collapse;">
            <tr class="x-tr1">
                <th class="x-th">第一項</th>
                <td class="x-td" colspan="3"><?=$dao->html('broth')?></td>
            </tr>
            <tr class="x-tr1">
                <th class="x-th">菜單</th>
                <td class="x-td" colspan="3">
                    <table class="x-table" style=" border-collapse: collapse;">
                        <tr class="x-tr1" valign="top">
                            <?php for($i=1;$i<=3;$i++) {?>
                            <td><?=$dao->html('broth_items'.$i)?></td>
                            <?php }?>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- 動態條列工具 -->
        <style type="text/css">
        .btnList{
          width:100%;
          height:20px;
          float:left;
          margin:10px 0;
        }
        .btnList button{
          margin-right: 10px;
        }
        </style>
        <?php
        $subkey= 'stand1';
        ?>
        <table class="x-table" style=" border-collapse: collapse;">
            <tr class="x-tr1">
                <th class="x-th" colspan="5" style="text-align:left;">菜單</th>
            </tr>
            <tr class="x-tr1">
                <th class="x-th"></th>
                <td class="x-td modifyArea" colspan="3">
                    <div class="btnList">
                        <div class="btn_panel"><Button type="button" title="新增列" class="button" ><div class="btn_add">新增列</div></Button></div>
                        <div class="btn_panel"><Button type="button" title="刪除" class="button" ><div class="btn_del">刪除</div></Button></div>
                    </div>
                    <table class="x-table modifyTable" style=" border-collapse: collapse;">
                        <tr class="x-tr1">
                            <th class="x-th" style="width:50px;text-align:center;" >全選 <input type="checkbox" name="" class="ckb_list" value="ckbox"></th>
                            <th class="x-th" style="text-align:center;">項目</th>
                            <th class="x-th" style="text-align:center;">菜色</th>
                            <th class="x-th" style="text-align:center;"></th>
                        </tr>
                        <tr class="templeteRow listRow" valign="top">
                            <td align="center"><input type="checkbox" name="ckbox[]" value=""></td>
                            <td><input type="text" name="subs[<?=$subkey?>][column1][]" value="" class="input_full_max" /></td>
                            <td><textarea name="subs[<?=$subkey?>][column2][]" class="textarea_full"></textarea></td>
                            <!-- <td><input type="checkbox" name="subs[<?=$subkey?>][column3][]" value="1" checked />二選一</td> -->
                        </tr>
                        <?php
                        $sql = "SELECT * FROM ".$CFG->tbext."product_rel where typeid='$id' AND subkey='$subkey' order by id";
                        $rs = @sql_query($sql);
                        while ($item = @sql_fetch_assoc($rs)) {
                        ?>
                        <tr class="listRow" valign="top">
                            <td align="center"><input type="checkbox" name="ckbox[]" ></td>
                            <td><input type="text" name="subs[<?=$subkey?>][column1][]" value="<?=htmlentities($item['column1'], ENT_QUOTES, "UTF-8");?>" class="input_full_max" /></td>
                            <td><textarea name="subs[<?=$subkey?>][column2][]" class="textarea_full"><?=htmlentities($item['column2'], ENT_QUOTES, "UTF-8");?></textarea></td>
                            <!-- <td><input type="checkbox" name="subs[<?=$subkey?>][column3][]" value="1" <?=!empty($item['column3']) ? 'checked' : ''?> />二選一</td> -->
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        <table class="x-table" style=" border-collapse: collapse;">
            <tr class="x-tr2">
                <th class="x-th"><em>*</em>狀態</th>
                <td class="x-td" colspan="3">
                    <input type="radio" name="inuse" value="1" <?php if($dao->dbrow["inuse"]=="1") echo "checked";?>/> 啟用
                    <input type="radio" name="inuse" value="0" <?php if($dao->dbrow["inuse"]=="0") echo "checked";?>/> 停用
                </td>
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
    if ($('input[name=title]').val()==""){
        errormessage +="請輸入名稱<br/>";
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


// 動態條列工具
$(document).ready(function(){
    var templeteRow=$(".modifyTable").find(".templeteRow");
    templeteRow.hide();
    $(".btn_add").parent().on("click",function(){
        var _parent = $(this).parents('.modifyArea');
        var newRow = _parent.find(".templeteRow").clone(true).removeClass("templeteRow").show();
        _parent.find(".modifyTable ").append(newRow);
    });
    $(".btn_del").parent().on("click",function(){
        $('input[name^=ckbox]:checked').each(function(indx){
           $(this).parents(".listRow").remove();
        });

    });
});
</script>
