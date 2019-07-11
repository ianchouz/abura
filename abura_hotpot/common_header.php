<?php
Global $CFG,$pageid;
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();

// Logo title
$row=getConfigNew('company_info');
$xmlvo = new parseXML($row);
$data['company_name'] = $xmlvo->value('/content/company_name');
$tpl->assignGlobal($data);

#目前所在
$nowpage = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1);
if ($nowpage=="index.php") {
	$data['active_index'] = 'active';
} elseif ($nowpage=="about.php") {
	$data['active_about'] = 'active';
} elseif ($nowpage=="service.php") {
  $data['active_service'] = 'active';
} elseif ($nowpage=="product.php") {
  $data['active_product'] = 'active';
} elseif ($nowpage=="news.php") {
  $data['active_news'] = 'active';
} elseif ($nowpage=="contact.php") {
	$data['active_contact'] = 'active';
}
$tpl->assignGlobal($data);


$tpl -> printToScreen();
?>
