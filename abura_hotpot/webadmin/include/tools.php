<?php
$_tool_ver='20130722.1';

/*取得 webconfig 資料*/
function getConfig($configid){
    global $CFG;
   // $sql   = "select * from ".$CFG->tbext."webconfig where id='indexset'";
    $sql = "select * from ".$CFG->tbext."webconfig where id='$configid' limit 0,1";
    $query = @mysql_query($sql);
    return @mysql_fetch_assoc($query);
}
function getConfigNew($id){
    global $CFG;
    $strSQLQ = "select * from ".$CFG->tbext."webconfig where id=".pSQLStr($id);
    $query = sql_query($strSQLQ);
    $row = sql_fetch_array($query);

    return $row['xmlcontent'];
}
/*設定 webconfig 資料*/
function getImg($path="",$set="",$cover="",$d4Img="",$other="") {
    global $CFG;
    $img="";
    $imgsrc="";
    if ($cover !="" && $path !="" && $set !=""){
        $img = getImage($CFG->{$set}["w"],$CFG->{$set}["h"],$cover,$CFG->{$path}["path"],false,false,$other,false,false);
        if($img) $imgsrc = $CFG->web_user.$CFG->{$path}["path"].$cover;
    }else if($d4Img){
        $img="<img src=\"".$d4Img."\" $other >";
        $imgsrc="$d4Img";
    }
    return array("img"=>$img,"src"=>$imgsrc);
}

  function getImage($width,$height,$img,$path="",$usestyle=true,$useunknow=true,$othattr="",$usehand=false,$usepaddingtop=false,$imageroot = "",$imageweb = ""){
    $setwidth = $width;
    $setheight = $height;

    if ($width==0){
      $width = 1000;
    }
    if ($height==0){
      $height = 1000;
    }
    global $CFG;
    if ($imageroot==""){
      $imageroot = $CFG->root_user;
    }
    if ($imageweb==""){
      $imageweb = $CFG->web_user;
    }
    $imageweb = str_replace($CFG->domain,'/',$imageweb);

  	$pos = strpos ($img, ".swf");
	  if ($pos === false) {
  	  $imagestr = "";
  	  if ($img!=""){
	      $reimg = new reSizeImage($width,$height,$imageroot.$path.$img);
	      $data = 'data-ow="'.$reimg->width.'" data-oh="'.$reimg->height.'"';
	      if ($usestyle){
	      	$style=" style='";
	      	if ($setheight!=0){
	      	  $style .= ($usepaddingtop?"padding-":"")."top:".$reimg->thumbtop."px;";
	      	}
	      	if ($setwidth!=0){
	      	  $style .= ($usepaddingtop?"padding-":"")."left:".$reimg->thumbleft."px;";
	      	}
	      	$style .="width:".$reimg->thumbwidth."px;height:".$reimg->thumbheight."px;".(($usehand)?"cursor:pointer;":"")."'";
	      }
        $imagestr = "<img $othattr  $data src=\"".$imageweb.$path.$img."\" $style  border='0'/>";
      }else if ($useunknow){
      	$imagestr = '<img '.$othattr.' '.$data.' src="'.$CFG->unknowimg.'" width="'.$width.'" height="'.$height.'" border="0"/>';
      }
      return $imagestr;
    }else{
    	return getFlash($width,$height,$img,$path,$useunknow);
    }
  }
function setconfig($id,$xml){
    global $CFG;
    $strSQLQ = "select * from webconfig where id=".pSQLStr($id);
    $query = sql_query($strSQLQ);
    $row = sql_fetch_array($query);

    if($row['id']!=''){
        //更新
        $strSQL = "update webconfig set xmlcontent=".pSQLStr($xml)." where id=".pSQLStr($id);
        return sql_query($strSQL);
    }else{
        //新增
        $strSQL = "insert into webconfig (xmlcontent,id) values("
               ." ".pSQLStr($xml)
               ." ,".pSQLStr($id).")";
        return sql_query($strSQL);
    }
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function base64url_decode($data) {
    if(endsWith($data,'==')){
      return base64_decode($data);
    }
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
class debugTimer{
    function __construct($tit = '') {
        $this->t1 = time();
        $this->ppstr($tit.'=>'.date("H:i:s",$this->t1));
    }
    function start($tit = ''){
        $this->subject = $tit;
        $this->t2 = time();
    }
    function end(){
        $this->t3 = time();
        $toto = ($this->t3 - $this->t2)/1000;

        $this->ppstr($this->subject.' 共'.$toto.'秒');
    }

    function ppstr($msg){
        if(!empty($_GET['showlog'])){
          echo $msg.'<br>';
        }
        // global $CFG;
        // if(!is_dir($CFG->root_web."log/")){
        //   mkdir($CFG->root_web."log/", 0777);
        // }
        // $filename = $CFG->root_web."log/".date("Y-m-d").'_'.$CFG->web.".log";
        // $logstr = date("Y-m-d H:i:s")." \t ".$msg."\r\n";
        // if ( !($fp = fopen($filename, "a")) ) {
        //    //die( "無法開啟檔案");
        // } else {
        //    fwrite ($fp, $logstr);
        //    fpassthru ($fp);
        // }
    }
}
function curPageName($BringV = false ,$val="") {
    if($BringV){
        $tmp=$_SERVER['QUERY_STRING'];
        if($val!="" && $tmp!="" ) $val.="&";
        $val.= $tmp;
    }
    $str= substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1).($val!=''?'?'.$val:'');
    return $str;
}

/*判斷是否為手機瀏覽器*/
function isMobile(){
    $mobile_browser = '0';
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|ios|mobi)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
        $mobile_browser++;
    }
    if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda','xda-','Googlebot-Mobile');

    if(in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
        $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
        $mobile_browser=0;
    }

    if($mobile_browser>0) {
        return true;
    }else {
        return false;
    }
}

/*取得操作者IP*/
function getUserIp(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $myip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $myip= $_SERVER['REMOTE_ADDR'];
    }
    return $myip;
}

function URLStrMake($link){
    if ($link=='') return '';
    $scheme="";
    $link2 = trim( $link);
    if( preg_match ("/^((http:|https:|ftp:)/*)/",  $link, $res) ){
        $schemefull=$res[1];
        $scheme=$res[2];
        $link= "{$scheme}//". preg_replace  ( "/^((http:|https:|ftp:)/*)/" ,"" ,$link );
        return $link;
    }
    return "http://".$link;
}

function getShoppingContent($order_id){
    global $CFG;
    $url = $CFG->full_domain.$CFG->doc_admin."shoppingcar/form_orderdetail.php?mail=true&orders_id=".$order_id;
    return getURLContent($url);
}

function getContactUsContent($id){
    global $CFG;
    $url = $CFG->full_domain.$CFG->doc_admin."contact_us/form_list.php?mail=true&id=".$id;
    return getURLContent($url);
}

function getDeliveryContent($id){
    global $CFG;
    $url = $CFG->full_domain.$CFG->doc_admin."contact_us/form_list.php?mail=true&id=".$id;
    return getURLContent($url);
}

function getURLContent($url){
    try{
        $connection_timeout = 3;
        $context = stream_context_create(array(
        'http' => array(
        'timeout' => $connection_timeout)));
        $data = @file_get_contents($url, null, $context);
        if ($data === false) {
            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, $connection_timeout);
                $data = curl_exec($ch);
            } else {
                throw new Exception('抱歉，網站系統不支援CURL，請聯絡網站管理者!!');
            }
        }
    } catch (Exception $e) {
        return '無法取得內容:'.$e->getMessage();
    }
    return $data;
}

