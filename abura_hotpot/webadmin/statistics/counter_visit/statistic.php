<?
include '../../include/config.php';
$canlogin = "n";
require '../../include/checksession.php';
$strSQLQ = "select xmlcontent from " . $CFG->tbext . "webconfig where id='company_info'";
$query = sql_query($strSQLQ);
$row = sql_fetch_row($query);
$xmlobj = new parseXML($row[0]);
$company_name = $xmlobj->value('/content/company_name');

$stype = pgParam("stype", null);
$type = pgParam("type", null);
$yy = pgParam("yy", null);
$mm = pgParam("mm", null);
$dd = pgParam("dd", null);
if($stype == null || $type == null) {
    die("請重新查詢統計");
}

$maxnum = 0;
$maxkey = 0;

$papertitle = "";
$paperdate = "";
if($type == "m") {
} else if($type == "d") {
}
if($type == "m") {
    $paperdate = "$yy 年月報表";
    //建立月份陣列
    $data = array();
    for($x = 1; $x <= 12; $x++) {
        if($x < 10) {
            $key = "0" . $x;
        } else {
            $key = "" . $x;
        }
        $data[$key] = 0;
    }
} else if($type == "d") {
    $paperdate = "$yy 年 $mm 月 日報表";
    $firstday = mktime(0, 0, 0, $mm, 1, $yy);
    $maxday = date('t', $firstday);
    $data = array();
    ;
    for($x = 1; $x <= $maxday; $x++) {
        if($x < 10) {
            $key = "0" . $x;
        } else {
            $key = "" . $x;
        }
        $data[$key] = 0;
    }
}
if($stype == "from") {
    $orderbytype = pgParam("orderbytype", null);
    $orderby = pgParam("orderby", null);
    $papertitle = "網站流量導引";
    if($type == "m") {
        $paperdate = "$yy 年 $mm 月 月報表";
        $sql = "select url, sum(frequency) as frequency from " . $CFG->tbext . "counter_from where yy='$yy' and mm='$mm' ";
        $sql .= " group by url";
        $sql .= " order by " . $orderbytype . " " . $orderby;
    } else if($type == "d") {
        $paperdate = "$yy 年 $mm 月 $dd 日 日報表";
        $sql = "select url, sum(frequency) as frequency from " . $CFG->tbext . "counter_from where yy='$yy' and mm='$mm' and dd='$dd'";
        $sql .= " group by url";
        $sql .= " order by " . $orderbytype . " " . $orderby;
    }
    $result = sql_query("select max(frequency) as frequency from (" . $sql . ")a");
    $row = sql_fetch_array($result);
    $maxnum = $row['frequency'];
    $paperorder = "依照";
    if($orderbytype == "url") {
        $paperorder .= " 來源網站 ";
    } else {
        $paperorder .= " 次數 ";
    }
    if($orderby == "desc") {
        $paperorder .= " 由大到小";
    } else {
        $paperorder .= " 由小到大";
    }
    $paperorder .= " 進行排序";
    
} else if($stype == "visit") {
    $papertitle = "網站訪客人數";
    if($type == "m") {
        $sql = "select mm, sum( frequency ) AS frequency from " . $CFG->tbext . "counter_visit where yy='$yy' group by mm";
        //echo "$sql<br>";
        $result = sql_query($sql);
        while($row = sql_fetch_array($result)) {
            $key = $row['mm'];
            $data[$key] = $row['frequency'];
            if($row['frequency'] > $maxnum) {
                $maxnum = $row['frequency'];
                $maxkey = $key;
            }
        }
    } else if($type == "d") {
        $sql = "select dd, frequency from " . $CFG->tbext . "counter_visit where yy='$yy' and mm='$mm' group by dd";
        //echo "$sql<br>";
        $result = sql_query($sql);
        while($row = sql_fetch_array($result)) {
            $key = $row['dd'];
            $data[$key] = $row['frequency'];
            if($row['frequency'] > $maxnum) {
                $maxnum = $row['frequency'];
                $maxkey = $key;
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$company_name?> 訪客流量統計報表</title>
<link type="text/css" rel="stylesheet" href="screen.css" media="screen" />
<link type="text/css" rel="stylesheet" href="print.css" media="print" />
<script>
function print_page() {
  window.print();
}
function close_page() {
  window.close();
}
</script>
</head>
<body>
<div style="position: absolute;top:2px;right:2px;" id="btnarea">
  <Button type="button" title="友善列印" class="button" onclick="print_page();"><div class="btn_no">友善列印</div></Button>
  <Button type="button" title="關閉視窗" class="button" onclick="close_page();"><div class="btn_no">關閉視窗</div></Button>
</div>
<div id="login_footer"><img src="../../images/worlddesign.png"/></div>
<div id="paperdate">製表日期：<?=date("Y-m-d");?></div>
  <div style="padding-left:20px;padding-right:10px;padding-top:40px;padding-bottom:10px;">
      <div class="logo"><img src="../../images/logo.gif" width="50px" height="50px"/></div>
      <div class="t3" style="float:left;padding-top:25px;padding-left:10px;"><?=$company_name?> - <?=$papertitle?> <?=$paperdate?></div>
      <div class="clearfloat"></div>
      <div class="cs"></div>
      <div>

<?php
if($stype == "visit" && $type == "m") {
?>
        <table class="equal">
         <tr><th class="td1 xth">月 份</th><th class="td2 xth">次 數 / 圖 表</th></tr>
<?php
    $per = 500;
    $addx = ($per / $maxnum);
    if($addx > 1) {
        $addx = 1;
    }
    for($x = 1; $x <= 12; $x++) {
        if($x < 10) {
            $key = "0" . $x;
        } else {
            $key = "" . $x;
        }
        $mw = ceil($data[$key] * $addx);
?>
<tr><td class="td1 tac"><?echo "$yy 年 $key 月"?></td><td class="td2"><img src="<?=$CFG->url_admin?>images/bar_2.gif" width="<?=$mw?>" height="10">&nbsp;<?=$data[$key]?> 人</td></tr>
<?php
    }
} else if($stype == "visit" && $type == "d") {
?>

        <table class="equal">
         <tr><th class="td1 xth">日 期</th><th class="td2 xth">次 數 / 圖 表</th></tr>
<?
    $per = 500;
    $addx = ($per / $maxnum);
    if($addx > 1) {
        $addx = 1;
    }
    for($x = 1; $x <= $maxday; $x++) {
        if($x < 10) {
            $key = "0" . $x;
        } else {
            $key = "" . $x;
        }
        $mw = ceil($data[$key] * $addx);
?>
<tr><td class="td1 tac"><?echo "$mm 月 $key 日"?></td><td class="td2"><img src="<?=$CFG->url_admin?>images/bar_2.gif" width="<?=$mw?>" height="10">&nbsp;<?=$data[$key]?> 人</td></tr>
<?php
    }
} else {
?>
<div style="font-size:10pt;margin-top:10px;margin-left:15px;">※<?=$paperorder?></div>
 <table class="equal">
 <tr><th class="td1 xth">來 源 網 站</th><th class="td2 xth">次 數 / 圖 表</th></tr>

<?php
    $per = 500;
    $addx = ($per / $maxnum);
    if($addx > 1) {
        $addx = 1;
    }
    $result = sql_query($sql);
    while($row = sql_fetch_array($result)) {
        
        $mw = ceil($row['frequency'] * $addx);
?>
<tr><td class="td1 tac"><?=$row['url']?></td><td class="td2"><img src="<?=$CFG->url_admin?>images/bar_2.gif" width="<?=$mw?>" height="10">&nbsp;<?=$row['frequency']?> 次</td></tr>

<?php
    }
}
?>
        </table>
      </div>
    </div>
</div>
<br>
</body>