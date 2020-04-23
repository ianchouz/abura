<?php
include 'dao.php';
include '../../header.php';
checkAuthority("system");
$headstr = "";
$dao = new userdata();
//讀取查詢條件
$dao->setQueryDefault();
echo $dao->qVO->bulidFrom('qFrom1', 'index.php');

$mod = pgParam("mod", "add");
if(isset($_POST["active"]) && $_POST["active"] == "run") {
    if($mod == "edit") {
        $dao->update();
    } else {
        $dao->insert();
    }
    if($dao->action_message == "") {
        die("<script>transPageInfoForm('作業成功','qFrom1');</script>");
    } else {
        echo "<script>info_msg('$dao->action_message')</script>";
    }
}
if($mod == "edit") {
    $headstr = "-修改";
    $dao->load();
} else {
    $headstr = "-新增";
}
?>
<style>
  .mainmenu{
    border-bottom:1px solid #111111;
  }
    .submenu{
        margin-left:20px;
        font-size:11px;
        color:#7c7b7b;
        border-bottom:1px dashed #7c7b7b;
    }
</style>
<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
  <input type="hidden" name="mod" value="<?=$mod;?>"/>
  <input type="hidden" name="active" value="run"/>
  <input type="hidden" name="userid" value="<?=$dao->dbrow['userid'];?>"/>
<div class="x-panel" id="x-webedit">
  <div class="x-panel-header">
    <?=$page_navigation.$headstr ?>
  </div>
  <div class="x-panel-body">
    <table class="x-table" style=" border-collapse: collapse;">
      <tr class="x-tr1">
        <th class="x-th"><em>*</em>管理者名稱</th>
        <td class="x-td"><input type="text" name="username" id="username" size="20" maxLength="100" value="<?=$dao->dbrow['username'];?>"></td>
      </tr>
      <tr class="x-tr2">
        <th class="x-th">Email</th>
        <td class="x-td">
          <input type="text" name="useremail" id="useremail" size="40" maxLength="100" value="<?=$dao->dbrow['useremail'];?>"/>
        </td>
      </tr>
      <tr class="x-tr1">
        <th class="x-th"><em>*</em>帳號</th>
        <td class="x-td">
          <? 
          if ($mod=="edit"){
            ?><input type="hidden" name="userid" id="userid" size="20" maxLength="20" value="<?=$dao->dbrow['userid'];?>"><?=$dao->dbrow['userid'];?><?
          }else{
            ?><input type="text" name="userid" id="userid" size="20" maxLength="20" value="<?=$dao->dbrow['userid'];?>"><?
          }?> &nbsp;<strong>(可輸入A~Z或a~z或0~9或_，最少需輸入3個字)</strong>
        </td>
      </tr>
      <tr class="x-tr1"><th class="x-th"><?php if($mod!="edit") {?><em>*</em><?php }?>新密碼</th>
        <td class="x-td">
          <input type="password" name="newuserpwd" id="newuserpwd" size="30" maxLength="20" value=""/> &nbsp;<strong>(可輸入A~Z或a~z或0~9或_，最少需輸入6個字)</strong>
        </td>
      </tr>
      <tr class="x-tr1"><th class="x-th"><?php if($mod!="edit") {?><em>*</em><?php }?>確定新密碼</th>
         <td class="x-td">
          <input type="password" name="reuserpwd" id="reuserpwd" size="30" maxLength="20" value=""/>
         </td>
      </tr>
      <tr class="x-tr1">
         <th class="x-th">
          <em>*</em>管理權限<br>
          <br>
          <br>
         </th>
         <td class="x-td">
          <div style="margin:10px;"><input type="button" value="全選" onclick="runSelAll('authority[]');"/> <input type="button" value="取消全部" onclick="runSelAllElse('authority[]');"/></div>
          <div style="width:500px;">
          <?
          $menuresult= $dao->loadMenu();
          $cc=0;
          //echo "<br>authority:$dao->authority<br>";
          while ($row = sql_fetch_array($menuresult)) {
            if ($cc!=0 && ($cc % 4==0)){
              //echo "<br>";
            }
            $ischeck = strpos ($dao->dbrow['authority'], ";".$row['id'].";");
            if ($ischeck ===false){
              $ischeck = false;
            }else{
              $ischeck = true;
            }
            //echo "<br>id:".$row['id']."ischeck:".$ischeck."<br>";
            ?>
              <div style="float:left;min-width:120px;padding-right:30px;padding-bottom:10px;">
              <div class="mainmenu"><input type="checkbox" name="authority[]" value="<?=$row['id']?>" <?=$ischeck?"checked":""?> " onclick="check_sub(this)" class="folder"/>&nbsp;<b><?=$row['text']?></b></div>
              <?
                $subrs = $dao->loadSubMenu($row['id']);
                while ($sub_item = @sql_fetch_array($subrs)) {
                  $issubcheck = strpos ($dao->dbrow['authority'], ";".$sub_item['id'].";");
                  if ($issubcheck ===false){
                    $issubcheck = false;
                  }else{
                    $issubcheck = true;
                  }
                ?><div class="submenu"><input type="checkbox" name="authority[]" value="<?=$sub_item['id']?>" <?=$issubcheck?"checked":""?> "  class="dir" onclick="check_parent(this)"/>&nbsp;<?=$sub_item['text']?></div><?
                }
              ?>
            </div>
            <?
            $cc++;
          }
          ?>
          </div>
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