function buildMsg($msg,$url=''){
    echo '<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <script>
    alert("'.htmlspecialchars($msg).'");
    ';
    if ($url!=''&& $url!='close'){
    echo 'location.href = "'.$url.'";';
    }else if ($url=='close'){
    echo 'self.close();';
    }
    echo '</script>';
    echo htmlspecialchars($msg).'<br>';
    die('</body></html>');
}

/*function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}*/
function format_bytes($size) {
    if(!$size)return array();
    $size_unit = array('Bytes','KB','MB','GB','TB','PB','EB','ZB','YB');
    //進行簡化除算
    $flag = 0;
    while($size >= 1024){
        $size = $size / 1024;
        $flag++;
    }

    //回傳大小與單位
    return array(
        'size' => number_format($size,$decimal),
        'unit' => $size_unit[$flag]
    );
}
function endOutput($endMessage){
    ignore_user_abort(true);
    set_time_limit(0);
    header("Connection: close");
    header("Content-Length: ".strlen($endMessage));
    echo $endMessage;
    echo str_repeat("\r\n", 10); // just to be sure
    flush();
}
/*編輯器<->資料庫，將html圖片的位置轉換成變數
  src="images/
  src="$CFG->full_domain
  ==>%imgsrcwebpath%                                 */
function html2db($content,$allow = false){
    global $CFG;
    $replace_arr = array('src="mobile/images/','src="images/','src="'.$CFG->full_domain,'<script','<Script','<SCRIPT');
    $var_str = array('src="%imgsrcwebpath%mobile/images/','src="%imgsrcwebpath%images/','src="%imgsrcwebpath%','','','');
    return str_replace($replace_arr, $var_str, $content);
}

/*編輯器<->資料庫，將變數轉換成html圖片的位置
  %imgsrcwebpath%
  $CFG->full_domain                                   */
function db2html($content){
    global $CFG;
    $replace_arr = array('%imgsrcwebpath%');
    $var_str = array($CFG->full_domain);
    return str_replace($replace_arr, $var_str, $content);
}

function strip_only_tags($str, $tags, $stripContent = FALSE) {
    $content = '';
    if (!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if (end($tags) == '') {
            array_pop($tags);
        }
    }
    foreach($tags as $tag) {
        if ($stripContent) {
            $content = '(.+<!--'.$tag.'(-->|\s[^>]*>)|)';
            echo '    '.htmlspecialchars($content).'<br>';
            echo '='.$tag.'<br>';
        }
        $str = preg_replace('#<!--?'.$tag.'(-->|\s[^>]*>)'.$content.'#is', '', $str);
    }
    return $str;
}


function fdate($ndate,$f="Y/m/d"){
    if (empty($ndate)){
        return "";
    }else{
        return date($f, strtotime($ndate));
    }
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) {
        @chmod($dir, 0777);
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . "/" . $item)) {
            @chmod($dir . "/" . $item, 0777);
            if (!deleteDirectory($dir . "/" . $item)) return false;
        }
    }
    return rmdir($dir);
}

function getFileSize($file){
    $file_size = @filesize($file);
    if ($file_size <=0){
        return "";
    }
    if($file_size < 1024) {
        $FileSize = (string)$file_size . "字節";
    }elseif($Upfile_size <(1024 * 1024)) {
        $FileSize = number_format((double)($file_size / 1024), 1) . " KB";
    }else {
        $FileSize = number_format((double)($file_size/(1024*1024)),1)."MB";
    }
    return $FileSize;
}

function delhtml($mystr){
    $search = array ('@ ]*?>.*?@si', // Strip out javascript
         '@<[\/\!]*?[^<>]*?>@si',     // Strip out HTML tags
         '@([\r\n])[\s]+@',        // Strip out white space
         '@&(quot|#34);@i',        // Replace HTML entities
         '@&(amp|#38);@i',
         '@&(lt|#60);@i',
         '@&(gt|#62);@i',
         '@&(nbsp|#160);@i',
         '@&(iexcl|#161);@i',
         '@&(cent|#162);@i',
         '@&(pound|#163);@i',
         '@&(copy|#169);@i',
         '@&#(\d+);@e');          // evaluate as php

    $replace = array ('',
         '',
         '\1',
         '"',
         '&',
         '<',
         '>',
         ' ',
         chr(161),
         chr(162),
         chr(163),
         chr(169),
         'chr(\1)');
    return preg_replace($search, $replace,$mystr);
}

/**字串擷取**/
function htmlSubString($content,$maxlen=300){
    $content = str_replace('&nbsp;',' ',$content);
    $maxlen = $maxlen*2;
    //把字元按HTML標籤變成陣列。
    $content = preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
    $wordrows=0; //中英字數
    $outstr=""; //生成的字串
    $wordend=false; //是否符合最大的長度
    $beginTags=0; //除<img><br><hr>這些短標籤外，其它計算開始標籤，如<div*>
    $endTags=0; //計算結尾標籤，如</div>，如果$beginTags==$endTags表示標籤數目相對稱，可以退出迴圈。
    //print_r($content);
    foreach($content as $value){
        if (trim($value)=="") continue; //如果該值為空，則繼續下一個值
        if (strpos(";$value","<")>0){
            //如果與要載取的標籤相同，則到處結束截取。
            if (trim($value)==$maxlen) {
                $wordend=true;
                continue;
            }
            if ($wordend==false){
                $outstr.=$value;
                if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
                    $beginTags++; //除img,br,hr外的標籤都加1
                }
            }else if (preg_match("/<\/([^>]+?)>/is",$value,$matches)){
                $endTags++;
                $outstr.=$value;
                if ($beginTags==$endTags && $wordend==true) break; //字已載完了，並且標籤數相稱，就可以退出迴圈。
            }else{
                if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
                    $beginTags++; //除img,br,hr外的標籤都加1
                    $outstr.=$value;
                }
            }
        }else{
            if (is_numeric($maxlen)){ //截取字數
                $curLength=getStringLength($value);
                $maxLength=$curLength+$wordrows;
                if ($wordend==false){
                    if ($maxLength>$maxlen){ //總字數大於要截取的字數，要在該行要截取
                        $outstr.=subString($value,0,$maxlen-$wordrows);
                        $wordend=true;
                    }else{
                        $wordrows=$maxLength;
                        $outstr.=$value;
                    }
                }
            }else{
                if ($wordend==false) $outstr.=$value;
            }
        }
    }
    //迴圈替換掉多餘的標籤，如<p></p>這一類
    while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)){
        $outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
    }
    //把誤換的標籤換回來
    if (strpos(";".$outstr,"[html_")>0){
        $outstr=str_replace("[html_&lt;]","<",$outstr);
        $outstr=str_replace("[html_&gt;]",">",$outstr);
    }
    //echo htmlspecialchars($outstr);
    $curLength=getStringLength($outstr);
    return $outstr;
}

//去掉多餘的空標籤
function strip_empty_html($matches){
    $arr_tags1=explode(" ",$matches[1]);
    if ($arr_tags1[0]==$matches[2]){ //如果前後標籤相同，則替換為空。
        return "";
    }else{
        $matches[0]=str_replace("<","[html_&lt;]",$matches[0]);
        $matches[0]=str_replace(">","[html_&gt;]",$matches[0]);
        return $matches[0];
    }
}

//取得字串的長度，包括中英文。
function getStringLength($text){
    $length = myccstr($text);
    return $length;
}

