<?php
include 'config.php';
include 'mailSender.php';
$mailsend = new mailSender();
if ($mailsend->mail_receiver !=""){
    $mailsend->tomail = $mailsend->mail_receiver;
    $mailsend->runSend(true);
    if ($mailsend->getSuccess()){
        die('寄送成功，請至'.$mailsend->mail_receiver.'收信!!');
    }else{
        die($mailsend->getMessage());
    }
}else{
    die('請先設定收件者!!');
}
?>