<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WMS網站管理系統::<?echo $CFG->company_name?></title>
<?require('base_header_lib.php');?>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>css/web.css?v=1"  />
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>css/menu.css?v=1"  />
<? if ((!empty($useckeditor) && $useckeditor) || (!empty($useimgbrowser) && $useimgbrowser)){ ?>
<link href="<?=$CFG->f_admin?>css/swfupload.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/swfupload/jquery.swfupload.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=$CFG->f_admin?>include/jqueryFileTree/jqueryFileTree.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>include/jqueryFileTree/jqueryFileTree.css"  />
<link rel="stylesheet" type="text/css" href="<?=$CFG->f_admin?>css/jquery-imgbrowser.css"  />
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-imgbrowser.js" ></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-flabrowser.js" ></script>
<script type="text/javascript" src="<?=$CFG->f_admin?>js/jquery-multibrowser.js" ></script>
<? }?>

<?php if (!empty($useckeditor) && $useckeditor){ ?>
<script type="text/javascript" src="<?=$CFG->f_admin?>ckeditor/ckeditor.js"></script>
<?php require("ckeditor/build_js.php");?>
<?php } ?>


<? if ((!empty($use_color) && $use_color)){ ?>
<link rel="stylesheet" href="<?=$CFG->f_admin?>/js/spectrum/spectrum.css" type="text/css" />
<script type="text/javascript" src="<?=$CFG->f_admin?>/js/spectrum/spectrum.js"></script>
<script>
$(document).ready(function(){
    $('.colorpicker').spectrum({
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        allowEmpty: true
    });
});
</script>
<? }?>

<script>
function goLoginout(){
    location.href="<?=$CFG->f_admin?>logout.php";
}
function goOpenNew(){
    window.open('<?=$CFG->url_web?><?=$CFG->url_index?>');
}
</script>





</head>
<?php
//取得使用者權限
$authority = $_SESSION['authority'];
$authority = str_replace(";;","','",$authority);
$authority = str_replace(";","'",$authority);
if ($authority==null && $authority!="all"){
    die("您無權限使用");
}
if ($authority!="all"){
    $sql = "select * from ".$CFG->tbext."webmenu where inuse=true and id in (".$authority.") and uplevel=-1 order by seq";
}else{
    $sql = "select * from ".$CFG->tbext."webmenu where inuse=true and uplevel=-1 order by seq";
}
//echo $sql.'<br>';
$result = sql_query($sql) or trigger_error("SQL", E_USER_ERROR);
//取代掉/webAdmin/

$nowpageurl = str_replace("/$CFG->doc_web"."$CFG->doc_admin","",$_SERVER[PHP_SELF]);
$canview = true;

