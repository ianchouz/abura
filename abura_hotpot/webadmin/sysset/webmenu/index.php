<?php
include 'dao.php';
include '../../header.php';
checkAuthority($menu_id);
include '../../include/pagelib.php';


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
$subNavigation = "";
$tmuplevel = $dao->qVO->val("quplevel");
if($tmuplevel != "-1") {
    $cidx = 0;
    while(true) {
        $tmpData = $dao->getPIDData($tmuplevel);
        if($tmpData != null) {
            if($cidx == 0) {
                $subNavigation = "<div class='div-nonbtn'> " . $tmpData[0] . "</div>";
            } else {
                $subNavigation = "<div class='div-button' onclick='changePID(" . $tmpData[2] . ")'> " . $tmpData[0] . "</div><div class='div-expand'>&nbsp;</div> " . $subNavigation;
            }
            $tmpid = $tmpData[1];
            if($tmpid == -1) {
                break;
            }
            $cidx++;
        } else {
            break;
        }
    }
    $subNavigation = "<div class='div-button' onclick='changePID(-1)'>根目錄</div><div class='div-expand'>&nbsp;</div> " . $subNavigation;
} else {
    $subNavigation = "<div class='div-nonbtn'>根目錄</div>";
    ;
}
?>
<div class="mainpanel">
  <div class="navigation"><?=$page_navigation?></div>
  <form name="QForm1" id="QForm1" method="post">
    <input type="hidden" name="mod" value=""/>
    <input type="hidden" name="quplevel" value="<?=$dao->qVO->val("quplevel");?>"/>
    <div class="x-panel">
      <div class="x-panel-header">
        <div class="query_area">
            <div style="float:left;padding-top:5px;">
                &nbsp;關鍵字：<input type="text" name="qkeyvalue" size="20" value="<?=$dao->qVO->val("qkeyvalue")?>" maxLength="100"/>
                &nbsp;狀態：<select name="qinuse">
                    <option value="" <?=HSelChk("",$dao->qVO->val("qinuse"));?>>全部</option>
                    <option value="true" <?=HSelChk("true",$dao->qVO->val("qinuse"));?>>啟用</option>
                    <option value="false" <?=HSelChk("false",$dao->qVO->val("qinuse"));?>>停用</option>
                </select>
            </div>
            <div style="float:left;padding-top:0px;padding-left:20px;width:80px;"><Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button></div>
            <?php if($qcateid!="" && $qcateid!="-1" && $useCate == 1) {?>
            <div style="float:left;padding-top:0px;padding-left:5px;"><Button type="button" title="不分類查詢" class="button" onclick="$('#qcateid').val('');goQuery();"><div class="btn_query">不分類查詢</div></Button></div>
            <?php }?>
        </div>
      </div>
      <div class="x-panel-body">
        <div class="div-navigation"><?=$subNavigation?></div>
        <div class="clearfloat"></div>
        <div class="list_but">
          <span class="l_ss" style="float:none;"　title="請勾選項目後再點選按鈕"></span>
          <span><Button type="button" title="刪除" class="button" onclick="runDel();"><div class="btn_del">刪除</div></Button></span>
          <span><Button type="button" title="啟用狀態" class="button" onclick="runActive();"><div class="btn_run">啟用狀態</div></Button></span>
          <span><Button type="button" title="停用狀態" class="button" onclick="runStop();"><div class="btn_stop">停用狀態</div></Button></span>
          <span><Button type="button" title="更新編號" class="button" onclick="runUpdate();"><div class="btn_update">更新編號</div></Button></span>
          <span style="color:blue;">｜</span>
          <span><Button type="button" title="新增" class="button" onclick="runAdd();"><div class="btn_add">新增</div></Button></span>
        </div>
        <table id="x-data-list-table">
          <tr class="x-tr1">
            <th class="x-th" style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');"/></th>
            <th class="x-th" style="width:50px;">操作</th>
            <th class="x-th" style="width:50px;">項次</th>
            <th class="x-th" style="width:100px;">編號</th>
            <th class="x-th" >名稱</th>
            <th class="x-th" style="width:80px;">是否顯示</th>
            <th class="x-th" style="width:80px;">權限設定</th>
            <th class="x-th" style="width:80px;">網頁設定</th>
            <th class="x-th" style="width:120px;">代號</th>
            <th class="x-th" style="width:80px;">狀態</th>
          </tr>
          <?
          for ($i=1; $i <= $pagetool->rrows; $i++){
            $item = sql_fetch_array($result);
            $rowsubcc = $dao->countSubCategory($item['id']);
          ?>
          <tr class="x-tr<?=($i % 2)?'1':'2'?>">
            <td class="x-td"><input type="checkbox" name="sel[]" value="<?=$item['id']?>" id="sel_<?=$item['id']?>"/></td>
            <td class="x-td">
              <input type="button" value="  " alt="編輯" title="編輯" class="l_edit" onclick="runEdit('<?=$item['id']?>')"/>
              <input type="button" value="  " alt="複製" title="複製" class="l_copy" onclick="runCopy('<?=$item['id']?>')"/>
            </td>
            <td class="x-td"><?=$i?></td>
            <td class="x-td">
              <input type="text" name="seq_<?=$item['id']?>" value="<?=$item['seq']?>" size="5" maxLength="5" onchange="setSel('sel_<?=$item['id']?>')"/>
            </td>
            <td class="x-td" style="text-align:left;">
              <? if ($item['leaf']==true){
                echo "<div class=\"leaf\">".$item['text']."</div>";
              }else{
                if ($rowsubcc==0){
                  echo "<div class=\"folder\">".$item['text']."</div>";
                }else{
                  echo "<div class=\"folder folder-btn\" onclick=changePID('".$item['id']."')>".$item['text']."<span class='numshow'>(".$rowsubcc.")</span></div>";
                }
              }?>
            </td>
            <td class="x-td"><?=$item['hidden']?"否":"是"?></td>
            <td class="x-td"><?=$item['authority']?"是":"否"?></td>
            <td class="x-td"><?=$item['htmlset']?"是":"否"?></td>
            <td class="x-td"><?=$item['keyname']?></td>
            <td class="x-td"><?=Html_TF($item['inuse']);?></td>
          </tr>
          <?php }?>
        </table>
    <?
      //無資料顯示
      if ($pagetool->rrows==0){
        echo "<div style=\"text-align:left;padding:5px;\"><font color=\"red\"><b>查無資料</b></font></div>";
      }
    ?>
      </div>
      <div class="x-panel-bbar">
        <?=$pagetool->builePage();?>
      </div>
    </div>
  </form>
