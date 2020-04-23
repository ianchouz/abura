<?php
include_once("applib.php");
include_once("header.php");
?>

<?php
include_once("contact_us/innerdao.php");
if (checkAuthority($menu_id,false)){
    $sql = "SELECT * FROM ".$CFG->tbext."contact_us where 1=1 and (read_status is null or read_status <> 'Y') order by create_time desc";
    $contact_usresult = sql_query($sql);
    $contact_ussize = sql_num_rows($contact_usresult);
?>
<div style="margin:20px;">
    <div class="x-panel-header" >您有 <?echo $contact_ussize?> 則【連絡我們】未讀取</div>
    <div class="x-panel-body">
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:50px;">項次</th>
                <th class="x-th" style="width:120px;">日期</th>
                <th class="x-th" style="width:100px;">姓名</th>
                <th class="x-th" style="width:100px;">聯絡電話</th>
                <th class="x-th">電子郵件</th>
            </tr>
            <?php
            $i=1;
            while ($contact_usitem = sql_fetch_array($contact_usresult)) {
                $vo = new contact_us();
                $vo->setItem($contact_usitem);
            ?>
            <tr class="x-tr<? echo ($i % 2)?'1':'2'?>">
                <td class="x-td"><?echo $i?></td>
                <td class="x-td al"><div class="newbtn" onclick="javascript:runContactShow(<?echo $vo->id?>)"><?=date("Y/m/d H:i",strtotime($vo->create_time))?></div></td>
                <td class="x-td al"><?echo $vo->val("name")?><?=$vo->val("sex")?></td>
                <td class="x-td al"><?echo $vo->val("phone")?></td>
                <td class="x-td al"><a href="mailto://<?=$vo->val("mail")?>"><?=$vo->val("mail")?></a></td>
            </tr>
            <?php $i++;}
            ?>
        </table>
    </div>
</div>
<?php } ?>


<?php /*
if (checkAuthority('shoppingcar',false)){
$sql = "SELECT orders . * , (
CASE WHEN paytype.payName IS NULL
THEN orders.payType
ELSE paytype.payName
END
) AS payName
FROM ".$CFG->tbext."orders orders
LEFT JOIN ".$CFG->tbext."orders_paytype paytype ON ( paytype.id = orders.payType )
WHERE orderStatus IN ('新訂單','交易成功','訂單成立') AND (logistics_status IS NULL OR logistics_status='未出貨' )
OR readflag = false
ORDER BY orderTime, readflag";
$ordersresult = @sql_query($sql);
$orderssize = @sql_num_rows($ordersresult);
?>
<div style="margin:20px;">
    <div class="x-panel-header" >
        您有 <?echo $orderssize?> 筆 新訂單 或 未讀取 或 未出貨
    </div>
    <div class="x-panel-body">
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:50px;">項次</th>
                <th class="x-th" style="width:120px;">訂單編號</th>
                <th class="x-th" style="width:120px;">訂單狀態</th>
                <th class="x-th" style="width:130px;">訂購日期</th>
                <th class="x-th">訂購者</th>
                <th class="x-th" style="width:120px;">付款方式</th>
                <th class="x-th" style="width:120px;">付款狀態</th>
                <th class="x-th" style="width:80px;">金額</th>
                <th class="x-th" style="width:80px;">讀取狀態</th>
            </tr>
            <?php
            $i=1;
            while ($orders_item = sql_fetch_array($ordersresult)) {
            ?>
            <tr class="x-tr<? echo ($i % 2)?'1':'2'?>">
                <td class="x-td"><?echo $i?></td>
                <td class="x-td"><div class="newbtn" onclick="javascript:location.href='shoppingcar/show.php?orders_id=<?echo $orders_item["orders_id"]?>'";><?=$orders_item["orders_id"]?></div></td>
                <td class="x-td"><?=$orders_item["orderStatus"]?></td>
                <td class="x-td"><?=date("Y-m-d H:i",strtotime($orders_item["orderTime"]))?></td>
                <td class="x-td" style="line-height:15px"><?=$orders_item["order_name"]?></td>
                <td class="x-td" style="line-height:15px"><?=$orders_item["payName"]?></td>
                <td class="x-td" style="line-height:15px"><?php
                if (!empty($orders_item["payStatus"])){
                    echo "<span style='font-size:9px;line-height:9px;color:red;'>".$orders_item["payStatus"]."<br>".$orders_item["payTime"]."</span>";
                }
                php?></td>
                <td class="x-td" style="line-height:15px;text-align:right;padding-right:10px;">$<?=number_format($orders_item["orderPrice"])?></td>
                <td class="x-td"><?php
                if ($orders_item["readflag"]){
                    echo "<span style='font-size:9px;line-height:9px;'>".$orders_item["readname"]."<br>".date("Y-m-d H:i",strtotime($orders_item["readdate"]))."</span>";
                }else{
                    echo "<span style='font-size:10px;color:red;'>未讀取</span>";
                }?></td>
             </tr>
            <?php
            $i++;
            }?>
    </table>
  </div>
</div>
<?php }*/?>

<?php

//$today = date("Y-m-d");
$sql = "select sum(frequency) as m from ".$CFG->tbext."counter_visit where yy='".date("Y")."' and mm='".date("m")."'";
$result = sql_query($sql);
$r = sql_fetch_row($result);
$monthcount = ($r[0]==""?0:$r[0]);

$sql = "select sum(frequency) as m from ".$CFG->tbext."counter_visit where yy='".date("Y")."' and mm='".date("m")."' and dd='".date("d")."'";
$result = sql_query($sql);
$r = sql_fetch_row($result);
$daycount = ($r[0]==""?0:$r[0]);

if ($daycount<100){
    $dw = $daycount;
}
if ($monthcount<100){
    $mw = $monthcount;
}
?>
<div style="margin:20px;">
    <div class="x-panel-header" >
        網站流量統計
    </div>
    <div class="x-panel-body">
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:100px;">本日訪客</th>
                <td class="x-td"  style="text-align:left;padding-left:2px;"><img src="<?echo $CFG->url_admin?>images/bar_2.gif" width="<?echo $dw?>" height="10">&nbsp;<?echo $daycount?>人次</td>
            </tr>
            <tr class="x-tr2">
                <th class="x-th" style="width:100px;">本月訪客</th>
                <td class="x-td"  style="text-align:left;padding-left:2px;"><img src="<?echo $CFG->url_admin?>images/bar_2.gif" width="<?echo $mw?>" height="10">&nbsp;<?echo $monthcount?>人次</td>
            </tr>
        </table>
    </div>
</div>
<?php  include('footer.php'); ?>
<script>
function runContactShow(id){
    wait_msg("請稍等...");
    location.href="contact_us/show.php?id="+id;
}
</script>
