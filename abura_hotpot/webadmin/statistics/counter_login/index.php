<?php
include 'dao.php';
include '../../header.php';
checkAuthority("statistics");
include '../../include/pagelib.php';
$pagetool = new pageTool();
$mod = $_POST["mod"];
if(isSet($mod)) {
    $counter_login = new counter_login();
    if($mod == "del") {
        $counter_login->delrow();
    }
    if($counter_login->action_message != null) {
        echo "<div id='__action_message' class=\"action_message\">$counter_login->action_message</div>";
        echo "<script>setTimeout(\"$('#__action_message').fadeOut();\",2000);</script>";
    }
}
//取得查詢條件
$querypars = new QueryParames();
$sql = "SELECT * FROM " . $CFG->tbext . "counter_login where 1=1 ";
if($querypars->logintime != "") {
    $sql .= " and logintime like '" . $querypars->logintime . "%'";
}
if($querypars->userid != "") {
    $sql .= " and userid ='" . $querypars->userid . "'";
}
$tmpsql = "select topadmin from " . $CFG->tbuserext . "userdata where inuse=true and userid='" . $_SESSION['sess_uid'] . "'";
$subquery = sql_query($tmpsql) or trigger_error("SQL", E_USER_ERROR);
$r = sql_fetch_row($subquery);
if(!$r[0]) {
    $sql .= " and userid not in ('Project','Maintain1','Maintain2')";
}
$sql .= " ORDER BY logintime desc";
$result = $pagetool->excute($sql);
?>
<? if ($dao->action_message != null){ echo "$dao->action_message";}?>
<form name="QForm1" id="QForm1" method="post">
  <input type="hidden" name="mod" name="mod" value=""/>
<div class="x-panel" id="weblist">
  <div class="x-panel-header">
    <div class="page_navigation"><?=$page_navigation?></div>
  </div>
  <div class="x-panel-header">
    <div class="query_area">
      <table border="0" width="100%">
        <tr>
          <td valign="top" style="width:370px;">
            使用者帳號：<input type="text" size="10" maxLength="100" name="userid" id="userid" value="<?=$querypars->userid?>"/>&nbsp;
            &nbsp;登入日期：<input type="text" size="12" maxLength="10" name="logintime" id="logintime" value="<?=$querypars->logintime?>">
          </td>
          <td valign="top">
            <Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button>
          </td>
        </tr>
      </table>
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
        <th class="x-th" style="width:100px;">使用者帳號</th>
        <th class="x-th" >IP</th>
        <th class="x-th" style="width:150px;">登入時間</th></tr>
      <?
      for ($i=1; $i <= $pagetool->rrows; $i++){
        $item = sql_fetch_array($result);
      ?>
      <tr class="x-tr<?=($i % 2)?'1':'2'?>">
        <td class="x-td"><input type="checkbox" name="sel[]" value="<?=$item['id']?>"/></td>
        <td class="x-td"><?=$i?></td>
        <td class="x-td"><?=$item['userid']?></td>
        <td class="x-td al"><?=$item['userip']?></td>
        <td class="x-td"><?=$item['logintime'];?></td>
      </tr>
      <?};
      //無資料顯示
      if ($pagetool->rrows==0){
        echo "<tr class='x-tr1'><td  class='x-td' colspan=\"5\" style=\"text-align:left;Butionpadding:5px;\"><font color=\"red\"><b>查無資料</b></font></td></tr>";
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
	$(document).ready(function(){
		$("#logintime").datepicker({
		  showOn: 'button',
			buttonImage: fulladmin+'images/calendar.gif',
      dateFormat: 'yy-mm-dd',
　　  buttonText: '...',
　　  changeYear: true,
			buttonImageOnly: true,
			showButtonPanel: true
		});
  });

  function goQuery(){
    var f = document.getElementById("QForm1");
    var errormessage = "";
    if (!isDate($('#logintime').val())){
      errormessage +="[起始日期]格式不正確<br/>";
    }
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
      warning_msg("<font color='red'><b>請先選擇欲刪除的資料!!</b></font>");
      return false;
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