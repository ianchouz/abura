<?php
$modid = 'faq01'; // 預設模組代號
$modname = '問與答'; // 預設模組名稱

$modDel = array(
    //預設範本
    'file' => array(
        "webadmin/{mod}/"
    ),
    'table' => array(
        "DROP TABLE `tw_{mod}`"
        ,"DROP TABLE `tw_{mod}_cate`"
        ,"DROP TABLE `tw_{mod}_stand`"
    ),
    //由安裝程式寫入
    'uniqid' => '{uniqid}',
);
?>