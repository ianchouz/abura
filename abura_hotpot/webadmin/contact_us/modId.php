<?php
$modid = 'contact_us01'; // 預設模組代號
$modname = '聯絡我們'; // 預設模組名稱

$modDel = array(
    //預設範本
    'file' => array(
        "webadmin/contact_us/"
    ),
    'table' => array(
        "DROP TABLE `tw_contact_us`"
        ,"DROP TABLE `tw_contact_us_reply`"
    ),
    //由安裝程式寫入
    'uniqid' => '58369b4b08751',
);
?>