<?php
Global $CFG,$pageid;
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();

// 次分類 & 項目
$sql = "SELECT * FROM ".$CFG->tbext."product_cate where inuse=1 and pid>0 order by seq asc";
$resc = @sql_query($sql);
while($cate = @sql_fetch_assoc($resc)){

  // servie
  $cateid = $cate["id"];
  $sql = "SELECT * FROM ".$CFG->tbext."product where inuse=1 and cateId='$cateid' order by seq asc";
  $res = @sql_query($sql);
  $num = @sql_num_rows($res);
  if ($num>0) {
    $data = array(
      "category"=>$cate["catename"],
      "id"=>$cate["pid"],
    );
    $tpl->newBlock("dataProduct");
    $tpl->assign($data);
  }
  while($row = @sql_fetch_assoc($res)){
    $xmlvo = new parseXML($row['imagexml']);
    $cover = $xmlvo->value('/content/cover');
    $data = array(
      "no"=>$row["no"],
      "name"=>$row["title"],
      "img"=>!empty($cover) ? $CFG->url_web."archive/images/product/".$cover : '',
    );
    $tpl->newBlock("items");
    $tpl->assign($data);
  }
}


$tpl -> printToScreen();
?>
