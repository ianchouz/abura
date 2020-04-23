<?php
$CFG->f_admin = $CFG->full_domain.$CFG->doc_admin;
$CFG->f_web = $CFG->full_domain;
$CFG->fix_web = $CFG->full_domain;
$CFG->fix_admin = $CFG->fix_web.$CFG->doc_admin;
/*圖片上傳檔案大小*/
$CFG->imgsize_limit= 5;
/*檔案上傳大小*/
$CFG->file_size_limit = 20;
/*檔案上傳限制格式*/
$CFG->fileappendext = array('.php','.exe','.inc','.js','html');
//購物車ID 名稱
$CFG->shoppingcarKey = 'shoppingcar';
$CFG->shoppingcar_title = '訂購單';
//使用者自定上傳
$CFG->doc_root = "archive/";
//圖片預設路徑
$CFG->doc_user = $CFG->doc_root."images/";
//相簿集
$CFG->doc_album = $CFG->doc_root."album/";
//編輯器
$CFG->doc_editor = "editor/";
//檔案下載路徑
$CFG->doc_user_file = $CFG->doc_root."doc/";
//FLASH
$CFG->doc_user_fla = $CFG->doc_user;
//以下為固定
//網站網址
$CFG->url_web = $CFG->full_domain;
//後台管理網址
$CFG->url_admin = "$CFG->url_web$CFG->doc_admin";
//config位置
$CFG->root_config = str_replace("\\","/",dirname(__FILE__));
//admin根目錄
$CFG->root_admin = str_replace("include","",$CFG->root_config);
//web根目錄
$CFG->root_web = str_replace("$CFG->doc_admin","",$CFG->root_admin);
$CFG->root_user = "$CFG->root_web$CFG->doc_user";
$CFG->web_user = "$CFG->url_web$CFG->doc_user";
$CFG->root_album = "$CFG->root_web$CFG->doc_album";
$CFG->web_album = "$CFG->url_web$CFG->doc_album";
$CFG->unknowimg = $CFG->url_web."images/unknow.jpg";
$CFG->root_user_img = "$CFG->root_web$CFG->doc_user";
$CFG->web_user_img = "$CFG->url_web$CFG->doc_user";
$CFG->root_user_file = "$CFG->root_web$CFG->doc_user_file";
$CFG->web_user_file = "$CFG->url_web$CFG->doc_user_file";
$CFG->root_user_fla = $CFG->root_user_img;
$CFG->web_user_fla = $CFG->web_user_img;
//共用lib
include $CFG->root_admin."include/tools.php";
include $CFG->root_admin."include/parseXML.php";
include $CFG->root_admin."include/dbFunc.php";
if (!isset($openOther)){
  //開啟資料庫
  $CFG->dblink = mysql_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass) or die("資料庫錯誤");
  mysql_query("SET NAMES 'utf8'");
  mysql_select_db($CFG->dbname, $CFG->dblink) or die("無法連接資料庫");
  if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
  } else if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
  }else{
    ini_set("session.name",$CFG->sessionname);
  }
  session_start();
  //撈取系統基本設定名稱
  $sql = "select xmlcontent from ".$CFG->tbext."webconfig where id='company_info'";
  $query = @mysql_query($sql);
  if ($query){
    $row = @mysql_fetch_row($query);
    $xmlobj = new parseXML($row[0]);
    $CFG->company_name = @$xmlobj->value('/content/company_name');
    $CFG->company_mail_sender = @$xmlobj->value('/content/mail_sender');
  }else{
    $CFG->company_name="";
  }
}
function __chkdir($pathdir){
  if(!@is_dir($pathdir)){
    @mkdir($pathdir, 0777);
  }else{
    @chmod($pathdir, 0777);
  }
}
//以下為設定圖片的尺寸大小及目錄位置
__chkdir($CFG->root_web.$CFG->doc_root);
__chkdir($CFG->root_user);
__chkdir($CFG->root_user_file);
__chkdir($CFG->root_user.$CFG->doc_editor);
__chkdir($CFG->root_album);
$CFG->simpleeditor_info = '<div style="margin:3px;color:red;">提醒您，如果是從其他地方複製過來的文案，建議先貼至記事本後在貼上來，或善用<img src="'.$CFG->url_admin.'images/ed_icon.jpg">功能貼上，因為，通常別處複製過來的文章會帶有許多用不到的格式，容易造成網站排版的問題。</div>';
$CFG->simpleeditor_config="[
{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'Link', 'Unlink' ] },
{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
{ name: 'styles', items: [ 'FontSize', 'TextColor' ] },
{ name: 'document', items: [ 'Source'] }
]";
$CFG->editor_info = '<div style="margin:3px;color:red;">提醒您，如果是從其他地方複製過來的文案，建議先貼至記事本後在貼上來，或善用<img src="'.$CFG->url_admin.'images/ed_icon.jpg">功能貼上，因為，通常別處複製過來的文章會帶有許多用不到的格式，容易造成網站排版的問題。</div>';
$CFG->ajax_error = '<font color="red"><b>抱歉，頁面程式出現錯誤!!請稍後在試~ <br>或者連絡客服人員!!</b></font>';
if ($CFG->language=='tw'){
  $CFG->systemmsg = '<div style="font-size:17px;color:red;width:80%;margin:0 auto;letter-spacing:4px;text-align:center;"><b>系統訊息</b></div><hr style="width:98%;border:1px solid;"/>';
  $CFG->waitmsg = '<div id="waitmsg" style="width:80%;display:none;border:1px solid red;margin:20px auto;padding:10px;font-size:12px;text-align:center;">系統作業中... 請稍等!!</div>';
}else if ($CFG->language=='eng'){
  $CFG->systemmsg = '<div style="font-size:17px;color:red;width:80%;margin:0 auto;letter-spacing:4px;text-align:center;"><b>System Message</b></div><hr style="width:98%;border:1px solid;"/>';
  $CFG->waitmsg = '<div id="waitmsg" style="width:80%;display:none;border:1px solid red;margin:20px auto;padding:10px;font-size:12px;text-align:center;">System operations ... Please wait!</div>';
}
//{頁尾編輯器
  $CFG->footer_ed = $CFG->doc_editor."footer/";
  __chkdir($CFG->root_user.$CFG->footer_ed);
