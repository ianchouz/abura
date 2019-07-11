<?php
if(!isset($CFG)){
    include_once("../../applib.php");
}

//set
$incSet["work"]    = array("maxFiles"=>6 ,"set"=>$CFG->aboutAlbum   ,"action"=>array("edit"=>true,"cropb"=>true,"crops"=>true,"del"=>true));
$incSet["info"]    = array("maxFiles"=>6 ,"set"=>$CFG->aboutAlbum   ,"action"=>array("edit"=>true,"cropb"=>true,"crops"=>true,"del"=>true));
$table = $CFG->tbext . "webconfig_photo";
$menu_id = "about";
$baseRoot = $CFG->aboutAlbum["root_album"];
$webRoot = $CFG->aboutAlbum["web_album"];

$mainid =$menu_id;
if($mainid == '')  $mainid = $_REQUEST["mainid"];

$typid="";
if($_REQUEST["typid"])  $typid = $_REQUEST["typid"];


$folder   = $mainid."/";
$fullDir = $baseRoot.$folder;
__chkdir($fullDir);
$fullsDir = $baseRoot.$folder."s/";
__chkdir($fullsDir);
$webfullDir =  $webRoot.$folder;
$webfullsDir = $webRoot.$folder."s/";

if($typid){
	$uSet->use_overlay = true; // 是否使用工具列, default: true;
	$uSet->maxFiles = $incSet[$typid]["maxFiles"]; // 上傳數量限制, default: 999;
	$uSet->use_cut_s = $incSet[$typid]["action"]["crops"]; // 同時剪裁大圖小圖, default: false;

	$m_width=$incSet[$typid]['bw'];
	$m_height=$incSet[$typid]['bh'];
	$s_width=$incSet[$typid]['sw'];
	$s_height=$incSet[$typid]['sh'];	
}
?>