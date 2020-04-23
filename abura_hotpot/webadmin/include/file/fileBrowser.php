<?php
/*******************************
*產生Json的Files清單
********************************/
class Files {
    public $name = "";
    public $width = "";
    public $height = "";
    public $size = "";
    public $url = "";
    public $topdir = "";
    function __construct($name,$width,$height,$size,$url,$topdir) {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->size = $size;
        $this->url = $url;
        $this->topdir = $topdir;
    }
}
class Filess {
    protected $Filess = array();
    function add($name,$width,$height,$size,$url,$topdir) {
        $n = new Files($name,$width,$height,$size,$url,$topdir);
        $this->Filess[] = $n;
    }
    function toJson() {
        return json_encode($this->Filess);
    }
    function getNum(){
    	return count($this->Filess);
    }
}

include '../../applib.php';

$AutorisedFilesType = array('php','exe','inc','js');

function load_file_list($listdir,$filterkey=""){
	global $AutorisedFilesType;
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
      if( in_array($ext, $AutorisedFilesType) ) {
      	continue;
      }else{
        $imagesarr[] = $filename;
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

$FilessList = new Filess();

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
  list($width, $height, $type, $attr) = @getimagesize( $localDir.$filename );
  if ($width!=0){
    $FilessList->add($filename,$width,$height,$filesizes,$CFG->web_user.$topdir.$filename,$topdir);
  }else if ($filesizes!=0){
  	$FilessList->add($filename,0,0,$filesizes,$CFG->web_user.$topdir.$filename,$topdir);
  }
}
echo '{"images":'.$FilessList->toJson().',"num":'.$numrows.',"totalpages":'.$totalpages.',"page":'.$page.',"offset":'.$offset.'}';
?>