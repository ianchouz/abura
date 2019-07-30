<?php include 'applib.php';
$tpl = new TemplatePower("html/".basename(__FILE__,".php").".html");
$tpl -> prepare();
$pageid="index";


$row=getConfigNew('indexset');
$xmlvo = new parseXML($row);

// Banner
$s1data_title_en = nl2br($xmlvo->value('/content/s1data_title_en'));
$s1data_title_en = str_replace('||', '', $s1data_title_en);
$s1data_video_active = nl2br($xmlvo->value('/content/s1data_video_active'));
$data = [
  's1data_title_en' => $s1data_title_en,
  's1data_video_active' => !empty($s1data_video_active) ? 'true' : 'false',
  's1data_video' => $xmlvo->value('/content/s1data_video'),
];
$tpl->assignGlobal($data);
// Banner IMG
$data = array();
for($i=1;$i<=5;$i++) {
    $dsk = $xmlvo->value('/content/dsk'.$i);
    $mbl = $xmlvo->value('/content/mbl'.$i);
    $data['subtitle'] = nl2br($xmlvo->value('/content/s1_title'.$i));
    $data['img'] = $dsk ? $CFG->url_web."archive/images/indexset/".$dsk : '';
    $data['imgMbl'] = $mbl ? $CFG->url_web."archive/images/indexset_mbl/".$mbl : '';
    $data['img_alt'] = $xmlvo->value('/content/dsk'.$i.'_alt');
    $data['imgMbl_alt'] = $xmlvo->value('/content/mbl'.$i.'_alt');

  	$tpl->newBlock("banner");
  	$tpl->assign($data);
}


// STORY
$data = [
  's21data_title_en' => nl2br($xmlvo->value('/content/s21data_title_en')),
  's21data_title_ch' => nl2br($xmlvo->value('/content/s21data_title_ch')),
  's21data_intro_title' => nl2br($xmlvo->value('/content/s21data_intro_title')),
  's21data_intro_content' => nl2br($xmlvo->value('/content/s21data_intro_content')),
];
$tpl->assignGlobal($data);
// STORY INTRO
$data = array();
for($i=1;$i<=3;$i++) {
    $img = $xmlvo->value('/content/s21data_img'.$i);
    $data['img'] = $img ? $CFG->url_web."archive/images/s21data_img/".$img : '';
    $data['img_alt'] = $xmlvo->value('/content/s21data_img'.$i.'_alt');

  	$tpl->newBlock("s21data_img");
  	$tpl->assign($data);
}


// MENU 味自慢集
$data = [
  's22data_title_en' => nl2br($xmlvo->value('/content/s22data_title_en')),
  's22data_title_ch' => nl2br($xmlvo->value('/content/s22data_title_ch')),
];
$tpl->assignGlobal($data);
// DATA
$data = array();
$sql = "SELECT * FROM ".$CFG->tbext."product_cate where pid=-1 and inuse=1 order by seq asc";
$res = @sql_query($sql);
while($row = @sql_fetch_assoc($res)){
	$xmlvo1 = new parseXML($row['imagexml']);
  $pid = $row['id'];
	$data["name"] = $row["catename"];
	$tpl->newBlock("menus");
	$tpl->assign($data);
  for($i=1;$i<=5;$i++) {
      $data2 = array();
      $img = $xmlvo1->value('/content/cover'.$i);
      $data2['img'] = $img ? $CFG->url_web."archive/images/product_cate/".$img : '';
      $data2['img_alt'] = $xmlvo1->value('/content/cover'.$i.'_alt');
      if (!empty($img)) {
      };
    	$tpl->newBlock("menus_imgs");
    	$tpl->assign($data2);
  }
  // 次分類 & 項目
  $sql = "SELECT * FROM ".$CFG->tbext."product_cate where inuse=1 and pid='$pid' order by seq asc";
  $resc = @sql_query($sql);
  if ($row['leaf']==0) {
    while($cate = @sql_fetch_assoc($resc)){
      $cateid = $cate["id"];
      $sql = "SELECT * FROM ".$CFG->tbext."product where inuse=1 and cateId='$cateid' order by seq asc";
      $res2 = @sql_query($sql);
      $num = @sql_num_rows($res);
      if ($num>0) {
        $data = array('name'=>$cate["catename"]);
        $tpl->newBlock("tag");
        $tpl->assign($data);

        while($row2 = @sql_fetch_assoc($res2)){
          $xmlvo2 = new parseXML($row2['imagexml']);
          $cover = $xmlvo2->value('/content/cover');
          $data = array(
            "name"=>$row2["title"],
            "note"=>$row2["note"],
            "price"=>$row2["price"],
            "img"=>!empty($cover) ? $CFG->url_web."archive/images/product/".$cover : '',
            "img_alt"=>$xmlvo2->value('/content/cover'.'_alt'),
          );
          $tpl->newBlock("items");
          $tpl->assign($data);
        }
      }
    }
  } else {
    $data = array('name'=>'');
    $tpl->newBlock("tag");
    $tpl->assign($data);

    $sql = "SELECT * FROM ".$CFG->tbext."product where inuse=1 and cateId='$pid' order by seq asc";
    $res2 = @sql_query($sql);
    while($row2 = @sql_fetch_assoc($res2)){
      $xmlvo2 = new parseXML($row2['imagexml']);
      $cover = $xmlvo2->value('/content/cover');
      $data = array(
        "name"=>$row2["title"],
        "note"=>$row2["note"],
        "price"=>$row2["price"],
        "img"=>!empty($cover) ? $CFG->url_web."archive/images/product/".$cover : '',
        "img_alt"=>$xmlvo2->value('/content/cover'.'_alt'),
      );
      $tpl->newBlock("items");
      $tpl->assign($data);
    }
  }
}


