<?php
include_once("fn_set.inc");
checkAuthority($menu_id);


$filename = $_GET['filename'];
if(!isset($mainid) || $mainid == "") {
    die("缺少編號");
}
if(!isset($filename) || $filename == "") {
    die("缺少資料");
}
//讀取資料
$ass = sql2ass("select * from $table where cateid='" . sql_real_escape_string($mainid) . "' AND filename='" . sql_real_escape_string($filename) . "' ");
?>
<form name="infoForm" method="POST" enctype="multipart/form-data">
<input type="hidden" name="mainid" value="<?= $mainid ?>" />
<input type="hidden" name="filename" value="<?= $filename ?>" />
<div class="infoBox">
    <div class="infoTitle">編輯檔案</div>
    <div class="infoWrap">
        <input type="text" name="filename_title" class="form-control" placeholder="輸入標題" value="<?= $ass['filename_title'] ?>" />
        <input type="text" name="fileMemo" class="form-control" placeholder="輸入說明" value="<?= $ass['fileMemo'] ?>" />
        <!--<input type="text" name="url" class="form-control" placeholder="連結設定" value="<?= $ass['url'] ?>" />-->
    </div>
    <div class="infoBtn">
        <div class="infoSave">存檔</div>
        <div class="infoCancel">取消</div>
    </div>
</div>
</form>