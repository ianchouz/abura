<?php
include_once("../include/config.php");
include 'dao.php';

$mail = pgParam("mail", "");
$print = pgParam("print", "");

$id = pgParam("id", "");
if(empty($id)) {
    die("無法取得資料!!");
}

$vo = new contact_us();
echo $vo->getHtml($id);
?>