/***********按一定長度截取字串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
    return mysubstr2($text,$limit);
}

function myccstr($str){
    $chtsize = (strlen($str)-mb_strlen($str,"UTF-8"))/2;
    $othsize = mb_strlen($str,"UTF-8")-$chtsize;
    return $chtsize*2+$othsize;
}

function mysubstr2($str,$len,$append="..."){
    //計算總字數
    $sublen = $len;
    $newstr = $str;
    $nl = myccstr($newstr);
    $cc=0;
    $nsublen = $sublen;
    while($nl > $sublen){
        $cc++;
        $el = $nl - $len;
        $newstr = mb_substr($str, 0, $nsublen, 'UTF-8');
        $nsublen--;
        $nl = myccstr($newstr);
    }
    return $newstr.($cc!=0?$append:'');
}

function mysubstr($str,$len,$append="..."){
    //計算總字數
    $sublen = $len * 2;
    $newstr = $str;
    $nl = myccstr($newstr);
    $cc=0;
    $nsublen = $sublen;
    while($nl > $sublen){
        $cc++;
        $el = $nl - $len;
        $newstr = mb_substr($str, 0, $nsublen, 'UTF-8');
        $nsublen--;
        $nl = myccstr($newstr);
    }
    return $newstr.($cc!=0?$append:'');
}


function create_verifycode( $_obfuscate_ = 10 ){
    $_obfuscate_olwD8Q = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ123456789";
    $_obfuscate_QPWo2QnrzawrKQ = "";
    $_obfuscate_YQL3FlhPx8 = strlen( $_obfuscate_olwD8Q ) - 1;
    $_obfuscate_7w = 0;
    for ( ; $_obfuscate_7w < $_obfuscate_; ++$_obfuscate_7w ){
        $_obfuscate_Ybai = mt_rand( 0, $_obfuscate_YQL3FlhPx8 );
        $_obfuscate_QPWo2QnrzawrKQ .= $_obfuscate_olwD8Q[$_obfuscate_Ybai];
    }
    return $_obfuscate_QPWo2QnrzawrKQ;
}

class DateAccount{
    function __construct(){}
    function DateAdd ( $interval , $number , $date ) {
        $date_time_array = getdate ( $date );
        $hours = $date_time_array [ "hours" ];
        $minutes = $date_time_array [ "minutes" ];
        $seconds = $date_time_array [ "seconds" ];
        $month = $date_time_array [ "mon" ];
        $day = $date_time_array [ "mday" ];
        $year = $date_time_array [ "year" ];
        switch ( $interval ) {
            case "yyyy" : $year += $number ; break ;
            case "q" : $month +=( $number *3); break ;
            case "m" : $month += $number ; break ;
            case "y" :
            case "d" :
            case "w" : $day += $number ; break ;
            case "ww" : $day +=( $number *7); break ;
            case "h" : $hours += $number ; break ;
            case "n" : $minutes += $number ; break ;
            case "s" : $seconds += $number ; break ;
        }
        $timestamp = mktime ( $hours , $minutes , $seconds , $month , $day , $year );
        return $timestamp ;
    }
    function DateDiff ( $interval , $date1 , $date2 ) {
        $timedifference = $date2 - $date1 ;
        switch ( $interval ) {
            case "w" : $retval = bcdiv ( $timedifference ,604800); break ;
            case "d" : $retval = bcdiv ( $timedifference ,86400); break ;
            case "h" : $retval = bcdiv ( $timedifference ,3600); break ;
            case "n" : $retval = bcdiv ( $timedifference ,60); break ;
            case "s" : $retval = $timedifference ; break ;
        }
        return $retval ;
    }
}

function getWebconfig($id){
    $sql = "select xmlcontent from webconfig where id='$id'";
    $query = @sql_query($sql);
    if ($query){
        $row = @sql_fetch_row($query);
        $xmlcontent = $row[0];
    }else{
        $xmlcontent = "";
    }
    return new parseXML($xmlcontent);
}

function nvl( $chk, $nulldef= "" ){
    if ( isset( $chk ) ){
        return $chk;
    }
    return $nulldef;
}

function compare2Date($date1,$date2){
    $time1 = $date1 != '' ? strtotime($date1) : time();
    $time2 = $date2 != '' ? strtotime($date2) : time();
    return ceil(($time2 - $time1)/(3600*24));
}

function read_template( $filename, &$var ){
    $temp = str_replace( "\\", "\\\\", implode( file( $filename ), "" ) );
    $temp = str_replace( "\"", "\\\"", $temp );
    eval( "\$template = \"".$temp."\";" );
    return $template;
}

function convertcash($num, $currency=""){
    if (empty($num)|| $num==""){
        return "";
    }
    return $currency.number_format($num);
}

function checkProper(){
    global $CFG;
    //檢查是否從系統頁面進行轉址動作,若不是的話,則直接顯示錯誤訊息
    $formurl = $_SERVER['HTTP_REFERER'];
    $isok = ($formurl==""?false:true);
    if ($isok===false){
        die("sorry...please try login system again!!");
    }
}

function isProper(){
    global $CFG;
    //檢查是否從系統頁面進行轉址動作,若不是的話,則直接顯示錯誤訊息
    $formurl = $_SERVER['HTTP_REFERER'];
    $isok = ($formurl==""?false:true);
    //$isok = strpos ($formurl, $CFG->domain);
    if ($isok===false){
        return false;
    }else{
        return true;
    }
}

function IsAnimatedGif($filename){
    $fp = fopen($filename, 'rb');
    $filecontent = fread($fp, filesize($filename));
    fclose($fp);
    return strpos($filecontent,chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0') === FALSE?0:1;
}

class resize_img{
    var $image_path = '';
    //holds the image path
    var $sizelimit_x = 5000;
    //the limit of the image width
    var $sizelimit_y = 5000;
    //the limit of the image height
    var $image_resource = '';
    //holds the image resource
    var $keep_proportions = true;
    //if true it keeps the image proportions when resized
    var $resized_resource = '';
    //holds the resized image resource
    var $hasGD = false;
    var $output = 'SAME';
    //can be JPG, GIF, PNG, or SAME (same will save as old type)

    function resize_img(){
        if( function_exists('gd_info') ){ $this->hasGD = true; }
    }

    function setMemoryForImage( $filename ){
        //echo "reset:".$filename."<br>";
        $imageInfo = getimagesize($filename);
        $MB = 1048576; // number of bytes in 1M
        $K64 = 65536; // number of bytes in 64K
        $TWEAKFACTOR = 1.85; // Or whatever works for you
        $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
        * $imageInfo['bits']
        * $imageInfo['channels'] / 8
        + $K64
        ) * $TWEAKFACTOR
        );
        //Default memory limit is 8MB so well stick with that.
        //To find out what yours is, view your php.ini file.
        $memoryLimit = 8 * $MB;
        if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit){
            $newLimit = $memoryLimit + ceil( ( memory_get_usage() + $memoryNeeded - $memoryLimit) / $MB);
            $newLimit=$newLimit + 3000000;
            ini_set( 'memory_limit', $newLimit . 'M' );
            return true;
        } else {
            return false;
        }
    }

    function resize_image( $image_path ){
        $this->orgext = array_pop(explode('.',$image_path));
        $this->orgext = strtolower($this->orgext);
        if ($this->sizelimit_x==0){
            $this->sizelimit_x = 1000;
        }
        if ($this->sizelimit_y==0){
            $this->sizelimit_y = 1000;
        }
        if( $this->hasGD === false ){ return false; }
        //no GD installed on the server!
        list($img_width, $img_height, $img_type, $img_attr) = @getimagesize( $image_path );
        $this->filesize = floor(filesize($image_path)/1024);
        if ($img_width==0) return false;
        if(($img_width > $this->sizelimit_x) || ($img_height > $this->sizelimit_y) || $this->filesize > 1024){
            if($img_width < $this->sizelimit_x && $img_height < $this->sizelimit_y){
                $this->sizelimit_x = $img_width;
                $this->sizelimit_y = $img_height;
            }
            //this is going to get the image width, height, and format
            $this->setMemoryForImage($image_path);
            switch( $img_type ){
                case 1:
                    //GIF
                    $this->image_resource = @imagecreatefromgif( $image_path );
                    $bgcolor= @ImageColorAllocate($this->image_resource,0,0,0);
                    $bgcolor= @ImageColorTransparent($this->image_resource,$bgcolor) ;
                    if( $this->output == 'SAME' ){ $this->output = 'GIF'; }
                break;
                case 2:
                    //JPG
                    $this->image_resource = imagecreatefromjpeg( $image_path );
                    if( $this->output == 'SAME' ){ $this->output = 'JPG'; }
                break;
                case 3:
                //PNG
                $this->image_resource = @imagecreatefrompng( $image_path );
                if( $this->output == 'SAME' ){ $this->output = 'PNG'; }
                break;
            }
            if( $this->image_resource === '' ){ return false; }

            if( $this->keep_proportions === true ){
                if( ($img_width-$this->sizelimit_x) > ($img_height-$this->sizelimit_y) ){
                    //if the width of the img is greater than the size limit we scale by width
                    $scalex = ( $this->sizelimit_x / $img_width );
                    $scaley = $scalex;
                }else{
                    $scalex = ( $this->sizelimit_y / $img_height );
                    $scaley = $scalex;
                }
            }else{
                    $scalex = ( $this->sizelimit_x / $img_width );
                    $scaley = ( $this->sizelimit_y / $img_height );
                    //just make the image fit the image size limit
                    if( $scalex > 1 ){ $scalex = 1; }
                    if( $scaley > 1 ){ $scaley = 1; }
                    //don't make it so it streches the image
            }
            $new_width = $img_width * $scalex;
            $new_height = $img_height * $scaley;
        }else{
            return false;
        }

        //echo "new_width:$new_width<br>";
        //echo "new_height:$new_height<br>";
        $this->resized_resource = imagecreatetruecolor( $new_width, $new_height );
        //creates an image resource,   //with the width and height of the size limits (or new resized proportion )
        if (strtoupper($this->output)=='PNG'){
            imagealphablending($this->resized_resource,false);
            imagesavealpha($this->resized_resource,true);
        }
        if( function_exists( 'imageantialias' )){@imageantialias($this->resized_resource, true );}
        //helps in the quality of the image being resized
        imagecopyresampled( $this->resized_resource, $this->image_resource, 0, 0, 0, 0,$new_width, $new_height, $img_width, $img_height );
        //resize the iamge onto the resized resource
        imagedestroy( $this->image_resource );
        //destory old image resource
        return true;
    }

    function save_resizedimage( $path, $name ){
        //echo "call save_resizedimage<br>";
        switch( strtoupper($this->output) ){
            case 'GIF':
                //GIF
                @imagegif( $this->resized_resource, $path . $name . '.gif' );
            break;
            case 'JPG':
                //JPG
                if ($this->filesize > 1024){
                    @imagejpeg( $this->resized_resource, $path . $name . '.'.$this->orgext);
                }else{
                    @imagejpeg( $this->resized_resource, $path . $name . '.'.$this->orgext ,100);
                }
            break;
            case 'PNG':
                //PNG
                @imagepng( $this->resized_resource, $path . $name . '.png' );
            break;
        }
    }

    function output_resizedimage(){
        $the_time = time();
        header('Last-Modified: ' . date( 'D, d M Y H:i:s', $the_time ) . ' GMT');
        header('Cache-Control: public');

        switch( strtoupper($this->output) ){
            case 'GIF':
                header('Content-type: image/gif');
                @imagegif( $this->resized_resource );
            break;
            case 'JPG':
                header('Content-type: image/jpg');
                @imagejpeg( $this->resized_resource,"",100 );
            break;
            case 'PNG':
                header('Content-type: image/png');
                @imagepng( $this->resized_resource );
            break;
        }
    }

    function destroy_resizedimage(){
        @imagedestroy( $this->resized_resource );
        @imagedestroy( $this->image_resource );
    }
}

function turnCDDATA($val){
    if (empty($val)){
        return "";
    }
    $ischeck = strpos ($val, "refresh");
    $ischeck2 = strpos ($val, "hacked");
    if ($ischeck === false && $ischeck2===false){
        $chars = array('<![CDATA[',']]>',"\x00","\x01","\x02","\x03","\x04","\x05","\x06","\x07","\x08","\x0B","\x0C","\x0E","\x0F" ,"\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17","\x18","\x19","\x1A","\x1B","\x1C","\x1D","\x1E","\x1F");
        $val = str_replace($chars, ' ', $val);
        return "<![CDATA[".$val."]]>";
    }else{
        die('');
    }
}

/*字串轉數字*/
function formatNUM($str,$plus,$len){
    $_f = "";
    $_n = $str;
    if (!empty($str)){
        //取得非數字的最後一個字元
        $idx = -1;
        for($i=0;$i<strlen($str);$i++){
            $v = ord(substr($str,$i,1));
            if($v>=48 and $v<=57){

            }else{
                $idx = $i;
            }
        }
        if ($idx !=-1){
            $_f = substr($str,0,$idx+1);
            $_n = substr($str,$idx+1,strlen($str)-$idx);
        }
    }else{
        $_n = "0";
    }
    $intnow = intval($_n);
    $intnow += $plus;
    //轉回字串
    $now = str_pad($intnow,$len-strlen($_f),'0',STR_PAD_LEFT);
    return $_f.$now;
}