// MEAL 套餐
$data = [
  's23data_title_en' => nl2br($xmlvo->value('/content/s23data_title_en')),
  's23data_title_ch' => nl2br($xmlvo->value('/content/s23data_title_ch')),
  's23data_popupNote' => nl2br($xmlvo->value('/content/s23data_popupNote')),
  's23data_popupNotenote' => nl2br($xmlvo->value('/content/s23data_popupNotenote')),
  's23data_priceNote' => nl2br($xmlvo->value('/content/s23data_priceNote')),
];
$tpl->assignGlobal($data);
// 套餐
$data = array();
$sql = "SELECT * FROM ".$CFG->tbext."meal where inuse=1 order by seq asc";
$res = @sql_query($sql);
while($row = @sql_fetch_assoc($res)){
  $id = $row['id'];
  $xmlvo1 = new parseXML($row['imagexml']);
  $cover = $xmlvo1->value('/content/cover');
  $data = array(
    "name"=>$row["title"],
    "type"=>nl2br($row["type"]),
    "price"=>$row["price"],
    "broth"=>$xmlvo1->value('/content/broth'),
    "broth_items1"=>nl2br($xmlvo1->value('/content/broth_items1')),
    "broth_items2"=>nl2br($xmlvo1->value('/content/broth_items2')),
    "broth_items3"=>nl2br($xmlvo1->value('/content/broth_items3')),
    "nameImg"=>!empty($cover) ? $CFG->url_web."archive/images/meal_cover/".$cover : '',
    "nameImg_alt"=>$xmlvo1->value('/content/cover'.'_alt'),
  );
  $tpl->newBlock("meals");
  $tpl->assign($data);

  for($i=1;$i<=5;$i++) {
    $cover = $xmlvo1->value('/content/cover'.$i);
    $data2 = array(
      "img"=>!empty($cover) ? $CFG->url_web."archive/images/meal/".$cover : '',
      "img_alt"=>$xmlvo1->value('/content/cover'.$i.'_alt'),
    );
    $tpl->newBlock("meals_imgs");
    $tpl->assign($data2);
  }

  // 菜單
  $subkey = 'stand1';
  $sql = "SELECT * FROM ".$CFG->tbext."product_rel where typeid='$id' AND subkey='$subkey' order by id";
  $rs = @sql_query($sql);
  while ($item = @sql_fetch_assoc($rs)) {
  	$data2 = array(
      "title" => $item['column1'],//htmlentities($item['column2'], ENT_QUOTES, "UTF-8"),
  		"chooseNum" => !empty($item['column3']) ? '1' : '',
  	);
  	$tpl->newBlock("otherDishes");
  	$tpl->assign($data2);

    $items = explode(',', $item['column2']);
    foreach ($items as $value) {
      $data3 = array('name'=>nl2br($value));
    	$tpl->newBlock("otherDishesDetail");
    	$tpl->assign($data3);
    }
  }
}


