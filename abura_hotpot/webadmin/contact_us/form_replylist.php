<?php
include_once("../include/config.php");
require '../include/checksession.php';

$id = pgParam("id","");

$sql = "select * from ".$CFG->tbext."contact_us where id=$id";
$query = @sql_query($sql);

$queryItem = @sql_fetch_array($query);

$sql = "SELECT * FROM ".$CFG->tbext."contact_us_reply where contact_id='".$queryItem["id"]."' order by reply_time desc";
$rs_reply = @sql_query($sql);
$rcc=0;

if ($rs_reply != null){
    while($item = sql_fetch_array($rs_reply)){
?>
<table width="90%" border="0" cellpadding="6" cellspacing="1" bordercolor="#e9e9e9" bgcolor="#cccccc" align="center">
    <tr bgcolor="#ffe8c0" class="maintext">
        <td height="30" colspan="2" valign="top" bgcolor="#FFFFFF">
            <table width="100%" border="0" cellspacing="0" cellpadding="10">
                <tr bgcolor="#8FCEDF" height="20">
                    <td class="maintext">
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr>
                                <td width="50%" align="left">回覆者：<?=$item["reply_name"]?></td>
                                <td width="50%" align="right">回覆時間：<?=$item["reply_time"]?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="left" bgcolor="#EEEEEE" class="maintext">
                        <?=$item["reply_content"]?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<?php
        $rcc++;
    }
}

if ($rcc==0) {?>
<div style="width:95%;font-size:12px;color:blue;padding:5px;margin-left:20px;">
    目前無回覆記錄!!
</div>
<?php }?>