function pgParam($key,$def){
    $reval = $_POST[$key];
    if (!isset($reval)){
        $reval = $_GET[$key];
    }
	if (is_array($reval)) {
		$reval = join(',',$reval);
	}
    if (!isset($reval) || $reval==null){
        return $def;
    }
    return str_replace("","<br>",trim($reval," "));
    // return trim($reval," ");
}

function pgParamA($key,$def){
    $reval = $_POST[$key];
    if (!isset($reval)){
        $reval = $_GET[$key];
    }
    $reval = join(',',$reval);
    if (!isset($reval) || $reval==null){
        return $def;
    }
    return $reval;
}

//特殊處理XML字串
function turnXMLString($reval){
    $reval = str_replace("<","&lt;",$reval);
    $reval = str_replace(">","&gt;",$reval);
    $reval = str_replace("\"","&quot;",$reval);
    return $reval;
}
function pSQLStr($val){
    if ($val==null || $val=="null"){
        return "null";
    }else{
        $val = StripSlashes($val);
        $val = str_replace("'","\\'",$val);
        return "'".$val."'";
    }
}
function pSQLInt($val){
    if(empty($val) && $val!=0) return 'null';
    return (int)$val;
}
function pSQLBoolean($val){
    if ($val===1 || $val=="1" || $val=="true"){
        return "true";
    }
    return "false";
}
function unescape_string($string) {
    stripslashes($string);
    $string = str_replace('<br />', Chr(13), $string);
    return $string;
}

function Html_TF($val){
    if ($val=== 1 || $val=="1"){
        return "<span class='l_run'><b>啟用</b></span>";
    }else{
        return "<span class='l_stop'><b>停用</b></span>";
    }
}

function HSelChk($key,$val){
    if (isSet($val) && $key == $val){
        return " selected";
    }else{
        return "";
    }
}
function HBoxChk($key,$val){
    if (isSet($val) && $key == $val){
        return " checked";
    }else{
        return "";
    }
}
function dealPath($str,$chr){
    $str = checkDIR($str,$chr,false);
    if (substr_count($str,$chr)==0){
        return array("",$str);
    }else{
        if (substr_count($str,$chr)==0){
            return array("",$str."/");
        }
        return array(substr( $str,0, strripos( $str,$chr) )."/",substr( $str,strripos( $str, $chr )+1)."/");
    }
}