if ($nowpageurl!='index.php'){
    //echo "menu_id:".$menu_id."<br>";
    if ($authority!="all"){
        //$wheresql = " and (a.uplevel in (".$authority.") or a.uplevel=-1) and a.keyname='".$menu_id."'";
        $wheresql = " and a.id in (".$authority.") and a.keyname='".$menu_id."'";
    }else{
        $wheresql = " and a.keyname='".$menu_id."'";
    }
    $sql = "select a.text,a.uplevel,b.keyname as uplevel_keyname from  ".$CFG->tbext."webmenu a left join ".$CFG->tbext."webmenu b on (b.id=a.uplevel) where a.inuse=true " . $wheresql;
    //echo "$sql<br>";
    $subquery = sql_query($sql) or trigger_error("SQL", E_USER_ERROR);
    $hasrow = sql_num_rows($subquery);
    if ($hasrow < 1){
        if ($authority!="all"){
            $canview = false;
        }
    }
    //取得資料
    $row = sql_fetch_array($subquery);
    $page_navigation = $row['text'];
    $menu_uplevel = $row['uplevel_keyname'];
    if ($menu_uplevel==null){
        $menu_uplevel = $menu_id;
    }
}
?>
<style>
.i_lbtn{float:left;width:9px;height:23px;background:url(<?=$CFG->fix_admin?>images/btn_left.png) 0px 0px no-repeat;margin-top:5px;}
.i_rbtn{float:left;width:9px;height:23px;background:url(<?=$CFG->fix_admin?>images/btn_right.png) 0px 0px no-repeat;margin-top:5px;}
.i_lbtn_loginout {cursor:pointer;float:left;height:23px;width:35px;background:url(<?=$CFG->fix_admin?>images/btn_loginout.png) 0px 0px no-repeat;padding:0px;margin-top:5px;}
.i_lbtn_loginout:hover {float:left;height:23px;width:35px;background:url(<?=$CFG->fix_admin?>images/btn_loginout.png) 0px -23px no-repeat;}
.i_lbtn_view {cursor:pointer;float:left;height:23px;width:61px;background:url(<?=$CFG->fix_admin?>images/btn_view.png) 0px 0px no-repeat;padding:0px;margin-top:5px;}
.i_lbtn_view:hover {float:left;height:23px;width:61px;background:url(<?=$CFG->fix_admin?>images/btn_view.png) 0px -23px no-repeat;}
.i_lbtn_split {float:left;height:23px;width:7px;background:url(<?=$CFG->fix_admin?>images/btn_split.png) 0px 0px no-repeat;padding:0px;margin-top:5px;}
.i_top{height:113px;width:100%;background:url(<?=$CFG->fix_admin?>images/i_tit_bg.gif) 0px 0px repeat-x;}
.i_logo{width:160px;height:113px;float:left;background:url(<?=$CFG->fix_admin?>images/logo6.svg) 10px 5px no-repeat;}
.i_info{float:left;letter-spacing:1px;font-weight:bold;padding-right:10px;padding-top:10px;}
.i_infoarea{height:30px;float:right;border:0px solid #a9bfd3;padding-right:20px;padding-top:10px;}
.i_btnarea{float:left;}
</style>
<body>
    <div class="i_top">
        <div class="i_logo" onclick="location.href='<?=$CFG->f_admin?>'" style="cursor:pointer;" title="回到首頁"></div>
        <div class="i_infoarea">
            <div class="i_info">WMS網站管理系統-<?echo $CFG->company_name?></div>
            <div class="i_info">登入者：<?echo $_SESSION['sess_name']?></div>
            <div class="i_info">語言版本：<?=$langs[$CFG->language]["name"]?></div>
            <div class="i_btnarea">
                <div class="i_lbtn"></div>
                <div class="i_lbtn_loginout" onclick="goLoginout();"></div>
                <div class="i_lbtn_split"></div>
                <div class="i_lbtn_view" title="預覽網站" onclick="goOpenNew();"></div>
                <div class="i_rbtn"></div>
            </div>
        </div>
    </div>
    <div id="mmbody" style="background: #FFFFFF;border:0px solid #d0d0d0;border-width:2px 0px 0px 0px;">
        <table style="border-collapse:collapse;width:100%;height:500px;">
            <tr>
                <td valign="top" style="background-color:#dbdbdb;width:150px;">
                    <div id="area-menu">
                        <ul id="menu">
                        <?php
                        $CFG->fn_edm = false;
                        while ($row = sql_fetch_array($result)) {
                            if ($row['keyname'] == 'epaper'){
                                $CFG->fn_edm = true;
                            }
                            if ($row['hidden']==false){
                                if ($row['leaf']){
                                    echo "<li id=\"".$row['keyname']."\">";
                                    echo "<a href=\"".$CFG->url_admin.$row['url']."\"><div class='i-menu'><div class=\"".($row['leaf']?"i-leaf":"i-folder ")."\">".$row['text']."</div></div></a>";
                                }else{
                                    if ($authority!="all"){
                                        $sql = "select * from ".$CFG->tbext."webmenu where inuse=true and hidden=false and id in (".$authority.") and uplevel=".$row['id']." order by seq";
                                    }else{
                                        $sql = "select * from ".$CFG->tbext."webmenu where inuse=true and hidden=false and uplevel=".$row['id']." order by seq";
                                    }
                                    //echo $sql.'<br>';
                                    $result2 = sql_query($sql) or trigger_error("SQL", E_USER_ERROR);
                                    if ($menu_uplevel == $row['keyname']){
                                        $cssactive = " submenuactive";
                                        $pfoldercss = "i-folder-open";
                                    }else{
                                        $cssactive = "";
                                        $pfoldercss = "";
                                    }
                                    echo "<li id=\"".$row['keyname']."\">";
                                    echo "<a href=\"".$CFG->url_admin.$row['url']."\"><div class='i-menu'><div class=\"i-folder ".$pfoldercss."\">".$row['text']."</div></div></a>";
                                    echo "<ul id=\"".$row['keyname']."-sub\" class=\"submenu $cssactive\">";
                                    while ($row2 = sql_fetch_array($result2)) {
                                        if ($menu_id == $row2['keyname']){
                                            //$subcssactive = "class='elnow'";
                                        }else{
                                            $subcssactive = "";
                                        }
                                        echo "<li><a href=\"".$CFG->url_admin.$row2['url']."\" $subcssactive><div class=\"elbow\">".$row2['text']."</div></a></li>";
                                    }
                                    echo "</ul>";
                                }
                                echo "</li>";
                            }
                        }
                        // 判斷是否為最高權限,出現選單
                        if ($authority=="all"){
                            $_keyname = "sysset-webmenu";
                            $_menuurl = "sysset/webmenu/index.php";
                            $_leaf = true;
                            $_text = "系統選單";
                            echo "<li id=\"".$_keyname."\">";
                            echo "<a href=\"".$CFG->url_admin.$_menuurl."\"><div class='i-menu'><div class=\"".($_leaf?"i-leaf":"i-folder ")."\">".$_text."</div></div></a></li>";

                            // $_keyname = "sysset-module";
                            // $_menuurl = "include/module/index.php";
                            // $_leaf = true;
                            // $_text = "系統模組";
                            // echo "<li id=\"".$_keyname."\">";
                            // echo "<a href=\"".$CFG->url_admin.$_menuurl."\"><div class='i-menu'><div class=\"".($_leaf?"i-leaf":"i-folder ")."\">".$_text."</div></div></a></li>";
                        }
                        ?>
                        </ul>
                    </div>
                </td>
                <td valign="top" style="border:0px solid #d0d0d0;border-width:0px 0px 0px 2px;">
                    <div id="area-content">
                    <?php
                    if (!$canview){
                        die("<div class='action_message'>抱歉，您無此權限觀看此頁面!!</div>");
                    }
                    ?>