// ENVIRONMENT 店內寫真
$s3data_video_active = nl2br($xmlvo->value('/content/s3data_video_active'));
$data = [
  's3data_title_en' => nl2br($xmlvo->value('/content/s3data_title_en')),
  's3data_title_ch' => nl2br($xmlvo->value('/content/s3data_title_ch')),
  's3data_video_active' => !empty($s3data_video_active) ? 'true' : 'false',
  's3data_video' => $xmlvo->value('/content/s3data_video'),
];
$tpl->assignGlobal($data);
// ENVIRONMENT IMG
for($i=1;$i<=10;$i++) {
    $img = $xmlvo->value('/content/s3data_img'.$i);
    $data['img'] = $img ? $CFG->url_web."archive/images/s3data_img/".$img : '';
    $data['img_alt'] = $xmlvo->value('/content/s3data_img'.$i.'_alt');
    $data['type'] = $xmlvo->value('/content/s3data_type'.$i);
    $data['video'] = $xmlvo->value('/content/s3data_video'.$i);

  	$tpl->newBlock("s3data_img");
  	$tpl->assign($data);
}

// RECOMMEND 達人秘訣
$data = [
  's4data_title_en' => nl2br($xmlvo->value('/content/s4data_title_en')),
  's4data_title_ch' => nl2br($xmlvo->value('/content/s4data_title_ch')),
];
$tpl->assignGlobal($data);
// ENVIRONMENT IMG
for($i=1;$i<=10;$i++) {
    $img = $xmlvo->value('/content/s4data_img'.$i);
    $data['img'] = $img ? $CFG->url_web."archive/images/s4data_img/".$img : '';
    $data['img_alt'] = $xmlvo->value('/content/s4data_img'.$i.'_alt');
    $data['title'] = nl2br($xmlvo->value('/content/s4data_title'.$i));
    $data['content'] = nl2br($xmlvo->value('/content/s4data_content'.$i));

  	$tpl->newBlock("s4data_img");
  	$tpl->assign($data);
}

// NEWS 揭示板
$data = [
  's5data_title_en' => nl2br($xmlvo->value('/content/s5data_title_en')),
  's5data_title_ch' => nl2br($xmlvo->value('/content/s5data_title_ch')),
];
$tpl->assignGlobal($data);
// NEWS
$sql = "SELECT a.*, b.catename FROM ".$CFG->tbext."news as a
LEFT JOIN ".$CFG->tbext."news_cate as b ON a.cateId=b.id
where (a.publishtype = 'A' or (a.publishtype !='P' and a.startdate <= '$today' and a.stopdate >='$today') )
order by a.createdate desc, a.seq asc";
$res = @sql_query($sql);
while($row = @sql_fetch_assoc($res)){
	$xmlvo1 = new parseXML($row['imagexml']);

  $img = $xmlvo1->value('/content/cover');
	$data = array(
    // "catename"=>$row["catename"],
    "date"=>$row["createdate"],
    "img"=>!empty($img) ? $CFG->url_web."archive/images/news/".$img : '',
    "img_alt"=>$xmlvo1->value('/content/cover'.'_alt'),
    "title"=>nl2br($row["title"]),
    "subtitle"=>nl2br($row["summary"]),
    "content"=>htmlSubString(strip_tags($row["content"],40)),
    "latestTag"=>!empty($row["latest"]) ? 'true' : 'false',
    "id"=>$row["id"],
	);
	$tpl->newBlock("news");
	$tpl->assign($data);
}

#頁尾連絡資訊
$row=getConfigNew('footer_info');
$xmlvo = new parseXML($row);
$data['footer_title_en'] = nl2br($xmlvo->value('/content/title_en'));
$data['footer_title'] = nl2br($xmlvo->value('/content/title'));
$data['footer_tel_title'] = $xmlvo->value('/content/tel_title');
$data['footer_tel'] = $xmlvo->value('/content/tel');
$data['footer_add_title'] = $xmlvo->value('/content/add_title');
$data['footer_add'] = $xmlvo->value('/content/add');
$data['footer_time_title'] = $xmlvo->value('/content/time_title');
$data['footer_time'] = $xmlvo->value('/content/time');
$data['link_facebook'] = $xmlvo->value('/content/link_facebook');
$data['link_ig'] = $xmlvo->value('/content/link_ig');
$tpl->assignGlobal($data);

$tpl -> printToScreen();
?>
