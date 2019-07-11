<?php
  include '../applib.php';
  $formurl = $_SERVER['HTTP_REFERER'];
  $isok =($formurl!="");
  $isProper = ($isok===false)?"false":"true";


  if (!$isProper){
    die('error!!');
  }
  include '../webadmin/include/mailSender.php';
  include '../webadmin/contact_us/innerdao.php';
  $mailmessage = "";


  // 檢查驗證碼
  $ccnum = $_SESSION["member_code"];

  if(empty($ccnum) || $ccnum != pgParam("member_code",null) ){
    build(false,"驗證碼輸入錯誤!!!!");
  }


  //新增至資料庫
  $vo = new contact_us();
  $xx = pgParam("name",null);
  if (empty($xx)){
    die('empty!!');
  }




  $strSQLQ = "select * from ".$CFG->tbext."webconfig where id='contactset'";
  $rs = @mysql_query($strSQLQ);
  $row = @mysql_fetch_array($rs);
  $xmlvo = new parseXML($row['xmlcontent']);
  $recipient = $xmlvo->value('/content/recipient');


  $mailsend = new mailSender();

  if ($recipient==""){
    $recipient = $mailsend->mail_receiver;
  }

  $vo->insert();

  if ($vo->action_message == "true"){
    //{寄送郵件
    if ($recipient !=""){
      $id = $vo->id;
      $var->querylist = $vo->getHtml($id);
      $mailsend->tosubject = "[".$mailsend->company_name."]聯絡我們";
      $var->memo = "此信件為系統自動送出，送出時間：".date("Y-m-d H:i:s");
      $emailbody = read_template($CFG->root_web."include/mail.inc", $var);
      $mailsend->tocontent = $emailbody;
      $mailsend->tomail = $recipient;
      @$mailsend->runSend();
    }
    build("true","");
    //}
  }else{
    build("false",$vo->action_message);
  }
?>
