<div class="-file-choice-area">
<div style="padding:1px 4px 0;color:red;font-size:12px;">僅允許 <?=$limitedext?> 格式，檔案大小不可超過 <?=$size_mb?>MB</div>
<input type="radio" name="upflag<?=$upname?>" value="1" class="upflag" checked/> 無 <br />
<input type="hidden" name="<?=$upname?>" value="<?=$value?>">
<?php if($value !="") {
$filefullpath = $CFG->root_user_file.$dao->cfg["path"].$append.$value;
$downpath = base64_encode($filefullpath);
//echo $filefullpath;
$show = '<span class="btn_down" onclick="go_download_stand(\''.$downpath.'\')">'.$value.'</span>';?>
<input type="radio" name="upflag<?=$upname?>" value="2" class="upflag" checked/> 目前檔案：<?=$show?> 檔案大小：<?=getFileSize($filefullpath)?> <br />
<?php }?>
<input type="radio" name="upflag<?=$upname?>" value="3" class="upflag" /> 新上傳：<input type="file" name="newfile<?=$upname?>" id="newfile<?=$upname?>" size="50" style="height:22px;" onclick="setUpRadio('upflag<?=$upname?>');">
</div>