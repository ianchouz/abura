<?
include 'include/config.php';
session_destroy();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WMS網站管理系統</title>
</head>
<center>
<div>您已經成功登出，頁面將自動轉到登入頁。</div>
<META HTTP-EQUIV="refresh" content="2; URL=<?echo $CFG->url_admin?>login.php">