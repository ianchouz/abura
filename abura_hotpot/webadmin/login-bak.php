<?php
include 'include/config.php';
include 'include/model/model_login.php';
unset($_SESSION['sess_uid']);
unset($_SESSION['sess_name']);
if (!isset($_POST['username'])){
    $_SESSION["goredirect"] = $_SERVER["HTTP_REFERER"];
}
$checkuser = "false";
$errormessage = "";

//已經有進來頁面過,判斷是否有submit
if ((($_POST['username'] && $_POST['userpwd'] && $_POST['check']))){

    $loginkey = $_SESSION['loginkey'];
    //if ($loginkey == $_POST['check']){
    $checkuser = "true";
    $newcode = $_SESSION["Checknum"];
    if( $newcode != $_POST['checknum'] ){
        $errormessage = "驗證碼輸入錯誤!!";
        $checkuser = "false";
    }
    //}
    if ($checkuser=="true"){
        $username = $_POST['username'];
        $userpwd = $_POST['userpwd'];
    }

    if ($checkuser=="true"){
        $login_model = new _user_login();
        $login_model->username = $username;
        $login_model->userpwd = $userpwd;
        if ($login_model->isOK()){
            $direct = $_SESSION['goredirect'];
            unset($_SESSION['goredirect']);
            if ($direct=="" || preg_match("/\blogout.php\b/i",$direct) || preg_match("/\blogin.php\b/i",$direct)){
              $direct = "index.php";
            }
            header("location:$direct");
        }else{
            $errormessage = $login_model->msg;
        }
    }
}
$_SESSION["loginkey"] = date("Y-m-d H:i:s");
//echo "<br>HTTP_REFERER".$_SESSION['goredirect']."<br>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WMS網站管理系統::<?echo $CFG->company_name?></title>
</head>
<style>
html {
    margin: 0;
    padding: 0;
}
body {
    padding: 0;
    font-family: Helvetica, Arial, sans-serif;
    background-color: #FFFFFF;
}
#_login_div {
    width: 650px;/*必須要設定你整個DIV區塊的寬度*/
    margin: 100px auto 0px;
}
#_login_div #_login_content {
    margin: 0;
    padding: 0;
}
#_login_div #_login_main_content {
    background: url(images/login/_login_div_content.jpg) 0px 0px repeat-x;
}
#_login_div ul {
    list-style-type: none;
    color: #000000;
    margin: 10px 0px;
    padding: 0px;
}
#_login_div ul li {
    height: 25px;
    border: 1px solid #99BBEE;
}
#_login_div ul li img {
    padding-top: 4px
}
.cs {
    width: 25px;
    height: 260px;
    background: url(images/login/l_b_s.gif) 10px 0px repeat-y;
}
table {
    border: 0;
    border-collapse: collapse;
    margin: 0px;
    padding: 0px;
}
td {
    margin: 0px;
    padding: 0px;
    border: 0px solid #A6A6A6;
}
img {
    border: none;
}
.errormsg {
    color: red;
    font-size: 12px;
    line-height: 15px;
    width: 160px;
}
.btn_reset {
    height: 23px;
    width: 36px;
    background: url(images/btn_reset.png) 0px 0px no-repeat;
    padding: 0px;
    margin: 0px;
}
.btn_reset:hover {
    height: 23px;
    width: 36px;
    background: url(images/btn_reset.png) 0px -23px no-repeat;
}
.btn_submit {
    height: 23px;
    width: 36px;
    background: url(images/btn_submit.png) 0px 0px no-repeat;
    padding: 0px;
    margin: 0px;
}
.btn_submit:hover {
    height: 23px;
    width: 36px;
    background: url(images/btn_submit.png) 0px -23px no-repeat;
}
.btn_left {
    height: 23px;
    width: 9px;
    background: url(images/btn_left.png) 0px 0px no-repeat;
    padding: 0px;
    margin: 0px;
}
.btn_right {
    height: 23px;
    width: 9px;
    background: url(images/btn_right.png) 0px 0px no-repeat;
    padding: 0px;
    margin: 0px;
}
.btn_sp {
    height: 23px;
    width: 7px;
    background: url(images/btn_split.png) 0px 0px no-repeat;
    padding: 0px;
    margin: 0px;
}
</style>
<script type="text/javascript">var fulladmin = "<?=$CFG->f_admin?>";var webroot = "<?=$CFG->f_web?>";</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<link href="http://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts-min.js" type="text/javascript"></script>
<link href="<?=$CFG->f_admin?>plugin/hiAlerts/jquery.hiAlerts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>js/plib.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery.client.js" ></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#os').html("作業系統: <b>" + $.client.os + "</b>");
    var vv = parseInt($.client.vversion);

    $('#whsize').html("解析度: <b>" + $(window).width()+'px * '+$(window).height()+'px' + "</b>");

    $('#browser').html("瀏覽器: <b>" + $.client.browser + "</b>");

    if(vv<7 && $.client.browser=='Explorer'){
    $('#version').html("瀏覽器版本: <b>" + $.client.vversion + "</b><div style='color:red;'>建議您升級瀏覽器版本!!</div>");
       warning_msg("<font color='red'><b>親愛的客戶您好，系統偵測您的瀏覽器版本為IE7以下，<br>為了確保您能順利的使用網站功能，請升級至IE7以上版本。</b></font>");
    }else{
       $('#version').html("瀏覽器版本: <b>" + $.client.vversion + "</b>");
    }
    $('#version').append("<br>您的IP: <b><?=$_SERVER['REMOTE_ADDR']?></b>");
    $("#checknum").keydown(function(event){
      if (event.keyCode==13){
        goSubmit();
      }
    });
  });
  $(document).ready(function(){
    $("#checknum").keydown(function(event){
      if (event.keyCode==13){
        goSubmit();
      }
    });
  });
  function goSubmit(){
    var errormessage = "";
    if ($('#username').val()==""){
      errormessage +="* 請輸入帳號<br/>";
    }
    if ($('#userpwd').val()==""){
      errormessage +="* 請輸入密碼<br/>";
    }
    if ($('#checknum').val()==""){
      errormessage +="* 請輸入圖形檢查碼<br/>";
    }
    if (errormessage!=""){
      warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
      return false;
    }
    var url = $("form[name='eForm1'] select[name='lang']").val();
    var f = document.getElementById("eForm1");
    f.action = url;
    wait_msg("請稍等...");
    $('#loginform').submit();
    f.submit();
  }
  function goreset(){
    document.getElementById("eForm1").reset();
  }
