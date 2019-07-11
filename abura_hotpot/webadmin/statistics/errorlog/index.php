<?php
include 'dao.php';
include '../../header.php';
checkAuthority("statistics");
include '../../include/pagelib.php';
$pagetool = new pageTool();

$dao = new user_errorlog();
$mod = $_POST["mod"];
if(isSet($mod)) {
    if($mod == "del") {
        $dao->delrow();
    }
    if($dao->action_message != null) {
        echo "<div id='__action_message' class=\"action_message\">$dao->action_message</div>";
        echo "<script>setTimeout(\"$('#__action_message').fadeOut();\",2000);</script>";
    }
}
//取得查詢條件
$querypars = new QueryParames();
$sql = "SELECT * FROM " . $CFG->tbext . "user_errorlog where 1 ";
if($querypars->logintime != "") {
    $sql .= " and acttime like '" . str_replace("/", "-", $querypars->logintime) . "%'";
}
if($querypars->userid != "") {
    $sql .= " and account ='" . $querypars->userid . "'";
}
if($querypars->actipaddress != "") {
    $sql .= " and actipaddress ='" . $querypars->actipaddress . "'";
}
$tmpsql = "select topadmin from " . $CFG->tbuserext . "userdata where inuse=true and userid='" . $_SESSION['sess_uid'] . "'";
$subquery = sql_query($tmpsql) or trigger_error("SQL", E_USER_ERROR);
$r = sql_fetch_row($subquery);
if(!$r[0]) {
    $sql .= " and account not in ('Project','Maintain1','Maintain2')";
}
$sql .= " ORDER BY acttime desc";
$result = $pagetool->excute($sql);
function displayPw($pw) {
    $num = strlen(substr($pw, 2));
    $num = 6;
    while($i < $num) {
        $str .= '*';
        $i++;
    }
    return substr($pw, 0, 2) . $str;
}
?>
  <form name="QForm1" id="QForm1" method="post">
  <input type="hidden" name="mod" name="mod" value=""/>
<div class="x-panel" id="weblist">
  <div class="x-panel-header">
    <div class="page_navigation">帳號登入異常紀錄</div>
  </div>
  <div class="x-panel-header">
        <div class="query_area">
            帳號：<input type="text" size="10" maxLength="100" name="userid" id="userid" value="<?=$querypars->userid?>"/>&nbsp;操作日期：
            <input type="text" size="12" maxLength="10" class="showdate" name="logintime" id="logintime" value="<?=$querypars->logintime?>"/>&nbsp;
            操作IP：<input type="text" size="20" maxLength="100" name="actipaddress" id="actipaddress" value="<?=$querypars->actipaddress?>"/>&nbsp;
          <Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button>
        </div>
  </div>
  <div class="x-panel-body">

        <div class="list_but">
          <span class="l_ss" title="請勾選項目後再點選按鈕"></span>
          <Button type="button" title="刪除" class="button" onclick="runDel();">刪除</Button>
        </div>
        <div class="clearfloat"></div>
        <div id="tb_container">
        <table id="x-data-list-table">
          <tr class="tr1">
            <th class="x-th"  style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');">&nbsp;</div></th>
            <th class="x-th"  style="width:50px;">項次</th>
            <th class="x-th"  style="width:100px;">帳號</th>
            <th class="x-th"  style="width:100px;">密碼</th>
            <th class="x-th"  >IP</th>
            <th class="x-th"  style="width:150px;">操作時間</th></tr>
          <?
          for ($i=1; $i <= $pagetool->rrows; $i++){
            $item = sql_fetch_array($result);
          ?>
          <tr class="tr<?=($i % 2)?'1':'2'?>">
            <td class="x-td"><input type="checkbox" name="sel[]" value="<?=$item['id']?>"/></td>
            <td class="x-td"><?=$i?></td>
            <td class="x-td"><?=$item['account']?></td>
            <td class="x-td"><?=displayPw($item['pwd'])?></td>
            <td class="x-td"><?=$item['actipaddress']?></td>
            <td class="x-td"><?=$item['acttime'];?></td>
          </tr>
          <?php }?>
        </table>
        <?  //無資料顯示
          if ($pagetool->rrows==0){
            echo "<div class='pageNone'>查無資料</div>";
          }
          ?>

      </div>
  </div>
  <div class="x-panel-bbar">
    <?=$pagetool->builePage();?>
  </div>
</div>
  </form>

<?php  include('../../footer.php');?>
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
    }else{
      hiConfirm('您確認要刪除選取的資料!?刪除後無法復原!!', '確認對話', function(r) {
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