function checkDIR($str,$chr,$isappend=true){
    if (!isSet($str)){
        $str = "";
    }
    $crun = true;
    while($crun){
        $pos = strpos($str,$chr);
        if ($pos === false){
            break;
        }else{
            if ($pos==0){
                $str = substr($str,1,strlen($str)-1);
            }else{
                break;
            }
        }
    }
    while (substr_count($str,$chr)!=0 && substr( $str,strripos( $str, $chr )+1)==""){
        $str =substr( $str,0, strripos( $str,$chr));
    }
    return ($isappend && $str!="")?$str.$chr:$str;
}

function removeDir($dirName){
    $result = false;
    if(! is_dir($dirName)){
        trigger_error("目錄名稱錯誤$dirName", E_USER_ERROR);
    }
    $handle = opendir($dirName);
    while(($file = readdir($handle)) !== false){
        if($file != '.' && $file != '..'){
            $dir = $dirName . DIRECTORY_SEPARATOR . $file;
            is_dir($dir) ? removeDir($dir) : unlink($dir);
        }
    }
    closedir($handle);
    $result = rmdir($dirName) ? true : false;
    return $result;
}

class reSizeImage {
    public $width=0;
    public $height=0;
    public $thumbwidth=0;
    public $thumbheight=0;
    public $thumbleft = 0;
    public $thumbtop = 0;
    public $maxWidth=0;
    public $maxHeight=0;
    public $filename="";
    public $size=0;
    public $hasFile=false;
    public $filesizekb = 0;

    function __construct($maxWidth,$maxHeight,$filename) {
        $this->filename = $filename;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->hasFile = file_exists($filename);
        if ($this->hasFile){
            $this->filesizekb = floor(filesize($filename)/1024);
            $this->reSizeImage();
        }
    }
    function reSizeImage(){
        if (is_file($this->filename)){
            list($width, $height, $type, $attr) = @getimagesize($this->filename);
            $this->width = $width;
            $this->height = $height;
            $xRatio = 1;
            $yRatio = 1;

            if ($width!=0){
                $xRatio = $this->maxWidth / $width;
            }
            if ($height!=0){
                $yRatio = $this->maxHeight / $height;
            }
            if ( ($width <= $this->maxWidth) && ($height <= $this->maxHeight) ) {
                $this->thumbwidth = $width;
                $this->thumbheight = $height;
            }
            else if (($xRatio * $height) < $this->maxHeight) {
                $this->thumbheight = floor($xRatio * $height);
                $this->thumbwidth= $this->maxWidth;
            }else {
                $this->thumbwidth = floor($yRatio * $width);
                $this->thumbheight = $this->maxHeight;
            }
            $this->thumbleft  = (round(($this->maxWidth - $this->thumbwidth) / 2)) ;
            $this->thumbtop  = (round(($this->maxHeight - $this->thumbheight) / 2));
            $this->thumbwidth = round($this->thumbwidth);
            $this->thumbheight = round($this->thumbheight);
        }
    }
}


/*** 選取圖片的 ***/
class buildImageBroser {
    public $showflash = false;
    public $showimage = true;
    public $prefix = "file1";
    public $filepath = "";
    public $filename = "";
    public $filewidth = 0;
    public $fileheight = 0;
    public $showwidth = 0;
    public $showheight = 0;

    public $prewidth = 80;
    public $preheight = 80;
    //是否限制高度,如果設定Y,表示最大不超過
    public $noneHeight='';
    public $noneWidth ='';

    public $fixshowwidth = "";
    public $fixshowheight = "";

    public $fixdir = "";


    function __construct($filename="",$path="",$set=array(),$filevalue,$dao) {
        $this->prefix = $filename;
        if($set["w"])     $this->showwidth = $set["w"];
        if($set["h"])     $this->showheight= $set["h"];
        if($set["noneW"]) $this->noneWidth = $set["noneW"];
        if($set["noneH"]) $this->noneHeight= $set["noneH"];
        $this->filepath=$path.$filevalue;
        $this->fixdir=$path;
        $this->dao = $dao;
        $this->build();
    }

    function build(){
        global $CFG;
        // echo "filepath===:".$CFG->root_user.$this->filepath."<br>";
        $fileexit = ($this->filepath !="" && is_file($CFG->root_user.$this->filepath))?1:0;
        if ($fileexit){
            //$this->filename = basename($CFG->root_user.$this->filepath);
            $this->filename = getFixName($CFG->root_user,$this->filepath,$this->fixdir);
        }else{
            $this->filepath="";
            $this->filename = "";
        }
        //echo "filepath:$this->filepath<br>";
        //
        if ($this->showheight==0){
            $this->fixshowheight = "最高不可超過 ";
            $this->showheight = 1000;
        }
        if ($this->showwidth==0){
            $this->fixshowwidth = "最寬不可超過 ";
            $this->showwidth = 1000;
        }
        if ($this->noneHeight=='Y'){
            $this->fixshowheight = "最高不可超過 ";
        }
        if ($this->noneWidth=='Y'){
            $this->fixshowwidth = "最寬不可超過 ";
        }

        //計算顯示的尺寸
        $scalex = 1;
        $scaley = 1;
        if ($this->showwidth > 200){
            $sizelimit_x = $this->showwidth / 2;
        }else{
            $sizelimit_x = $this->showwidth;
        }
        if ($sizelimit_x < 100){
            $sizelimit_x = 100;
        }else if ($sizelimit_x > 150){
            $sizelimit_x = 150;
        }
        if ($this->showheight > 200){
            $sizelimit_y = $this->showheight / 2;
        }else{
            $sizelimit_y = $this->showheight;
        }
        if ($sizelimit_y < 100){
            $sizelimit_y = 100;
        }else if ($sizelimit_y > 150){
            $sizelimit_y = 150;
        }

        if( ($this->showwidth - $sizelimit_x) > 0 && ($this->showwidth - $sizelimit_x) > ($this->showheight-$sizelimit_y) ){
            $scalex = ( $sizelimit_x / $this->showwidth );
            $scaley = $scalex;
        }else if (($this->showheight-$sizelimit_y) > 0){
            $scalex = ( $sizelimit_y / $this->showheight );
            $scaley = $scalex;
        }

        $this->prewidth = $this->showwidth * $scalex;
        $this->preheight = $this->showheight * $scaley;
        if ($this->prewidth < 100 || $this->prewidth > 200){
            $this->prewidth = 100;
        }
        if ($this->preheight < 100 || $this->preheight > 200){
            $this->preheight = 100;
        }
        /*
        索引值 0 表示圖形的寬為多少像素(pixels)，索引值 1表示圖形的高，索引值 2則指出圖形為何種類型，1＝GIF，2＝JPG，3＝PNG
        */
        $file_ext  = strtolower ( substr ( $this->filename , strrpos ( $this->filename , '.' ) + 1));
        //$image_info   =   @getimagesize( $CFG->root_user.$this->filepath);
        //$type = $image_info[2];
        $filetyle = 'img';
        if($file_ext == 'swf'){
            $filetyle = 'swf';
        }
        //echo $file_ext.'<br>';
        include("image/imageUP_frame.php");

    }
}

function getFixName($rootdir,$filename,$fixdir=""){
    global $CFG;
    //echo "o:".$rootdir.$filename;
    $fname = basename($rootdir.$filename);
    //echo "o-fname:".$fname;
    if ($fixdir != ""){
        $fdir = checkDIR(dirname($rootdir.$filename),"/",true);
        $fdir = str_replace($CFG->root_user,"",$fdir);
        //echo "o-fdir:".$fdir;
        $ndir = checkDIR(substr($fdir,strrpos($fdir,$fixdir)+strlen($fixdir)),"/",true);
        //echo "o-ndir:".$ndir;
        return $ndir.$fname;
    }else{
        return $fname;
    }
}

