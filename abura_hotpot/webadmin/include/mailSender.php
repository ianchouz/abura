<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
require_once 'config.php';
require_once 'parseXML.php';
use PHPMailer\PHPMailer\PHPMailer;

class mailSender{
    var $isProper = "";
    var $success = "";
    var $message = "";
    var $sender = "";
    var $mail_receiver = "";
    var $company_name = "";
    var $bol_cansend = true;

    var $tocc = null;
    var $tosubject = null;
    var $tocontent = null;
    var $tobcc = null;
    var $tomail = null;

    function __construct(){
        global $CFG;
        
        //先撈取寄件資訊
        $strSQLQ = "select xmlcontent from ".$CFG->tbext."webconfig where id='company_info'";
        $query = mysql_query($strSQLQ);
        $row = mysql_fetch_row($query);
        $xmlobj = new parseXML($row[0]);
        $this->sender = $xmlobj->value('/content/mail_sender');
        $this->mail_receiver = $xmlobj->value('/content/mail_receiver');
        $this->company_name = $xmlobj->value('/content/company_name');
        $this->smtp = $xmlobj->value('/content/smtp');
        $this->account = $xmlobj->value('/content/account');
        $this->pwd = $xmlobj->value('/content/pwd');
        $this->port = $xmlobj->value('/content/port');
        $this->ssl = $xmlobj->value('/content/ssl');
        $this->mail_receiver=$xmlobj->value('/content/mail_receiver');
    }

    function runSend($runtest=false){
        if ($this->sender==null || $this->sender==""){
            $this->bol_cansend = false;
            $this->message .=";寄件人為空";
        }
        if ($runtest){
            $this->tosubject = '網站郵件測試!!';
            $this->tocontent = '網站SMTP設定如下：<br>';
        }else{
            if ($this->tosubject==null){
                $this->bol_cansend = false;
                $this->message .=";標題為空";
            }
            if ($this->tocontent==null){
                $this->bol_cansend = false;
                $this->message .=";內容為空";
            }
        }

        if ($this->bol_cansend){
            try{
                $mail = new PHPMailer;
                $mail->SMTPDebug = 0; 

                if($this->smtp!=''){
                    $mail->IsSMTP();
                    $mail->Host = $this->smtp;  // SMTP server

                    if ($this->account !='' && $this->pwd !=''){
                        $mail->SMTPAuth   = true;           // enable SMTP authentication
                        $mail->Username   = $this->account; // SMTP account username
                        $mail->Password   = $this->pwd;     // SMTP account password
                    }else{
                        $mail->SMTPAuth   = false;   
                    }
                }

                if ($runtest){
                    $this->tocontent .= '<div style="padding:5px;">使用外部SMTP</div>';
                    $this->tocontent .= '<div style="padding:5px;">SMPT：'.$this->smtp.'</div>';
                    $this->tocontent .= '<div style="padding:5px;">當您看到此資訊息，表示您已經設定成功!!</div>';
                }
                
                $this->message.='<br>SMTP:'.$this->smtp;
        
       
                $mail->Port = intval($this->port);  // set the SMTP port for the GMAIL server
                $mail->Subject = $this->tosubject;
                if ($this->ssl =='Y'){
                    $mail->SMTPSecure = "ssl";
                }

                if ($this->sender !=null && $this->sender !=""){
                    $this->sender = str_replace(';',',',$this->sender);
                    $eearr = explode(',',$this->sender);
                    foreach($eearr as $eval){
                        $this->sender = $eval;
                        break;
                    }
                }

                $mail->SetFrom($this->sender , $this->company_name);
                if ($this->tomail !=null && $this->tomail !=""){
                    $this->tomail = str_replace(';',',',$this->tomail);
                    $eearr = explode(',',$this->tomail);
                    foreach($eearr as $eval){
                        $mail->AddAddress($eval);
                    }
                }else{
                    $this->tomail = str_replace(';',',',$this->mail_receiver);
                    $eearr = explode(',',$this->tomail);
                    foreach($eearr as $eval){
                        $mail->AddAddress($eval);
                    }
                }

                if ($this->tocc!= null && $this->tocc !=""){
                    $this->tocc = str_replace(';',',',$this->tocc);
                    $eearr = explode(',',$this->tocc);
                    foreach($eearr as $eval){
                        $mail->AddCC($eval);
                    }
                }

                if ($this->tobcc!= null && $this->tobcc !=""){
                    $this->tobcc = str_replace(';',',',$this->tobcc);
                    $eearr = explode(',',$this->tobcc);
                    foreach($eearr as $eval){
                        $mail->AddBCC($eval);
                    }
                }

                $this->tocontent = str_replace("\\\"","\"",$this->tocontent);
                $mail->MsgHTML($this->tocontent);
                if(!$mail->Send()) {
                    $this->message .= $mail->ErrorInfo.' '.$this->account;
                    
                } else {
                    
                    $this->success = true;
                }
                
    
            } catch (Exception $e) {
                $this->message .= $e->getMessage();
            }
        }
    }

    function toJson(){
        $json = array(
            success => $this->success,
            message => $this->message
        );
        echo json_encode($json);
    }

    function getSuccess(){
        return $this->success;
    }

    function getMessage(){
        return $this->message;
    }
}
?>