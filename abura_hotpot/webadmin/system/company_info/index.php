<?php
  include 'dao.php';
  include '../../header.php';
  checkAuthority("system");
  //初始化DAO
  $dao = new company_info();
  //讀取操作模式
  $mod = pgParam("mod","");
  if(isset($_POST["active"]) && $_POST["active"] =="run"){
    $dao->update();
    if ($dao->action_message=="true"){
      echo "<script>info_msg('操作成功!!');</script>";
    }else{
      echo "<script>info_msg('$dao->action_message')</script>";
    }
  }
  $dao->load();
?>
<form name="eForm1" id="eForm1" method="post">
  <input type="hidden" name="active" value="run"/>
  <div class="x-panel" id="x-webedit">
    <div class="x-panel-header">
      <? echo $page_navigation ?>
    </div>
    <div>
      <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
        <ul>
          <li><a href='#mtabs-01' style='font-size:12px;padding:5px 5px;'>基本資料</a></li>
          <li><a href='#mtabs-02' style='font-size:12px;padding:5px 5px;'>SMTP設定</a></li>
        </ul>
        <div id="mtabs-01">
          <div class="x-panel-body">
            <table class="x-table" style=" border-collapse: collapse;">
              <tr class="x-tr1">
                <th class="x-th"><em>*</em>公司名稱</th>
                <td class="x-td"><input type="text" name="company_name" id="company_name" size="40" maxLength="100" value="<? echo $dao->company_name; ?>"></td>
              </tr>
              <!-- <tr class="x-tr1">
                <th class="x-th"><em>*</em>公司英文名稱</th>
                <td class="x-td"><input type="text" name="company_name_en" id="company_name_en" size="40" maxLength="100" value="<? echo $dao->company_name_en; ?>"></td>
              </tr> -->
              <tr class="x-tr2">
                <th class="x-th">系統收信者</th>
                <td class="x-td"><input type="text" name="mail_receiver" id="mail_receiver" size="100" maxLength="500" value="<? echo $dao->mail_receiver; ?>"> <strong>(多個請用[,]逗號隔開)</strong></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th"><em>*</em>系統寄件者</th>
                <td class="x-td"><input type="text" name="mail_sender" id="mail_sender" size="100" maxLength="500" value="<? echo $dao->mail_sender; ?>"></td>
              </tr>
            </table>
          </div>
        </div>
        <div id="mtabs-02">
          <div class="x-panel-body">
            <table class="x-table" style=" border-collapse: collapse;">
              <tr class="x-tr2">
                <th class="x-th" colspan="2" style="text-align:left;padding-left:10px;">一般信件SMTP設定<?
                if ($dao->mail_receiver!=''){
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;<Button type="button" title="測試SMTP設定" class="button" onclick="goSMTP('');">測試SMTP設定</Button>
                <?}?>
                &nbsp;&nbsp;<Button type="button" title="載入GMAIL設定" class="button" onclick="loadGmail('');">載入GMAIL設定</Button>
                &nbsp;&nbsp;<Button type="button" title="清空SMTP設定" class="button" onclick="resetSTMP('');">清空SMTP設定</Button>
                </th>
              </tr>
              <tr class="tr1"><th width="10%" class="x-th">SMTP：</th><td width="90%" class="x-td"><input type="text" name="smtp" id="smtp" size="50" maxLength="200" value="<? echo $dao->smtp;?>"></td></tr>
              <tr class="tr2"><th width="10%" class="x-th">帳號：</th><td width="90%" class="x-td"><input type="text" name="account" id="account" size="50" maxLength="200" value="<? echo $dao->account;?>"></td></tr>
              <tr class="tr1"><th width="10%" class="x-th">密碼：</th><td width="90%" class="x-td"><input type="password" name="pwd" id="pwd" size="50" maxLength="200" value="<? echo $dao->pwd==""?"":"#####";?>"></td></tr>
              <tr class="tr2"><th width="10%" class="x-th">PORT：</th><td width="90%" class="x-td"><input type="text" name="port" id="port" size="20" maxLength="50" value="<? echo $dao->port;?>"></td></tr>
              <tr class="tr1"><th width="10%" class="x-th">SSL：</th><td width="90%" class="x-td"><input type="checkbox" name="ssl" id="ssl" value="Y" <?=$dao->ssl=='Y'?'checked':''?>> 需要SSL連線</td></tr>
              <?if ($CFG->fn_edm){?>
              <tr class="x-tr2">
                <th class="x-th" colspan="2" style="text-align:left;padding-left:10px;">電子報SMTP設定<?
                if ($dao->mail_receiver!=''){
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;<Button type="button" title="測試SMTP設定" class="button" onclick="goSMTP('edm_');">測試SMTP設定</Button>
                <?}?>
                &nbsp;&nbsp;<Button type="button" title="載入GMAIL設定" class="button" onclick="loadGmail('edm_');">載入GMAIL設定</Button>
                &nbsp;&nbsp;<Button type="button" title="清空SMTP設定" class="button" onclick="resetSTMP('edm_');">清空SMTP設定</Button>
                </th>
              </tr>
              <tr class="tr1"><th width="10%" class="x-th">SMTP：</th><td width="90%" class="x-td"><input type="text" name="edm_smtp" id="edm_smtp" size="50" maxLength="200" value="<? echo $dao->edm_smtp;?>"></td></tr>
              <tr class="tr2"><th width="10%" class="x-th">帳號：</th><td width="90%" class="x-td"><input type="text" name="edm_account" id="edm_account" size="50" maxLength="200" value="<? echo $dao->edm_account;?>"></td></tr>
              <tr class="tr1"><th width="10%" class="x-th">密碼：</th><td width="90%" class="x-td"><input type="password" name="edm_pwd" id="edm_pwd" size="50" maxLength="200" value="<? echo $dao->edm_pwd==""?"":"#####";?>"></td></tr>
              <tr class="tr2"><th width="10%" class="x-th">PORT：</th><td width="90%" class="x-td"><input type="text" name="edm_port" id="edm_port" size="20" maxLength="50" value="<? echo $dao->edm_port;?>"></td></tr>
              <tr class="tr1"><th width="10%" class="x-th">SSL：</th><td width="90%" class="x-td"><input type="checkbox" name="edm_ssl" id="edm_ssl" value="Y" <?=$dao->edm_ssl=='Y'?'checked':''?>> 需要SSL連線</td></tr>
              <?}?>
            </table>
          </div>
        </div>

      </div>

    </div>
    <div class="x-panel-bbar">
      <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
        <div class="btn_panel">
          <Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
	  $("#mtabs").tabs();
	});
  function goSubmit(){
    var errormessage = "";
    if ($('#company_name').val()==""){
      errormessage +="[公司名稱]不可以空白<br/>";
    }

    if ($('#mail_receiver').val()==""){
      //errormessage +="[系統收件者]不可以空白<br/>";
    }else{
      emailsarr = $('#mail_receiver').val().split(",");
      for(var i=0;i<emailsarr.length;i++){
        if (!isEmail(emailsarr[i])){
          errormessage +="[系統收件者]第 " + (i+1) + " 個格試錯誤<br/>";
        }
      }
    }
    if ($('#mail_sender').val()==""){
      errormessage +="[系統寄件者]不可以空白<br/>";
    }else{
      emailsarr = $('#mail_sender').val().split(",");
      for(var i=0;i<emailsarr.length;i++){
        if (!isEmail(emailsarr[i])){
          errormessage +="[系統寄件者]第 " + (i+1) + " 個格試錯誤<br/>";
        }
      }
    }
    if (errormessage!=""){
      warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
      return false;
    }
    wait_msg("請稍等...");
    $('#eForm1').submit();
  }

  function goSMTP(pre){
    wait_msg("請稍等...");
    $.post(
      "../../include/"+pre+"smtp_test.php",
      function(data){
        info_msg(data);
      },
      "html"
    );
  }
  function loadGmail(pre){
    $('#'+pre+'smtp').val('smtp.gmail.com');
    $('#'+pre+'port').val('465');
    $('#'+pre+'ssl').attr('checked',true);
    warning_msg("<font color='red'><b>已經將GMAIL設定載入，請在帳號及密碼欄位處輸入您的帳號密碼!!</b></font>");
  }
  function resetSTMP(pre){
    $('#'+pre+'smtp').val('');
    $('#'+pre+'port').val('');
    $('#'+pre+'account').val('');
    $('#'+pre+'pwd').val('');
    $('#'+pre+'ssl').attr('checked',false);
  }
</script>
<?php
  include('../../footer.php');
?>
