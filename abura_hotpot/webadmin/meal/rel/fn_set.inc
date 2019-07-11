<?php
if(!isset($CFG)){
    include_once("../../applib.php");
}


##資料列表

$rel_cate_sel=true;
$sql_List = "SELECT id,title,(select catename from ".$CFG->tbext."product_cate where ".$CFG->tbext."product.cateId = ".$CFG->tbext."product_cate.id) as catename
  FROM ".$CFG->tbext."product where id in (select rel_id from ".$CFG->tbext."product_rel where product_id = '".$dao->id."')";
$rel_cate=array();

if (class_exists("main")) {
     $rel_cate=main::_loadCategoryList($CFG->tbext."product_cate");
}

//include_once("../include/model/controller.php");
//use Mctrl\controller as reg; 
//use Mctrl\controller as Another;
//
/*try {
  
} catch (Exception $e) {
    
}
*/
?>