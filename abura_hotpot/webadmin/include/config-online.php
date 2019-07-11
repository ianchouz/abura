<?
header('Content-Type:text/html;charset=UTF-8');
error_reporting(E_ERROR);
//設定時區
date_default_timezone_set('Asia/Taipei');
$_config_ver = '20120315.2';
class object {};
$CFG = new object;
//Session定義
$CFG->sessionname = 'nell'; //請使用英文字就好,不要帶有其他符號
//資料庫連線設定
$CFG->dbhost = 'localhost';//
$CFG->dbname = 'cosmo_gios';//資料庫名稱
$CFG->dbuser = 'cosmo_gios';//資料庫帳號
$CFG->dbpass = '9vvPs1&2';//資料庫密碼
//==========================================
//網站檔案資料夾名稱
$CFG->doc_web = '';//以IP瀏覽的時候帶的資料夾目錄
$CFG->real_domain = 'gios.cosmo-br.tw';//真實域名，不帶有 http 喔!!
$CFG->full_domain = 'http://gios.cosmo-br.tw/'; //程式用的網站路徑，包含完成的http及域名或IP+目錄
$CFG->base_domain = 'http://gios.cosmo-br.tw/';//用在後台登入預設語言位置60.249.71.45/
//==========================================
//後台語言版本設定
//==========================================
$langs = array();
$langs["tw"]  = array("val"=>"tw","url"=>$CFG->base_domain."webadmin/login.php","name"=>"繁體中文");
// $langs["eng"] = array("val"=>"eng","url"=>$CFG->base_domain."eng/webadmin/login.php","name"=>"ENGLISH");
//$langs["ch"]  = array("val"=>"ch","url"=>$CFG->base_domain."ch/webadmin/login.php","name"=>"简体中文");
$CFG->langs = $langs;
$CFG->langs_show="_tw";

//==========================================
$CFG->domain = '/';//固定
$CFG->visit_host = $_SERVER['HTTP_HOST'];
//自動判斷使用者是用IP訪問還是用域名
$tmpvi = str_replace('.','',$CFG->visit_host);
if (is_numeric($tmpvi) === false && $tmpvi !='localhost'){//用域名
  $CFG->visit_type = 'domain';
  //$CFG->doc_web = '';
  $CFG->real_domain = $CFG->visit_host;
  $CFG->reldomain = 'http://'.$CFG->real_domain.'/'; //包含域名的domain
}else{
  $CFG->visit_type = 'ip';
  $CFG->reldomain = 'http://'.$CFG->visit_host.'/';
}
//後台管理
$CFG->doc_admin = 'webadmin/';
//預覽網頁手頁設置
$CFG->url_index = 'index.php';
//語言版本設置
$CFG->language='tw';
//資料表前置符號
$CFG->tbext = 'tw_';
//後台管理者使用資料表前置符號
$CFG->tbuserext = 'tw_';
include dirname(__FILE__).'/config_d.php';
?>
