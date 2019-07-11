<?php
include 'dao.php';
include '../../header.php';
checkAuthority("system");
include '../../include/pagelib.php';

$pagetool = new pageTool();

$dao = new userdata();

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
    } else if($mod == "unlock") {
        $dao->unlockrow();
    }
    if($dao->action_message != null) {
        echo "<div id='__action_message' class=\"action_message\">$dao->action_message</div>";
        echo "<script>setTimeout(\"$('#__action_message').fadeOut();\",2000);</script>";
    }
}

$result = $dao->getQueryResult();

?>
<form name="QForm1" id="QForm1" method="post">
  <input type="hidden" name="mod" value=""/>
<div class="x-panel" id="weblist">
  <div class="x-panel-header">
    <div class="page_navigation"><?=$page_navigation ?>
    </div>
  </div>
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
    <div class="list_but" title="請勾選項目後再點選按鈕">
      <span class="l_ss" ></span>
      <span><Button type="button" title="刪除" class="button" onclick="runDel();"><div class="btn_del">刪除</div></Button></span>
      <span><Button type="button" title="啟用狀態" class="button" onclick="runActive();"><div class="btn_run">啟用狀態</div></Button></span>
      <span><Button type="button" title="停用狀態" class="button" onclick="runStop();"><div class="btn_stop">停用狀態</div></Button></span>
      <span><Button type="button" title="開啟鎖住帳號" class="button" onclick="runUnLock();">開啟鎖住帳號</Button></span>
      <span style="color:blue;">｜</span>
      <span><Button type="button" title="新增" class="button" onclick="runAdd();"><div class="btn_add">新增</div></Button></span>
    </div>
    <table id="x-data-list-table">
      <tr class="x-tr1">
        <th class="x-th" style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');"/></th>
        <th class="x-th" style="width:50px;">操作</th>
        <th class="x-th" style="width:50px;">項次</th>
        <th class="x-th" style="width:100px;">帳號</th>
        <th class="x-th" style="width:100px;">名稱</th>
        <th class="x-th">電子郵件</th>
        <th class="x-th" style="width:80px;">狀態</th>
        <th class="x-th"width="150">最後登入資訊</th>
      </tr>
      <?
      for ($i=1; $i <= $pagetool->rrows; $i++){
        $item = sql_fetch_array($result);
      ?>
      <tr class="x-tr<?=($i % 2)?'1':'2'?>">
        <td class="x-td"><input type="checkbox" name="sel[]" value="<?=$item['userid']?>"/></td>
        <td class="x-td">
          <input type="button" value="  " alt="編輯" title="編輯" class="l_edit" onclick="runEdit('<?=$item['userid']?>')"/>
        </td>
        <td class="x-td"><?=$i?></td>
        <td class="x-td"><?=$item['userid']?></td>
        <td class="x-td"><?=$item['username']?></td>
        <td class="x-td"><?=$item['useremail']?></td>
        <td class="x-td"><?=Html_TF($item['inuse']);?></td>
        <td class="x-td">
            <?php if($item['lastloginip2']!='') {?>
            <div style="font-size:10px;line-height:12px;"><?=$item['lastlogintime2']?> , <?=$item['lastloginip2']?></div>
            <?php }?>
            <?php if($item['lockaccount']) {?>
            <div style="color:red;font-size:10px;line-height:12px;">帳號已經於 <?=$item['locktime']?> 鎖住</div>
            <?php }?>
            <?php if($item['errortimes']>0) {?>
            <div style="color:red;font-size:10px;line-height:12px;">登入錯誤次數：<?=$item['errortimes']?></div>
            <?php }?>
        </td>
      </tr>
      <?};
      //無資料顯示
      if ($pagetool->rrows==0){
        echo "<tr class='x-tr1'><td  class='x-td' colspan=\"8\" style=\"text-align:left;Butionpadding:5px;\"><font color=\"red\"><b>查無資料</b></font></td></tr>";
      }
      ?>
    </table>

  </div>
  <div class="x-panel-bbar">
    <?=$pagetool->builePage();?>
  </div>
</div>
</form>
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
    f.action="edit.php?userid=" + idx;
    wait_msg("請稍等...");
    f.mod.value="edit";
    f.submit();
  }
  function changePID(pid){
    var f = document.getElementById("QForm1");
    f.uplevel.value=pid;
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
  function runUnLock(){
    if (getBoxVals("sel[]")==""){
      warning_msg("<font color='red'><b>請先選擇欲解鎖的資料!!</b></font>");
      return false;
    }else{
      hiConfirm('您確認要解鎖選取的資料!?', '確認對話', function(r) {
        if (r){
          var f = document.getElementById("QForm1");
          wait_msg("請稍等...");
          f.mod.value="unlock";
          f.submit();
        }else{
          return false;
        }
      });
    }
  }
</script>
