<?php
$modid = 'news01'; // 預設模組代號
$modname = '最新消息'; // 預設模組名稱

$modDel = array(
    //預設範本
    'file' => array(
        "webadmin/news/"
    ),
    'table' => array(
        "DROP TABLE `tw_news`"
        ,"DROP TABLE `tw_news_cate`"
        ,"DROP TABLE `tw_news_stand`"
    ),
    //由安裝程式寫入
    'uniqid' => '5808406437d46',
);
?>