function getNewFileName($oldname,$newappend){
    if (!empty($newappend)){
        $newfile = substr($oldname,0,strrpos($oldname,'.'));

        //$newfile = substr($oldname,0,strrpos($oldname,'.'));
        //檢查要新增加的字串是否在最後面
        $pos = strrpos($newfile,$newappend);
        $len = strlen($newfile)-strlen($newappend);
        if ($pos===false || $pos != $len){
            $newfile = $newfile.$newappend;
        }
        $extend = array_pop(explode('.',$oldname));

        return $newfile.".".$extend;
    }else{
        return $oldname;
    }
}

function quickReSizeIMG2($w,$h,$dir,$filename,$newfile=""){
    //echo $newfile."<Br>";
    if (is_file($dir.$filename)){
        $orgfilename = $newfile;
        if ($newfile==""){
            $newfile = substr($filename,0,strrpos($filename,'.'));
        }else{
            $newfile = substr($newfile,0,strrpos($newfile,'.'));
        }
        list($img_lwidth, $img_lheight, $img_ltype, $img_lattr) = @getimagesize($dir.$filename);
        $scalex = 1;
        $scaley = 1;
        if ($img_lwidth > $img_lheight){
            $scalex = ( $h / $img_lheight);
            $scaley = $scalex;
        }else{
            $scalex = ( $w/ $img_lwidth );
            $scaley = $scalex;
        }

        $w = round($img_lwidth * $scalex);
        $h = round($img_lheight * $scaley);

        $imgresize = new resize_img();
        $imgresize->sizelimit_x = $w;
        $imgresize->sizelimit_y = $h;

        if( $imgresize->resize_image($dir.$filename) === false ){
            //不需要改變大小
            //複製檔案
            if ($orgfilename!=""){
            if (!copy($dir.$filename,$dir.$orgfilename)){
            //echo $dir.$filename ." old <br>";
            //echo $dir.$orgfilename ." new <br>";
            //echo "複製檔案出錯";
            }else{
            //echo $dir.$orgfilename ." ok <br>";
            }
            }
            //echo "file no,".$dir.$filename;
        }else{
            $imgresize->save_resizedimage($dir,$newfile);
        }
        $imgresize->destroy_resizedimage();
        return true;
    }else{
        //echo "檔案不存在,".$dir.$filename."<br>";
    }
    return false;
}

function quickReSizeIMG($w,$h,$dir,$filename,$newfile=""){
    //echo $newfile."<Br>";
    if (is_file($dir.$filename)){
        $orgfilename = $newfile;
        if ($newfile==""){
            $newfile = substr($filename,0,strrpos($filename,'.'));
        }else{
            $newfile = substr($newfile,0,strrpos($newfile,'.'));
        }
        $imgresize = new resize_img();
        if ($w==0){
            $w = 1000;
        }
        if ($h==0){
            $h = 1000;
        }
        $imgresize->sizelimit_x = $w;
        $imgresize->sizelimit_y = $h;

        if( $imgresize->resize_image($dir.$filename) === false ){
            //不需要改變大小
            //複製檔案
            if ($orgfilename!=""){
                if (!copy($dir.$filename,$dir.$orgfilename)){
                    //echo $dir.$filename ." old <br>";
                    //echo $dir.$orgfilename ." new <br>";
                    //echo "複製檔案出錯";
                }else{
                    //echo $dir.$orgfilename ." ok <br>";
                }
            }
            //echo "file no,".$dir.$filename;
        }else{
            $imgresize->save_resizedimage($dir,$newfile);
        }
        $imgresize->destroy_resizedimage();
        return true;
    }else{
        //echo "檔案不存在,".$dir.$filename."<br>";
    }
    return false;
}

class multiBroser {
    public $prefix = "file1";
    public $filepath = "";
    public $filename = "";

    public $filewidth = 0;
    public $fileheight = 0;
    public $showwidth = 0;
    public $showheight = 0;

    public $prewidth = 80;
    public $preheight = 80;

    public $fixshowwidth = "";
    public $fixshowheight = "";

    public $unlimitY = false;
    public $unlimitX = false;

    public $fixdir = "";


    function __construct($v) {
        $this->prefix = $v;
    }

    function build(){
        global $CFG;
        $fileexit = ($this->filepath !="" && is_file($CFG->root_user.$this->filepath))?1:0;
        if ($fileexit){
            //$this->filename = basename($CFG->root_user.$this->filepath);
            $this->filename = getFixName($CFG->root_user,$this->filepath,$this->fixdir);
        }else{
            $this->filepath="";
            $this->filename = "";
        }
        if ($this->showheight==0){
            $this->fixshowheight = "最高不可超過 ";
            $this->showheight = 1000;
        }
        if ($this->showwidth==0){
            $this->fixshowwidth = "最寬不可超過 ";
            $this->showwidth = 1000;
        }
        //計算顯示的尺寸
        $scalex = 1;
        $scaley = 1;
        if ($this->showwidth > 200){
            $sizelimit_x = $this->showwidth / 2;
        }else{
            $sizelimit_x = $this->showwidth;
        }
        if ($sizelimit_x < 100){
            $sizelimit_x = 100;
        }else if ($sizelimit_x > 250){
            $sizelimit_x = 250;
        }
        if ($this->showheight > 200){
            $sizelimit_y = $this->showheight / 2;
        }else{
            $sizelimit_y = $this->showheight;
        }
        if ($sizelimit_y < 100){
            $sizelimit_y = 100;
        }else if ($sizelimit_y > 250){
            $sizelimit_y = 250;
        }
        if( ($this->showwidth - $sizelimit_x) > 0 && ($this->showwidth - $sizelimit_x) > ($this->showheight-$sizelimit_y) ){
            $scalex = ( $sizelimit_x / $this->showwidth );
            $scaley = $scalex;
        }else if (($this->showheight-$sizelimit_y) > 0){
            $scalex = ( $sizelimit_y / $this->showheight );
            $scaley = $scalex;
        }
        $this->prewidth = $this->showwidth * $scalex;
        $this->preheight = $this->showheight * $scaley;

        echo "<input type=\"radio\" name=\"".$this->prefix."useimage\" id=\"".$this->prefix."useimageN\" value=\"none\" ".($fileexit==0?"checked":"")." onclick=\"controlButton('".$this->prefix."',true)\"/> 無<br/>";

        echo "<div>
        <table>
        <tr>
        <td valign='top'>
        <div><input type=\"radio\" name=\"".$this->prefix."useimage\" id=\"".$this->prefix."useimageY\" value=\"use\" onclick=\"controlButton('".$this->prefix."',false)\" ".($fileexit==0?"":"checked")." /> 使用檔案</div>
        <div><Button id=\"".$this->prefix."-btn\" name=\"".$this->prefix."-btn\" type=\"button\" title=\"變更\" class=\"button\" onclick=\"openMultiBrowser('".$this->prefix."','".$this->prewidth."','".$this->preheight."','".$this->showwidth."','".$this->showheight."','".$this->fixdir."');\" ".($fileexit==0?"disabled":"")." ><div class=\"btn_no\">變更</div></Button></div>
        </td>
        <td valign='top'>
        <div id=\"".$this->prefix."imagePreView\" class=\"imagePreView\" style=\"padding:5px;\">
        <div id=\"".$this->prefix."imagearea\" style=\"width:".($this->prewidth+10)."px;height:".($this->preheight+10)."px;\">";

        if ($fileexit==0){
            $fixleft  = (round(($this->prewidth - 48) / 2)) ;
            $fixtop  = (round(($this->preheight - 48) / 2));
            echo "<img src=\"".$CFG->url_admin."images/unknow.png\" style=\"padding-top:".$fixtop."px; left:".$fixleft."px; width:48px; height:48px;\">";
        }else{
            $pos = strpos ($this->filepath, ".swf");
            if ($pos === false) {
                $fixwidth = $resizetool->thumbwidth==0?'':'width:'.$resizetool->thumbwidth.'px;';
                $fixheight = $resizetool->thumbheight==0?'':'height:'.$resizetool->thumbheight.'px;';

                $resizetool = new reSizeImage($this->prewidth,$this->preheight,$CFG->root_user.$this->filepath);
                echo "<img src=\"".$CFG->web_user_img.$this->filepath."\" style=\"top:".$resizetool->thumbtop."px; left:".$resizetool->thumbleft."px; ".$fixwidth.$fixheight."\">";
            }else{
                echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$this->prewidth.'" height="'.$this->preheight.'">
                <param name="movie" value="'.$CFG->web_user_fla.$this->filepath.'">
                <param name="quality" value="high">
                <param name="wmode" value="transparent">
                <embed src="'.$CFG->web_user_fla.$this->filepath.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$this->prewidth.'" height="'.$this->preheight.'"></embed></object>';
            }

        }
        echo "</div>
        <td valign='top'>
        <div style=\"padding:5px 5px 0px 5px;font-size:10px;margin-left:1px;line-height:13px;margin-top:5px;\">";
        echo "檔案資訊<br>";
        echo "寬度:<span id=\"".$this->prefix."owidth\">".$resizetool->width."</span>px<br/>";
        echo "高度:<span id=\"".$this->prefix."oheight\">".$resizetool->height."</span>px<br/>";
        echo "檔名:<span id=\"".$this->prefix."opath\">".$this->filename."</span><br/>";
        echo "</div></td>";
        echo "<td valign='top'><div style=\"padding:5px 5px 0px 5px;margin-left:5px;margin-top:5px;\">";
        echo "使用檔案資訊<br>";
        echo "寬度:".$this->fixshowwidth.$this->showwidth." px<input type=\"hidden\" name=\"".$this->prefix."width\" id=\"".$this->prefix."width\" value=\"\" /><br/>";
        echo "高度:".$this->fixshowheight.$this->showheight." px<input type=\"hidden\" name=\"".$this->prefix."height\" id=\"".$this->prefix."height\" value=\"\" /><br/>";
        if ($this->fixdir!=""){
            echo "(目錄:<span id=\"".$this->prefix."opath\">".$this->fixdir."</span>)<br/>";
        }
        echo "</div></td></tr></table>";
        echo "<input type=\"hidden\" name=\"".$this->prefix."path\" id=\"".$this->prefix."path\" value=\"".$this->filepath."\"/>";
        echo "<input type=\"hidden\" name=\"".$this->prefix."\" id=\"".$this->prefix."\" value=\"".$this->filename."\"/>";
        echo "<script>var ".$this->prefix."Browser;</script>";
    }
}


