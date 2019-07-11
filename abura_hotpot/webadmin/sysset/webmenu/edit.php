<?php
include_once("dao.php");
include_once("../../header.php");
checkAuthority($menu_id);
include_once("../../include/QueryParames.php");

$headstr = "";
//讀取查詢條件
$dao->setQueryDefault();
echo $dao->qVO->bulidFrom('qFrom1', 'index.php');

$mod = pgParam("mod", "");
$id = pgParam("id", "");

if(!empty($id) && $mod == "") {
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
    $dao->uplevel = $dao->qVO->val("quplevel");
    $dao->dbrow["uplevel"] = $dao->uplevel;
    $dao->dbrow["seq"] = $dao->getMexSeq();
    $seqmemo = "(系統自動取得最大編號)";
} else {
    $headstr = "-新增";
    $dao->uplevel = $dao->qVO->val("quplevel");
    $dao->dbrow["uplevel"] = $dao->uplevel;
    $dao->dbrow["seq"] = $dao->getMexSeq();
    $seqmemo = "(系統自動取得最大編號)";
}

$dao->loadCategoryList();
?>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header">
        <?=$page_navigation.$headstr?>
    </div>
    <div class="x-panel-body">
        <form name="eForm1" id="eForm1" action="edit.php" method="post">
            <input type="hidden" name="mod" value="<?=$mod;?>"/>
            <input type="hidden" name="active" value="run"/>
            <input type="hidden" name="id" value="<?=$dao->dbrow["id"];?>"/>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th"><em>*</em>父目錄</th>
                    <td class="x-td">
                        <select name="uplevel" id="uplevel">
                            <? foreach ($dao->categoryList as $item) {
                            $isseled = ($dao->dbrow["uplevel"] == $item['id'])?" selected" : "";
                            echo "<option value='" .$item['id']. "' ".$isseled.">".$item['text']."</option>";
                            }?>
                        </select>
                    </td>
                </tr>
                <tr class="x-tr1"><th class="x-th">排序編號</th><td class="x-td"><input type="text" name="seq" id="seq" size="6" maxLength="5" value="<?=$dao->dbrow["seq"]?>"> <?=$seqmemo;?></td></tr>
                <tr class="x-tr1">
                    <th class="x-th"><em>*</em>標題</th>
                    <td class="x-td"><input type="text" name="text" id="text" size="40" maxLength="100" value="<?=$dao->dbrow["text"]?>"></td>
                </tr>
                <tr class="x-tr2">
                    <th class="x-th"><em>*</em>代號</th>
                    <td class="x-td">
                        <input type="text" name="keyname" id="keyname" size="40" maxLength="100" value="<?=$dao->dbrow["keyname"]?>"/>
                    </td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th"><em>*</em>網址</th>
                    <td class="x-td"><input type="text" name="url" id="url" size="40" maxLength="100" value="<?=$dao->dbrow["url"]?>"></td>
                </tr>
                <tr class="x-tr1"><th class="x-th"><em>*</em>是否為目錄</th><td class="x-td"><input type="radio" name="leaf" value="true" <?php if($dao->dbrow["leaf"]) echo "checked";?>/> 不是 <input type="radio" name="leaf" value="false" <?php if(!($dao->dbrow["leaf"])) echo "checked";?>/> 是</td></tr>
                <tr class="x-tr1"><th class="x-th"><em>*</em>狀態</th><td class="x-td"><input type="radio" name="inuse" value="true" <?php if($dao->dbrow["inuse"]) echo "checked";?>/> 啟用 <input type="radio" name="inuse" value="false" <?php if(!($dao->dbrow["inuse"])) echo "checked";?>/> 停用</td></tr>
                <tr class="x-tr1"><th class="x-th"><em>*</em>是否顯示</th><td class="x-td"><input type="radio" name="hidden" value="false" <?php if(!($dao->dbrow["hidden"])) echo "checked";?>/> 顯示 <input type="radio" name="hidden" value="true" <?php if($dao->dbrow["hidden"]) echo "checked";?>/> 不顯示</td></tr>
                <tr class="x-tr1"><th class="x-th"><em>*</em>權限設定</th><td class="x-td"><input type="radio" name="authority" value="true" <?php if($dao->dbrow["authority"]) echo "checked";?>/> 提供 <input type="radio" name="authority" value="false" <?php if(!($dao->dbrow["authority"])) echo "checked";?>/> 不提供</td></tr>
                <tr class="x-tr1"><th class="x-th"><em>*</em>網頁設定</th><td class="x-td"><input type="radio" name="htmlset" value="true" <?php if($dao->dbrow["htmlset"]) echo "checked";?>/> 提供 <input type="radio" name="htmlset" value="false" <?php if(!($dao->dbrow["htmlset"])) echo "checked";?>/> 不提供</td></tr>
            </table>
        </form>
    </div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
            <div class="btn_panel"><Button type="button" title="取消" class="button" onclick="goBack();"><div class="btn_cancel">取消</div></Button></div>
        </div>
    </div>
</div>
<?php  include('../../footer.php');?>
<script type="text/javascript">
function goSubmit(){
    var errormessage = "";        
    if ($('#text').val()==""){
        errormessage +="[標題]不可以空白<br/>";
    }
    if ($('#keyname').val()==""){
        errormessage +="[代號]不可以空白<br/>";
    }
    if ($('#url').val()==""){
        errormessage +="[網址]不可以空白<br/>";
    }
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    $.post(
        "action.php",
        $("#eForm1").serialize(),
        function(data){
            var showmessage = "";
            if (data.success=="true" || data.success=="1"){
                transPageInfoForm('作業成功!!','qFrom1');
            }else{
                warning_msg("<font color='red'><b>送出失敗:</b></font><br>" + data.message);
            }
        },
        "json"
    );
}
function goBack(){
    transPageInfoForm('','qFrom1');
}
</script>