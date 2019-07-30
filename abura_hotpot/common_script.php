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

//取得預設標題
$nowpage = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1);
$sql = "select * from ".$CFG->tbext."webconfig where id='header_info'";
$query = @sql_query($sql);
if ($query != null){
    $arr_header = @sql_fetch_array($query);
    if ($arr_header!= null){
        $xmlobj = new parseXML($arr_header["xmlcontent"]);
        $default_endbodyscript = $xmlobj->value('/content/endbodyscript_tw');

        if (!empty($pageid)){
            $now_endbodyscript = $xmlobj->value('/content/'.$pageid.'/endbodyscript_tw');
        }else if ($nowpage=="default.php" || $nowpage=="index.php"){
            $now_endbodyscript = $xmlobj->value('/content/index_endbodyscript_tw');
        }
    }
}

$tpl -> assignGlobal(array(
    "footerjs"=>$now_endbodyscript,
    "default-footerjs"=>$default_endbodyscript,
));


$tpl -> printToScreen();
?>