<div>

</div>
</form>
<script type="text/javascript">
  function goSubmit(){
    var errormessage = "";
    if ($('#username').val()==""){
      errormessage +="[管理者名稱]不可以空白<br/>";
    }
    if (!isEmail($('#useremail').val())){
      errormessage +="[E-mail]格式不正確<br/>";
    }
    re=/^[@|%|#|!|&|*|!|^|$|\w]+$/;

    if ($('#userid').val()==""){
      errormessage +="[帳號]不可以空白<br/>";
    }else{
      if ($('#userid').val().length<3){
         errormessage +="[帳號]不可少於3個字<br/>";
      }
      if(!re.test($('#userid').val())){
        errormessage +="[帳號格式]只能為英數字(包含_)<br/>";
      }
    }
    if ($('#newuserpwd').val()=="" && '<?=$mod?>'!="edit"){
      errormessage +="[密碼]不可以空白<br/>";
    }else if($('#newuserpwd').val()!=""){
      if(!re.test($('#newuserpwd').val())){
        errormessage +="[密碼]只能為英數字(包含_)<br/>";
      }
      if ($('#newuserpwd').val().length<6){
        errormessage +="[密碼]不可少於6個字<br/>";
      }
      if ($('#newuserpwd').val() != $('#reuserpwd').val()){
        errormessage +="[密碼]與[確認密碼]不同<br/>";
      }
    }
    //判斷權限是否有填
    var authority =getBoxVals('authority[]',';');
    if (authority==""){
      errormessage +="請勾選管理權限<br/>";
    }

    if (errormessage!=""){
      warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
      return false;
    }
    wait_msg("請稍等...");
    $("#eForm1").submit();
  }
  function goBack(){
    transPageInfoForm('','qFrom1');
  }

  function check_sub(chk){
    var $clickobj = $(chk);
    if ($clickobj.attr('checked')){
      $clickobj.parent().parent().find('.dir').each(function(){
        $(this).attr('checked',true);
      });
    }else{
      $clickobj.parent().parent().find('.dir').each(function(){
        $(this).attr('checked',false);
      });
    }

  }
  function check_parent(chk){
    var $clickobj = $(chk);
    if ($clickobj.attr('checked')){
      $clickobj.parent().parent().find('.folder').eq(0).attr('checked',true);
    }else{
      var parent_obj = $clickobj.parent().parent().find('.folder').eq(0);
      var total_size = $clickobj.parent().parent().find('.dir:checked').length;
      if (total_size==0){
        parent_obj.attr('checked',false);
      }
    }
  }
</script>
<?php  include '../footer.php';?>