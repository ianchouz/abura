<?php
include 'include/config.php';
include 'include/model/model_login.php';
unset($_SESSION['sess_uid']);
unset($_SESSION['sess_name']);
if (!isset($_POST['username'])){
    $_SESSION["goredirect"] = $_SERVER["HTTP_REFERER"];
}

$_cookie = new _login_from_cookie();
$cookieLogin = false;
if ($_cookie->_get()){
    $cookieLogin = true;
    $username = $_cookie->username;
    $userpwd = $_cookie->userpwd;
}

$checkuser = false;
$errormessage = "";
//已經有進來頁面過,判斷是否有submit
if ((($_POST['username'] && $_POST['userpwd'] && $_POST['check']) || $cookieLogin)){
    $remeberme = $_POST["remember_ac"];
    if($cookieLogin){
        $checkuser = true;
    }else{
        $username = $_POST['username'];
        $userpwd = $_POST['userpwd'];
        $loginkey = $_SESSION['loginkey'];
        //if ($loginkey == $_POST['check']){
        $checkuser = true;
        $newcode = $_SESSION["Checknum"];
        if( $newcode != $_POST['checknum'] ){
            $errormessage = "驗證碼輸入錯誤!!";//.$newcode."-".$_POST['checknum']
            $checkuser = false;
        }
    }

    if ($checkuser==true){
        $login_model = new _user_login();
        $login_model->username = $username;
        $login_model->userpwd = $userpwd;
        if ($login_model->isOK()){
            if ($remeberme==="Y" ){
                $_cookie->username = $username;
                $_cookie->userpwd = $userpwd;
                $_cookie->_set();
            }else{
                $_cookie->_del();
            }
            $direct = "index.php";
            $url=$CFG->langs[$_POST['langs']]["url"];
            $url=str_replace("login.php", "",$url);
            if( $url){
               $direct = $url;
            }
            header("location:$direct");
        }else{
            $errormessage = $login_model->msg;
        }
    }
}
$_SESSION["loginkey"] = date("Y-m-d H:i:s");
//echo "<br>HTTP_REFERER".$_SESSION['goredirect']."<br>";


##msg
$row=getConfig('ctrl');
$xmlvo = new parseXML($row['xmlcontent']);
$bg=$xmlvo->value("/content/bg");
$logo=$xmlvo->value("/content/logo");
$path = "admin"; $set = "adminBg";
$bg=getImg($path,$set,$bg,$CFG->f_admin."images/index/login.jpg","");
$path = "admin"; $set = "adminLogo";
$logo=getImg($path,$set,$logo,$CFG->f_admin."images/index/logo.jpg","");


?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WMS網站管理系統::<?echo $CFG->company_name?></title>
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/loaders.css">
    <link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
    <link href="css/jquery-ui_select.css" rel="stylesheet" type="text/css" />
    <script src="js/modernizr.js"></script>
    <link rel="stylesheet" href="css/admin_login.css"> <!-- shop_style style -->
    <script type="text/javascript">var fulladmin = "<?=$CFG->f_admin?>";var webroot = "<?=$CFG->f_web?>";</script>
    <link href="https://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="admin_login">
    <div class="image" style="background-image:url(<?=$bg["src"]?>)">
    </div>
    <div class="login">
        <form name="eForm1" id="eForm1" method="post" action="<?=$CFG->base_domain?>webadmin/login.php">
        <input type="hidden" name="check" value="<?=$_SESSION['loginkey']?>">
        <div class="login_box">
            <div class="logo"><?=$logo["img"]?></div>
            <div class="form">
                <div class="text">
                    網站後端管理系統登入<br /><br />
                    LOGIN TO YOUR<br />ACCOUNT<br />
                </div>

                <div class="line account boder">
                    <label for="account">帳 號</label>
                    <input type="text" name="username" id="username"  size="20" maxLength="20"/>
                </div>
                <div class="line password boder">
                    <label for="password">密 碼</label>
                    <input type="password" name="userpwd" id="userpwd" size="20" maxLength="20"/>
                </div>
                <!-- <?php if(count($CFG->langs)>1){?>
                <div class="line  boder">
                    <label for="account">語 系</label>
                     <select id="langs" name="langs" style="border:0;margin-left:20px;" >
                         <?php foreach($CFG->langs as $key=> $val){
                         $ck=$key==$CFG->language?"selected":"";
                         ?>
                        <option value="<?=$key?>" <?=$ck?>><?=$val["name"]?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php }?> -->
                <div class="line boder">
                    <input type="text" name="checknum" id="checknum" autocomplete="off" size="6" maxLength="5"  placeholder="驗  證  碼">

                </div>
                <div class="line">
                    <div class="code_box">
                        <img src="include/img.php" class="code" id="rand-img">

                        <div class="reset"  onClick="document.getElementById('rand-img').src = document.getElementById('rand-img').src + '?' + (new Date()).getMilliseconds()"  style="cursor: pinter;"></div>
                    </div>
                    <div class="remember">
                        <input type="checkbox" name="remember_ac" id="remember_ac" value="Y">
                        <label for="remember_ac">記住帳密</label>
                    </div>
                </div>
                <div class="line">
                    <div class="button" onclick="goSubmit();">登 入</div>
                </div>
            </div>
            <div class="copyright">
                最佳瀏覽解析度 1024*768 建議使用瀏覽器IE11.0版本以上<br />Copyright(c) 2018 COSMO all right reserved.<br><br>
                <div id="version"></div>
                <div id="os"></div>
                <div id="browser"></div>
                <?php if ($errormessage != ""){
                      echo "<span class='errormsg'>$errormessage</span>";
                }?>
            </div>
        </div>
        </form>
    </div>
</div>
<script src="js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>js/plib.js"></script>
    <script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery.client.js" ></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#os').html("作業系統: <b>" + $.client.os + "</b>");
        var vv = parseInt($.client.vversion);

        $('#whsize').html("解析度: <b>" + $(window).width()+'px * '+$(window).height()+'px' + "</b>");

        $('#browser').html("瀏覽器: <b>" + $.client.browser + "</b>");
        var ip="您的IP: <b><?=$_SERVER['REMOTE_ADDR']?></b><br>";
        if(vv<7 && $.client.browser=='Explorer'){
        $('#version').html(ip+"瀏覽器版本: <b>" + $.client.vversion + "</b><div style='color:red;'>建議您升級瀏覽器版本!!</div>");
           warning_msg("<font color='red'><b>親愛的客戶您好，系統偵測您的瀏覽器版本為IE7以下，<br>為了確保您能順利的使用網站功能，請升級至IE7以上版本。</b></font>");
        }else{
           $('#version').html(ip+"瀏覽器版本: <b>" + $.client.vversion + "</b>");
        }

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
          errormessage +="* 請輸入帳號\n";
        }
        if ($('#userpwd').val()==""){
          errormessage +="* 請輸入密碼\n";
        }
        if ($('#checknum').val()==""){
          errormessage +="* 請輸入圖形檢查碼\n";
        }
        if (errormessage!=""){
          warning_msg("請檢查以下錯誤:\n" + errormessage);
          return false;
        }
        var url = $("form[name='eForm1'] select[name='lang']").val();
        var f = document.getElementById("eForm1");
        $('#eForm1').submit();
        f.submit();
      }

      function goreset(){
        document.getElementById("eForm1").reset();
      }
    </script>
</body>
</html>