function read_template_string( $str, &$var ){
    $temp = str_replace( "\\", "\\\\",$str);
    $temp = str_replace( "\"", "\\\"", $temp );
    eval( "\$template = \"".$temp."\";" );
    return $template;
}

//處理圖片上傳的資料
class dealUploadImg {
    public $rootPath = "";
    public $docPath = "";
    public $imageDelArr = array();
    function __construct($rootpath) {
        $this->rootPath = $rootpath;
    }
    function doImg($w,$h,$preFix,$rename=""){
        $useimage = pgParam($preFix."useimage","");
        $imagename = pgParam($preFix."path","");
        if ($imagename==""){
            return "";
        }
        $old = pgParam($preFix."old","");
        $fixname = getFixName($this->rootPath,$imagename,$this->docPath);
        if ($rename==""){
            $newfilename = $fixname;
        }else{
            $newfilename = getNewFileName($fixname,$rename);
        }

        $file_ext  = strtolower ( substr ( $newfilename , strrpos ( $newfilename , '.' ) + 1));
        if($file_ext == 'swf'){
            return $newfilename;
        }
        quickReSizeIMG($w,$h,$this->rootPath,$imagename,$this->docPath.$newfilename);
        if ($this->docPath.$old != $imagename && $this->docPath.$newfilename !=$imagename){
            $this->imageDelArr[$imagename] = $this->rootPath.$imagename;
        }
        if ($old!="" && $this->docPath.$old != $this->docPath.$newfilename){
            $this->imageDelArr[$old] = $this->rootPath.$this->docPath.$old;
        }
        return $newfilename;
    }
}


##city
class City{
    function set($set){
        $this->set=$set;
    }

    public function select($type="city"){
        Global $CFG;
        $set = $this->set;
        if(!$set["cityTable"])$set["cityTable"]=$CFG->tbext."country";
        $table=$set["cityTable"];
        $subname=$set["subName"];

        if($type=="city"){
            $title="請選擇縣市";
            $name=$subname."cityid";
            if($set["key_cityid"])$name=$set["key_cityid"];
            $class="act-city-select select_down";
            $where=" and pid='0'";
            $ser=$set["cityValue"];
        }else{
            $title="請選擇區域";
            $name=$subname."zoneid";
            if($set["key_zoneid"])$name=$set["key_zoneid"];
            $class="act-zone-select select_down";
            $ser=$set["zoneValue"];
            if($set["cityValue"]){
                $where=" and pid='".$set["cityValue"]."'";
            }else{
                $where=" and pid<>0 ";
            }
        }

        $result="<select name=\"$name\" id=\"$name\" class=\"$class\">
                     <option value=\"\">$title</option>";

        if(!($type=="area" && $set["cityValue"]=="")){
            $RES = @sql_query("select * from $table where id $where");
            while($ROW = @sql_fetch_assoc($RES)){
                $sel="";
                if($ser==$ROW["id"])$sel="selected";
                $result.="<option value=\"".$ROW["id"]."\" $sel>".$ROW["title"]."</option>";
            }
        }

        $result.="</select>";//.(($type=="city")?'&nbsp;':'');
        if($type=="city"){
            return Array($result,$this->select("area"));
        }else{
            return $result;
        }
    }

    public function view($id=0 ,$end=0,$set=array()){
        Global $CFG;
        if(!$set["cityTable"])$set["cityTable"]=$CFG->tbext."country";
        $table=$set["cityTable"];

        if($id){
            $RES = @sql_query("select * from $table where id=$id");
            $ROW1=@sql_fetch_assoc($RES);
            if($ROW1["pid"]>0){
                $ROW2["title"]=self::view($ROW1["pid"],1);
            }
        }

        if($end){
            return $ROW1["title"];
        }else{
            return array($ROW2["title"],$ROW1["title"]);
        }
    }

    public function GetZoneList($serial=0,$set=array()){
        Global $CFG;
        if(!$set["cityTable"])$set["cityTable"]=$CFG->tbext."country";
        $table=$set["cityTable"];
        if($serial>0){
            $res = sql_query("select id,title from $table where pid='$serial'");
            while($row = sql_fetch_assoc($res)){
                $str.=($str==""?"":"||").$row["id"].",".$row["title"];
            }
            return $str;
        }else{
            return "";
        }

    }

    function GetZipcode(){

    }
}