</div>
<?php  include '../footer.php';?>
<script>
function runAdd(){
    var f = document.getElementById("QForm1");
    f.action="edit.php";
    wait_msg("請稍等...");
    f.mod.value="add";
    f.submit();
}
function runEdit(idx){
    var f = document.getElementById("QForm1");
    f.action="edit.php?id=" + idx;
    wait_msg("請稍等...");
    f.mod.value="edit";
    f.submit();
}
function runCopy(idx){
    var f = document.getElementById("QForm1");
    f.action="edit.php?id=" + idx;
    wait_msg("請稍等...");
    f.mod.value="copy";
    f.submit();
}
function changePID(pid){
    var f = document.getElementById("QForm1");
    f.quplevel.value=pid;
    goQuery();
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

function runUpdate(){
    if (getBoxVals("sel[]")==""){
      warning_msg("<font color='red'><b>請先選擇欲更新編號的資料!!</b></font>");
      return false;
    }else if (!getBoxVals("sel[]")){
    }else{
        hiConfirm('您確認要更新選取的資料編號!?', '確認對話', function(r) {
            if (r){
                var f = document.getElementById("QForm1");
                wait_msg("請稍等...");
                f.mod.value="update";
                f.submit();              
            }else{
                  return false;
            }
        });
    }
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

function runActive(){
    if (getBoxVals("sel[]")==""){
        warning_msg("<font color='red'><b>請先選擇欲啟用的資料!!</b></font>");
        return false;
    }else if (!getBoxVals("sel[]")){
    }else{
        hiConfirm('您確認要啟用選取的資料!?', '確認對話', function(r) {
            if (r){
              var f = document.getElementById("QForm1");
              wait_msg("請稍等...");
              f.mod.value="active";
              f.submit();              
            }else{
                  return false;
            }
        });
    }
}

function runStop(){
    if (getBoxVals("sel[]")==""){
        warning_msg("<font color='red'><b>請先選擇欲停用的資料!!</b></font>");
        return false;
    }else if (!getBoxVals("sel[]")){
    }else{
        hiConfirm('您確認要停用選取的資料!?', '確認對話', function(r) {
            if (r){
                var f = document.getElementById("QForm1");
                wait_msg("請稍等...");
                f.mod.value="stop";
                f.submit();                  
            }else{
                  return false;
            }
        });
    }
}
function runCopy(idx){
    var f = document.getElementById("QForm1");
    f.action="edit.php?id=" + idx;
    wait_msg("請稍等...");
    f.mod.value="copy";
    f.submit();
}

function openWin(url){
    window.open(url);
}
</script>