<?php
$modid = 'indexset01'; // 預設模組代號
$modname = '首頁設定'; // 預設模組名稱

$modDel = array(
    //預設範本
    'file' => array(
        "webadmin/about/"
    ),
    'table' => array(
        "DELETE FROM `tw_webconfig` WHERE id='indexset'"
    ),
    //由安裝程式寫入
    'uniqid' => '58369d1085cd3',
);
?>