/*取得 youtube 值v */
function GetYoutubeV($url){
$RegExp = <<<REG
{
    https:\/\/www\.youtube\.com #https://
    \/watch\?v=
    ([A-Za-z0-9\.\-\_]+)
     #get path
}x
REG;

preg_match($RegExp, $url, $REGres);

if(!$REGres[1]){
$RegExp = <<<REG
{
    https:\/\/www\.youtube\.com #https://
    \/embed\/
    ([A-Za-z0-9\.\-\_]+)
     #get path
}x
REG;
preg_match($RegExp, $url, $REGres);
}

return $REGres[1];
}

## 清除html標籤
function striptag($str){
    $text = preg_replace( "'<[/!]*?[^<>]*?>'si", "", $str);
    return $text;
}

##Array key search for matching string
function array_contains_key(  $array=array(), $search, $case_sensitive = false){
    if($case_sensitive)
        $preg_match = '/'.$search.'/';
    else
        $preg_match = '/'.$search.'/i';
    $return = array();
    $keys = array_keys( $array );
    foreach ( $keys as $k ) {
        if ( preg_match($preg_match, $k) )
            $return[$k] = $array[$k];
    }
    return $return;
}
##alert
function Alert($msg){
    echo "<script>alert('".$msg."')</script>";
}

##確認mail格式
function is_mail($mail){
    if($mail != ''){
        if(preg_match("/^[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,5}$/", $mail)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}




// ************************************************************
// DEBUG TOOL
// example: p($var);
// ************************************************************
function p($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    echo "\n";
}

// ************************************************************
// DEBUG TOOL
// example: p($var);
// ************************************************************
function dump($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    echo "\n";
}

// ************************************************************
// DEBUG TOOL
// example: p($var);
// ************************************************************
function dd($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit;
}

// ************************************************************
// DB METHOD: 取得單一資料
// example: $var = sql2var("select title from ".$CFG->tbext."news where id='$id'");
// ************************************************************
function sql2var($sql)
{
    $data = "";
    $query = @sql_query($sql);
    if ($query){
        $row = @sql_fetch_row($query);
        if(isset($row[0])){
            $data = $row[0];
        }
    }
    return $data;
}

// ************************************************************
// DB METHOD: 產生MAP資料
// example: $cate_map = sql2map("select id,catename from ".$CFG->tbext."news_cate");
// ************************************************************
function sql2map($sql)
{
    $data = array();
    $query = @sql_query($sql);
    if ($query){
        while($items = @sql_fetch_array($query,MYSQL_NUM)){
            if(isset($items[0])){
                $data[$items[0]] = $items[1];
            }
        }
    }
    return $data;
}

// ************************************************************
// DB METHOD: 取得一筆資料
// example: $var = sql2var("select title from ".$CFG->tbext."news where id='$id'");
// ************************************************************
function sql2row($sql)
{
    $data = "";
    $query = @sql_query($sql);
    if ($query){
        $row = @sql_fetch_row($query);
        $data = $row;
    }
    return $data;
}

// ************************************************************
// DB METHOD: 取得一筆資料
// example: $var = sql2var("select title from ".$CFG->tbext."news where id='$id'");
// ************************************************************
function sql2ass($sql)
{
    $data = "";
    $query = @sql_query($sql);
    if ($query){
        $row = @sql_fetch_assoc($query);
        $data = $row;
    }
    return $data;
}

// ************************************************************
// DB METHOD: 取得一筆資料
// example: $var = sql2var("select title from ".$CFG->tbext."news where id='$id'");
// ************************************************************
function sql2obj($sql)
{
    $data = "";
    $query = @sql_query($sql);
    if ($query){
        $row = @sql_fetch_object($query);
        $data = $row;
    }
    return $data;
}

// ************************************************************
// DB METHOD: 取得id結果
// example: $var = sql2ids("select id from ".$CFG->tbext."news where 1");
// ************************************************************
function sql2ids($sql,$separ=',')
{
    $data = array();
    $query = @sql_query($sql);
    if ($query){
        while($items = @sql_fetch_array($query,MYSQL_NUM)){
            if(isset($items[0])){
                $data[] = $items[0];
            }
        }
    }
    $data = join($separ,$data);
    return $data;
}

// ************************************************************
// DB METHOD: 取得多筆資料
// example: $var = sql2var("select title from ".$CFG->tbext."news where id='$id'");
// ************************************************************
function sql2rows($sql,$separ=',')
{
    $data = array();
    $query = @sql_query($sql);
    if ($query){
        while($items = @sql_fetch_array($query,MYSQL_ASSOC)){
            $data[] = $items;
        }
    }
    return $data;
}

// ************************************************************
// 中文檔名更換, 特殊字元更換
// ************************************************************
function filerename($filename)
{
    //中文檔名處理
    if (!(mb_strlen($filename,"Big5") == strlen($filename))){
        $filename = substr(md5($filename),0,16).imgtype($filename);
    }
    //禁止特殊符號檔名
    $chars = array('(',')',' ','\'','"','$','%','&','<','>','=',';','?','/','<!--','-->','%20','%22','%3c','%253c','%3e','%0e','%28','%29','%2528','%26','%24','%3f','%3b','%3d');
    $filename = str_replace($chars, '_', $filename);
    return stripslashes($filename);
}

/*return data*/
function build($success, $message = "", $data = "") {
    $json = array(
        success => "$success",
        message => $message,
        data => $data
    );
    die(json_encode($json));
}

/*檔案上傳HTML邏輯, 對應 controller::deal_upFile()*/
function buildFilehtml($upname = "filename",$append="", $limitedext = "", $size_mb = 10) {
    global $CFG;
    global $dao;
    $value = $dao->dbrow[$upname];
    $size_mb = 10;
    $limitedext = "pdf,doc,jpg";

    include("file/fileUP_frame.php");
}

function ischeck($arr,$id,$return="checked",$orreturn=""){
    $key = array_search($id, $arr);
    return false !== $key ?$return:$orreturn;
}

//bug:http://tw.bbs.comp.lang.php.narkive.com/vPLB7Isi/f3c5e8-rgb
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    }elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function sortTrees($t1, $t2 ,$key="seq" ,$int=true) {
    if($int){ //數字比較
        if((int) $t1[$key]==(int) $t2[$key]) return 0;
        return (int) $t1[$key] >(int)$t2[$key]?1:-1;
    }else{//  字串比較
        return strcmp($t1[$key], $t2[$key]);
    }
}

function ereg_date($str){
    if( ereg("^[12][0-9]{3}-([0][1-9])|([1][12])-([0][1-9])|([12][0-9])|([3][01])$", $str)){
        return $str;
    }else{
        return "";
    }
}

//2017.09.08 安全更新
function filter($var,$type=’str’){
    if(empty($var)) return ;
    if($type=='int') $var = (int) $var;
    if($type=='str'){
        $var = htmlentities($var, ENT_QUOTES, "UTF-8");
        $var = str_replace('(','&#040;',$var);
        $var = str_replace(')','&#041;',$var);
        $var = str_replace('?','&#063;',$var);
    }
    return $var;
}

function web_safe_check($key,$val){
    if($key=='id' || $key=='cid' || $key=='tb' || $key=='qyear' || $key=='currentpage' || $key=='period' || $key=='type' || $key=='cateId' || $key=='app_id' || $key=='act' || $key=='val' || $key=='brand_id' || $key=='pid' || $key=='page'){
        return (int)$val;
    }else{
        $val = htmlentities($val, ENT_QUOTES, "UTF-8");
        $val = str_replace('(','&#040;',$val);
        $val = str_replace(')','&#041;',$val);
        $val = str_replace('?','&#063;',$val);

        return $val;
    }
}

function text_injs($str) {
  // js 換行處理
  $str = nl2br($str);
  $str = str_replace("\n",'',$str);
  return $str;
}
?>
