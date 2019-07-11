<?php
  require_once('phpmailer/class.phpmailer.php');
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

    function __construct() {
      global $CFG;
        //先撈取寄件資訊
        $strSQLQ = "select xmlcontent from ".$CFG->tbext."webconfig where id='company_info'";
        $query = sql_query($strSQLQ);
        $row = sql_fetch_row($query);
        $xmlobj = new parseXML($row[0]);
        $this->sender = $xmlobj->value('/content/mail_sender');
        $this->mail_receiver = $xmlobj->value('/content/mail_receiver');
        $this->company_name = $xmlobj->value('/content/company_name');


        $this->smtp = $xmlobj->value('/content/edm_smtp');
        $this->account = $xmlobj->value('/content/edm_account');
        $this->pwd = $xmlobj->value('/content/edm_pwd');
        $this->port = $xmlobj->value('/content/edm_port');
        $this->ssl = $xmlobj->value('/content/edm_ssl');

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
          if ($this->smtp !=''){
            $mail = new PHPMailer();
            $mail->CharSet = 'utf-8';
            $mail->Encoding = 'base64';
            if ($runtest){
              $this->tocontent .= '<div style="padding:5px;">使用外部SMTP</div>';
              $this->tocontent .= '<div style="padding:5px;">SMPT：'.$this->smtp.'</div>';
            }
            $mail->IsSMTP();
            $this->message.='<br>SMTP:'.$this->smtp;
            $mail->Host = $this->smtp;  // SMTP server
            if ($this->account !='' && $this->pwd !=''){
              $mail->SMTPAuth   = true;  // enable SMTP authentication
              $mail->Username   = $this->account; // SMTP account username
              $mail->Password   = $this->pwd;        // SMTP account password
            }
            if ($this->port !=''){
              $mail->Port = intval($this->port);  // set the SMTP port for the GMAIL server
            }
            if ($this->ssl =='Y'){
              $mail->SMTPSecure = "ssl";
            }
            if ($runtest){
              $this->tocontent .= '<div style="padding:5px;">當您看到此資訊息，表示您已經設定成功!!</div>';
            }
            $mail->Subject = $this->tosubject;

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
          }else{
            if ($runtest){
              $this->tocontent .= '<div style="padding:5px;">使用主機設定</div>';
              $this->tocontent .= '<div style="padding:5px;">當您看到此資訊息，表示您已經設定成功!!</div>';
            }
            $this->tocontent = str_replace("\\\"","\"",$this->tocontent);
            //標題
            $Subject = "=?UTF-8?B?".base64_encode($this->tosubject)."?=";
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r";
            $headers .= 'Return-Path:'.$this->sender. "\r";
            // Additional headers
            $headers .= "From: =?UTF-8?B?".base64_encode($this->company_name)."?= <".$this->sender.">" . "\r";
            if ($this->tocc!= null && $this->tocc !=""){
              $headers .= "Cc: $this->tocc" . "\r";
            }
            if ($this->tobcc!= null && $this->tobcc !=""){
              $headers .= "Bcc: $this->tobcc" . "\r";
            }
            if ($this->tomail !=null && $this->tomail !=""){
            }else{
              $this->tomail="=?UTF-8?B?".base64_encode($this->company_name)."?= <".$this->sender.">";
            }
            if(!mail($this->tomail,$Subject,$this->tocontent,$headers,'-f'.$this->sender)){
              $this->message="發送郵件發生錯誤,code:" . $mail->ErrorInfo;
            }else{
              $this->success = true;
            }
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