<?php
include '../include/mailSender.php';
include 'dao.php';
$mailsend = new mailSender();
$mailmessage = "";
$formurl = $_SERVER['HTTP_REFERER'];
$isok = strpos($formurl, $CFG->url_admin);
$isProper = ($isok === false) ? "false" : "true";

if($isProper == "false") {
    $success = false;
    $message = "no";
} else {
    $id = pgParam("id", null);
    $tomail = pgParam("tomail", null);
    $tosubject = pgParam("tosubject", null);
    $tocontent = pgParam("tocontent", null);
    $htmlcontent = $tocontent;
    $bol_cansend = true;
    if($tomail != null && $tosubject != null && $htmlcontent != null) {
        $message = "有資料為空";
        $success = false;
        if($bol_cansend) {
            $mailsend->tocontent = $htmlcontent;
            $mailsend->tosubject = $tosubject;
            $mailsend->tomail = $tomail;
            $mailsend->runSend();
            $success = $mailsend->getSuccess();
            if($success) {
                $message = "寄送成功!!";
                $sql = "insert into " . $CFG->tbext . "contact_us_reply (contact_id,reply_content,reply_name,reply_time) value (" . $id . ",'" . $htmlcontent . "','" . $_SESSION['sess_name'] . "',now())";
                $result = @sql_query($sql);
            } else {
                $message = $mailsend->getMessage();
            }
        }
    } else {
        $message = "資料有空";
    }
}
$json = array(
    success => $success,
    message => $message
);
echo json_encode($json);
?>