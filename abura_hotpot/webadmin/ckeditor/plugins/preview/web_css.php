<?
include_once("../../../applib.php");
?>
<?if($page_css!='' && is_file($CFG->root_web_pub.'template/base/'.$page_css.'.css')){?>
<link href="<?=$CFG->web_web_pub?>template/base/<?=$page_css?>.css" rel="stylesheet" type="text/css" />
<?}?>
<link href="<?=$CFG->web_web_pub?>template/<?=$template_id?>/index.css" rel="stylesheet" type="text/css" />
<?if($colorcss_id!=''){?>
<link href="<?=$CFG->web_web_pub?>template_colorcss/<?=$colorcss_id?>/index.css" rel="stylesheet" type="text/css" />
<?}?>
<link rel="stylesheet" href="<?=$CFG->domain?>customization_css.php" type="text/css" rel="stylesheet" />