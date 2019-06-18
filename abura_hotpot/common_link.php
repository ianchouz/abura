<?php
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();
$tpl -> printToScreen();
?>