//}
/********************SET *****************/

// 登入頁設定+++
$CFG->adminBg["w"]= 2100;
$CFG->adminBg["h"]= 1400;
$CFG->adminBg["noneW"]= 'N';
$CFG->adminBg["noneH"]= 'N';

$CFG->adminLogo["w"]= 150;
$CFG->adminLogo["h"]= 30;
$CFG->adminLogo["noneW"]= 'N';
$CFG->adminLogo["noneH"]= 'N';
$CFG->admin["path"] = "admin/";
__chkdir($CFG->root_user.$CFG->admin["path"]);

// 系統系列+++

// 首頁系列+++
// Banner
$CFG->indexset["w"]= 2000;
$CFG->indexset["h"]= 1124;
$CFG->indexset["noneW"]= 'Y';
$CFG->indexset["noneH"]= 'Y';
$CFG->indexset["path"] = "indexset/";
__chkdir($CFG->root_user.$CFG->indexset["path"]);
// Banner mbl
$CFG->indexset_mbl["w"]= 320;
$CFG->indexset_mbl["h"]= 545;
$CFG->indexset_mbl["noneW"]= 'Y';
$CFG->indexset_mbl["noneH"]= 'Y';
$CFG->indexset_mbl["path"] = "indexset_mbl/";
__chkdir($CFG->root_user.$CFG->indexset_mbl["path"]);

// s21data_img
$CFG->s21data_img["w"]= 519;
$CFG->s21data_img["h"]= 713;
$CFG->s21data_img["noneW"]= 'Y';
$CFG->s21data_img["noneH"]= 'Y';
$CFG->s21data_img["path"] = "s21data_img/";
__chkdir($CFG->root_user.$CFG->s21data_img["path"]);

// s22data_img
$CFG->product_cate["w"]= 1505;
$CFG->product_cate["h"]= 812;
$CFG->product_cate["noneW"]= 'Y';
$CFG->product_cate["noneH"]= 'N';
$CFG->product_cate["path"] = "product_cate/";
__chkdir($CFG->root_user.$CFG->product_cate["path"]);
// product
$CFG->product["w"]= 402;
$CFG->product["h"]= 268;
$CFG->product["noneW"]= 'Y';
$CFG->product["noneH"]= 'N';
$CFG->product["path"] = "product/";
__chkdir($CFG->root_user.$CFG->product["path"]);

// s23data_img
$CFG->meal_cover["w"]= 122;
$CFG->meal_cover["h"]= 252;
$CFG->meal_cover["noneW"]= 'Y';
$CFG->meal_cover["noneH"]= 'N';
$CFG->meal_cover["path"] = "meal_cover/";
__chkdir($CFG->root_user.$CFG->meal_cover["path"]);
$CFG->meal["w"]= 1505;
$CFG->meal["h"]= 812;
$CFG->meal["noneW"]= 'Y';
$CFG->meal["noneH"]= 'N';
$CFG->meal["path"] = "meal/";
__chkdir($CFG->root_user.$CFG->meal["path"]);

// s3data_img
$CFG->s3data_img["w"]= 2000;
$CFG->s3data_img["h"]= 1182;
$CFG->s3data_img["noneW"]= 'Y';
$CFG->s3data_img["noneH"]= 'Y';
$CFG->s3data_img["path"] = "s3data_img/";
__chkdir($CFG->root_user.$CFG->s3data_img["path"]);

// s4data_img
$CFG->s4data_img["w"]= 838;
$CFG->s4data_img["h"]= 473;
$CFG->s4data_img["noneW"]= 'Y';
$CFG->s4data_img["noneH"]= 'Y';
$CFG->s4data_img["path"] = "s4data_img/";
__chkdir($CFG->root_user.$CFG->s4data_img["path"]);

// NEWS 揭示板
$CFG->news["w"]= 440;
$CFG->news["h"]= 352;
$CFG->news["noneW"]= 'Y';
$CFG->news["noneH"]= 'N';
$CFG->news["path"] = "news/";
__chkdir($CFG->root_user.$CFG->news["path"]);







?>
