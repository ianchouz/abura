<?php
include_once("fn_set.inc");
global $rel_cate_sel;
if(!isset($CFG)){
    include_once("../../applib.php");
}

##資料查詢
$cid = pgParam("cid", '');
$keyword = pgParam("keyword", '');
$typ = pgParam("typ", '');
$sql = "SELECT *,(select catename from ".$CFG->tbext."product_cate where ".$CFG->tbext."product.cateId = ".$CFG->tbext."product_cate.id) as catename  FROM " . $CFG->tbext . "product  where 1 ";

if($keyword != "") {
    $vv = sql_real_escape_string($keyword);
    $sql .= " and (title like '%" . $vv . "%'" . ")";
}
if ($myid !=""){
    $sql .= " and id <> ".$myid;
}
 if ($cid !="" && $cid !="-1"){
    $sql .= " and cateId = ".$cid;
  }
$sql .= " order by seq desc";
$sql_Search=$sql;


?>
<table style="border-collapse: collapse;background:#FFFFFF;  " width="100%" id="list" class="x-table">
    <tr>
        <th class="nth" width="50" align="center">&nbsp;</th>
        <th class="nth" align="left">名稱</th>
    </tr>
    <?php
    $query = @sql_query($sql_Search);
    $i=0;
    while($dbrow = @sql_fetch_assoc($query)){
        $title=$dbrow['title'];
        if($rel_cate_sel)$title=$dbrow['catename']."-".$title;
    ?>
    <tr class="x-tr<?=($i % 2)?'1':'2'?>" id="list_product_<?=$dbrow['id']?>_<?=$typ?>">
        <td><input type="button" class="btn" value="選取" onclick="addrel('<?=$dbrow['id']?>','<?=$title?>','<?=$typ?>')"></td>
        <td><?=$title?></td>
    </tr>
    <?php
    $i++;
    }
?></table><?php
?>