</script>
<body>
<center>
  <div id="_login_div">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td id="_login_content"><div style="margin:0px;padding:0px;"><img src="images/login/_login_div_top.jpg" style="display:block;margin:0px;padding:0px;" border="0"/></div>
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="26"><img src="images/login/_login_div_left.jpg" style="display:block;"/></td>
              <td width="544" valign="top" id="_login_main_content"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td valign="top" width="93" style="padding-top:30px;"><img src="images/logo.gif" style="margin:0px;padding:0px;" border="0"/></td>
                    <td valign="top" style="padding-top:30px;" align="center"><div class="cs">&nbsp;</div></td>
                    <td valign="top" align="center"><table border="0" cellpadding="0" cellspacing="0" width="99%">
                        <tr>
                          <td height="100" valign="middle" align="left" style="padding:20px 0px 0px 20px;"><img src="images/login/title.png"/></td>
                        </tr>
                        <tr>
                          <td height="10" style="font-size:1px;">&nbsp;</td>
                        </tr>
                        <tr>
                          <td valign="middle" align="center"><form name="eForm1" id="eForm1" method="post">
                              <input type="hidden" name="check" value="<?echo $_SESSION['loginkey']?>">
                              <table border="0" cellpadding="0" cellspacing="0" width="300">
                                <tr height="25">
                                  <td width="95" valign="middle"><img src="images/login/txt_id.png"/></td>
                                  <td align="left"><input type="text" name="username" id="username" size="20" maxLength="20"/></td>
                                </tr>
                                <tr height="25">
                                  <td width="95" valign="middle"><img src="images/login/txt_pwd.png"/></td>
                                  <td align="left"><input type="password" name="userpwd" id="userpwd" size="20" maxLength="20"/></td>
                                </tr>
                                <tr height="25">
                                  <td width="95" valign="middle"><img src="images/login/txt_lan.png"/></td>
                                  <td align="left"><select name="lang" id="lang">
                                      <?php foreach ($langs as $item){
                                        $sel = "";
                                        if ($item["val"]==$CFG->language){
                                            $sel =" selected";
                                        }
                                        echo '<option value="'.$item["url"].'" '.$sel.'>'.$item["name"].'</option>';
                                        }
                                        ?>
                                    </select></td>
                                </tr>
                                <tr height="25">
                                  <td width="95" valign="middle"><img src="images/login/txt_chk.png"/></td>
                                  <td align="left"><input type="text" name="checknum" id="checknum" size="6" maxLength="5">
                                    <img src="include/img.php" id="rand-img" style="vertical-align:bottom;margin-left:5px;margin-top:0px;padding-top:0px;border:1px #000 solid;">
                                  <td></td>
                                </tr>
                              </table>
                              <table border="0" cellpadding="0" cellspacing="0" width="300">
                                <tr height="30">
                                  <td align="right"><? if ($errormessage != ""){
                                      echo "<span class='errormsg'>$errormessage</span>";
                                      }?></td>
                                </tr>
                                <tr>
                                  <td align="center"><table border="0" cellpadding="0" cellspacing="0">
                                      
                                        <td><div class="btn_left"></div></td>
                                        <td><div class="btn_submit" onclick="goSubmit();" style="cursor:pointer;"></div></td>
                                        <td><div class="btn_sp"></div></td>
                                        <td><div class="btn_reset" onclick="goreset();" style="cursor:pointer;"></div></td>
                                        <td><div class="btn_right"></div></td>
                                    </table></td>
                                </tr>
                              </table>
                            </form></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
              <td width="30"><img src="images/login/_login_div_right.jpg"  style="display:block;"/></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td valign="top"><img src="images/login/_login_div_bottom.jpg"  style="display:block;"/></td>
      </tr>
    </table>
    <div><img src="images/login/info.gif"  style="display:block;"/></div>
  </div>
  <div style="width:521px;margin:0px auto; padding:5px;font-size:12px;color:blue;text-align:left;">
    <div id="os"></div>
    <div id="browser"></div>
    <div id="version"></div>
  </div>
</center>
</body>
