<?php
Global $CFG,$pageid;
global $html_title,$html_description;
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();
$CFG->tbext = "tw_";

//取得預設標題
	$sql = "select * from ".$CFG->tbext."webconfig where id='header_info'";

	$query = @mysql_query($sql);
	// if ($query != null){
		$arr_header = @mysql_fetch_array($query);
		// if ($arr_header!= null){
			$xmlobj = new parseXML($arr_header["xmlcontent"]);

			$default_html_title = $xmlobj->value('/content/html_title_tw');
			$default_html_keywords = $xmlobj->value('/content/html_keywords_tw');
			$default_headscript = $xmlobj->value('/content/headscript_tw');
			$default_html_description = $xmlobj->value('/content/html_description_tw');

			if (empty($pageid)){
				$now_html_title = $xmlobj->value('/content/index_html_title_tw');
				$now_html_keywords = $xmlobj->value('/content/index_html_keywords_tw');
				$now_headscript = $xmlobj->value('/content/index_headscript_tw');
				$now_html_description = $xmlobj->value('/content/index_html_description_tw');
			}else{
				$now_html_title = $xmlobj->value('/content/'.$pageid.'_tw/html_title');
				$now_html_keywords = $xmlobj->value('/content/'.$pageid.'_tw/html_keywords');
				$now_headscript = $xmlobj->value('/content/'.$pageid.'_tw/headscript');
				$now_html_description = $xmlobj->value('/content/'.$pageid.'_tw/html_description');
			}
			if ($now_html_title !=""){
				$html_title .= (($html_title!="")?"-":"").$now_html_title;
			}
			if ($default_html_title!=''){
				$html_title .= (($html_title!="")?" | ":"").$default_html_title;
			}

			if ($html_keywords==""){
				$html_keywords .= (($html_keywords!="")?",":"").$now_html_keywords;
			}
			$html_keywords .= (($html_keywords!="")?",":"").$default_html_keywords;

			if ($html_description==""){
				$html_description .= (($html_description!="")?",":"").$now_html_description;
			}
			$html_description .= (($html_description!="")?" ":"").$default_html_description;
		// }
	// }

	$tpl -> assignGlobal(array(
		"web_title" => striptag($html_title),
		"web_keywords" => striptag($html_keywords),
		"web_description" => striptag($html_description),
    "headjs" => $now_headscript,
    "default-headjs" => $default_headscript,
		"og_title" => $web_title,
		"og_description" => $web_description,
		"og_img" => $web_img,
		"FBnowurl" => $FBnowurl,
	));



$tpl -> printToScreen();
?>
