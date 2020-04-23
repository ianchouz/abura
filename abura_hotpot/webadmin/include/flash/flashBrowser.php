<?php
/*******************************
*產生Json的Flash清單
********************************/
class Flash {
    public $name = "";
    public $url = "";
    public $topdir = "";
    function __construct($name,$url,$topdir) {
        $this->name = $name;
        $this->url = $url;
        $this->topdir = $topdir;
    }
}
class Flashs {
    protected $Flashs = array();
    function add($name,$url,$topdir) {
        $n = new Flash($name,$url,$topdir);
        $this->Flashs[] = $n;
    }
    function toJson() {
        return json_encode($this->Flashs);
    }
}

include '../../applib.php';

$AutorisedFlashType = array ("swf");

function load_file_list($listdir,$filterkey=""){
	global $AutorisedFlashType;
	$imagesarr = array();
  if (is_dir($listdir)){
  $dh = opendir($listdir);
  chdir ($listdir);
  while (false !== ($filename = readdir($dh))) {
    if (is_dir($filename) && basename($filename)!='.' && basename($filename)!='..'){
      continue;
    }else if (basename($filename)!='.' && basename($filename)!='..'){
    	if ($filterkey !=""){
    		if (strpos($filename,$filterkey)===false){
    			continue;
    		}
    	}
      $ext = substr($filename, strpos($filename, ".")+1, strlen($filename));
      $ext = strtolower($ext);
      if( in_array($ext, $AutorisedFlashType) ) {
      	$imagesarr[] = $filename;
      }else{
      	continue;
      }
    }
  }
    asort($imagesarr);
  }
  return $imagesarr;
}



//取得處理的目錄
$gdir = $_POST["gdir"];
if (!isset($gdir)){
  $gdir = $_GET["gdir"];
}
$pdir = $_POST["pdir"];
if (!isset($pdir)){
  $pdir = $_GET["pdir"];
}

$FlashsList = new Flashs();

//目前完整目錄
$topdir = "";
//有父層
if (isset($pdir) && !empty($pdir)){
  $topdir = $pdir."/";
}else{
  $pdir = "";
}
//有指定下層目錄
if (isset($gdir) && !empty($gdir) ){
  $topdir = checkDIR($pdir,"/",true).checkDIR($gdir,"/",true);
  //拆解父層
  $tmpdir = dealPath($pdir, "/");
  $pdir =$tmpdir[0];
  $gdir =$tmpdir[1];
  $localDir = $CFG->root_user.$topdir;
}else{
  $topdir = $gdir;
  $localDir = $CFG->root_user;
}

//每頁筆數
$pagesize = $_REQUEST["pagesize"];
if (empty($pagesize)){
	$pagesize = 50;
}
//總頁數
$totalpages = 0;
//查詢結果筆數
$numrows = 0;
//目前第幾頁(查詢頁數)
$page = $_REQUEST["page"];
if (empty($page)){
	$page = 1;
}
$filterkey = $_REQUEST["filterkey"];
if (empty($filterkey)){
	$filterkey = "";
}

//起始位置
$offset = 0;


$getimages = load_file_list($localDir,$filterkey);

$numrows = count($getimages);
//計算
if ($numrows < $pagesize){
	$pagesize = $numrows;
}
if ($pagesize!=0){
$totalpages = ceil($numrows / $pagesize);
}else{
	$totalpages = 0;
}
// 若過當前的頁數大於頁數總數
if ($page > $totalpages) {
  // 把當前頁數設定為最後一頁
  $page = $totalpages;
}
// end if
// 若果當前的頁數小於 1
if ($page < 1) {
  // 把當前頁數設定為 1
  $page = 1;
}
// end if
// 根據當前頁數計算名單的起始位置
$offset = ($page - 1) * $pagesize;
$endset = $offset + $pagesize;
if ($endset>$numrows){
	$endset = $numrows;
}
for($x=$offset;$x < $endset;$x++){
	$filename = $getimages[$x];
  $filesizes = filesize( $localDir.$filename);
  if ($filesizes!=0){
  	$FlashsList->add($filename,$CFG->web_user.$topdir.$filename,$topdir);
  }
}
echo '{"images":'.$FlashsList->toJson().',"num":'.$numrows.',"totalpages":'.$totalpages.',"page":'.$page.',"offset":'.$offset.'}';
?>