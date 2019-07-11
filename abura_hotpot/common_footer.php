<?php
Global $CFG,$pageid;
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();


#頁尾連絡資訊
$row=getConfigNew('footer_info');
$xmlvo = new parseXML($row);
$data['link_facebook'] = $xmlvo->value('/content/link_facebook');
$data['link_ig'] = $xmlvo->value('/content/link_ig');
$tpl->assignGlobal($data);
if (!empty($data['link_facebook'])) { $tpl->newBlock("link_facebook"); }
if (!empty($data['link_ig'])) { $tpl->newBlock("link_ig"); }

$tpl -> printToScreen();
?>
