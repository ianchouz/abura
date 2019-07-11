<?php
include 'dao.php';
include '../header.php';
checkAuthority($menu_id);
include '../include/pagelib.php';

$dao = new contact_us();

$mod = $_POST["mod"];
if(isSet($mod)) {
    if($mod == "del") {
        $dao->delrow();
    } else if($mod == "active") {
        $dao->activerow();
    } else if($mod == "stop") {
        $dao->stoprow();
    } else if($mod == "update") {
        $dao->updaterowdata();
    }
    if($dao->action_message != null) {
        echo "<div id='__action_message' class=\"action_message\">$dao->action_message</div>";
        echo "<script>setTimeout(\"$('#__action_message').fadeOut();\",2000);</script>";
    }
}

$result = $dao->getQueryResult();
?>
<form name="QForm1" id="QForm1" method="post">
  <input type="hidden" name="mod" name="mod" value=""/>
<div class="x-panel" id="weblist">
    <div class="x-panel-header">
        <div class="page_navigation"><?=$page_navigation?></div>
    </div>
    <div class="x-panel-header">
        <div class="query_area">
            <div style="float:left;padding-top:5px;">
                &nbsp;關鍵字：<input type="text" name="qkeyvalue" size="20" value="<?=$dao->qVO->val("qkeyvalue")?>" maxLength="100"/>
                &nbsp;狀態：<select name="qinuse">
                    <option value="" <?=HSelChk("",$dao->qVO->val("qinuse"));?>>全部</option>
                    <option value="Y" <?=HSelChk("Y",$dao->qVO->val("qinuse"));?>>已讀取</option>
                    <option value="N" <?=HSelChk("N",$dao->qVO->val("qinuse"));?>>未讀取</option>
                </select>
                <div style="padding-top:5px;">
                    &nbsp;聯絡日期：<input type="text" name="qcreatedate" id="qcreatedate" size="10" value="<?=$dao->qVO->val("qcreatedate")?>" maxLength="10"/>
                </div>
            </div>
            <div style="float:left;padding-top:0px;padding-left:20px;width:80px;"><Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button></div>
            <?php if($qcateid!="" && $qcateid!="-1" && $useCate == 1) {?>
            <div style="float:left;padding-top:0px;padding-left:5px;"><Button type="button" title="不分類查詢" class="button" onclick="$('#qcateid').val('');goQuery();"><div class="btn_query">不分類查詢</div></Button></div>
            <?php }?>
        </div>
    </div>
    <div class="x-panel-body">
        <div class="list_but">
        <span class="l_ss" style="float:none;"　title="請勾選項目後再點選按鈕"></span>
        <span><Button type="button" title="刪除" class="button" onclick="runDel();"><div class="btn_del">刪除</div></Button></span>
        </div>
        <div class="clearfloat"></div>
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');">&nbsp;</div></th>
                <th class="x-th" style="width:50px;">項次</th>
                <th class="x-th" style="width:120px;">日期</th>
                <th class="x-th" style="width:100px;">訪客姓名</th>
                <th class="x-th" style="width:100px;">連絡電話</th>
                
                <th class="x-th" >電子郵件</th>
                <th class="x-th" style="width:80px;">讀取狀態</th>
            </tr>
            <?php
            for ($i=1; $i <= $pagetool->rrows; $i++){
            $item = sql_fetch_array($result);
            $vo = new contact_us();
            $vo->setItem($item);
            ?>
            <tr class="x-tr<?=($i % 2)?'1':'2'?>">
                <td class="x-td"><input type="checkbox" name="sel[]" value="<?=$vo->id?>"/></td>
                <td class="x-td"><?=$i?></td>
                <td class="x-td al"><div class="newbtn" onclick="javascript:runContactShow(<?=$vo->id?>)"><?=date("Y/m/d H:i",strtotime($vo->create_time))?></div></td>
                <td class="x-td al"><?=$vo->val("name")?><?=$vo->val("sex")?></td>
                 <td class="x-td al"><?=$vo->val("phone")?></td>
                <td class="x-td al"><a href="mailto://<?=$vo->val("mail")?>"><?=$vo->val("mail")?></a></td>
                <td class="x-td"><?=(($vo->read_status)=="Y"?"已讀取":"<font style='color:red'>未讀取</font>")?></td>
            </tr>
            <?php
            }
            ?>
        </table>
        <?php
        //無資料顯示
        if ($pagetool->rrows==0){
        echo "<div style=\"text-align:left;padding:5px;\"><font color=\"red\"><b>查無資料</b></font></div>";
        }
        ?>
    </div>
    <div class="x-panel-bbar"><?=$pagetool->builePage();?></div>
</div>
</form>
<?php  include '../footer.php';?>
<script>
$(function() {
    datasel("qcreatedate");
});
function runContactShow(id){
    var f = document.getElementById("QForm1");
    f.action="show.php?id=" + id;
    wait_msg("請稍等...");
    f.mod.value="show";
    f.submit();
}

function goQuery(){
    var f = document.getElementById("QForm1");
    var errormessage = "";
    //檢查是不是正整數
    var rowsperpage = f.rowsperpage.value;
    if (rowsperpage && rowsperpage.value !="" && !isNumber(rowsperpage)){
        errormessage +="[每頁筆數]只能為數字<br/>";
    }
    var currentpage = f.currentpage.value;
    if (currentpage && currentpage.value !="" && !isNumber(currentpage)){
        errormessage +="[頁數]只能為數字<br/>";
    }
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    f.mod.value="";
    f.submit();
}

function runDel(){
    if (getBoxVals("sel[]")==""){
        warning_msg("<font color='red'><b>請先選擇欲刪除的資料!!</b></font>");
        return false;
    }else if (!getBoxVals("sel[]")){
    }else{
        hiConfirm('您確認要刪除選取的資料!?', '確認對話', function(r) {
            if (r){
                var f = document.getElementById("QForm1");
                wait_msg("請稍等...");
                f.mod.value="del";
                f.submit();
            }else{
                return false;
            }
        });
    }
}
</script>