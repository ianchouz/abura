<?php
header("Cache-control: private");
session_cache_limiter('nocache');
$now = date("Y-m-d H:i:s");
if(empty($_SESSION['sess_uid']) || empty($_SESSION['sess_name']) || empty($_SESSION['logintime'])) {
    //header('Location:'.$base_url."login.php?refer=". urlencode(getenv('REQUEST_URI')));
    if($canlogin == "n") {
        echo "請重新登入";
        exit;
    } else {
        echo '<html><body><form name="eform" id="eform" action="' . $CFG->fix_admin . 'login.php"></from>';
        echo '<script>document.getElementById("eform").submit();</script></body></html>';
        exit;
    }
} else {
    $logintime = $_SESSION['logintime'];
    $diff = (strtotime($now) - strtotime($logintime)) / 60;
    if($diff > 360) {
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <style>
    .t3{
        color: #red;font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12pt;
        width:100%;
        margin:50px;
    }
    </style>
    <body>
        <div class="t3">現在時間：<?= $now ?><br>您已經太久沒有動作，請重新登入!!(最後使用時間:<?
        echo $logintime;
?>)<a href='<?= $CFG->url_admin ?>login.php'>重新登入</a>$diff</div>
    </body>
</html>
<?php
        exit;
    }
}
$_SESSION['logintime'] = date("Y-m-d H:i:s");
$_SESSION['sess_uid'] = $_SESSION['sess_uid'];
$_SESSION['sess_name'] = $_SESSION['sess_name'];
?>