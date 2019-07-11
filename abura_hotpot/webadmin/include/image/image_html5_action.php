<?php
  include("../../applib.php");
  $fileDir  = pgParam("fileDir",'');
  $rootpath = pgParam("rootpath",'');
  $webpath  = pgParam("webpath",$CFG->web_user);

  if($rootpath!='') {
  	$rootpath = base64_decode($rootpath);
  }else{
  	$rootpath = $CFG->root_user;
  }

  $upload_folder = $rootpath.$fileDir;

  foreach ($_FILES["ff"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
      $temploadfile = $_FILES["ff"]["tmp_name"][$key];
      $file_name = $_FILES["ff"]["name"][$key];

		  $fn_array=explode(".",$file_name);//分割檔名
		  $mainName = $fn_array[0];//檔名
		  $subName = strtolower($fn_array[1]);//副檔名,全部轉小寫


		  //中文檔名處理
		  if (mb_strlen($mainName,"Big5") != strlen($mainName))
		  {
		  	$mainName = "u".date("ymdHis").round(microtime(true) * 1000);//重新命名=檔名+日期
		  }
		  //中文檔名處理 end

		  //禁止特殊符號檔名
      		$chars = array('.','(',')',' ','\'','"','$','%','&');
      		$mainName = str_replace($chars,'_', $mainName);

      		$file_name = sprintf("%s.%s",$mainName,$subName);//組合檔名

		  //檔名與路徑組合
		  $upFilePath = $upload_folder.basename($file_name);


		  //檔名重覆處理
		  //$x=1;
		  //while(file_exists($upFilePath)){
		  //	$file_name = sprintf("%s_%d.%s",$mainName ,$x++ ,$subName);//組合檔名
		  //	$upFilePath = $upload_folder.'/'. basename($file_name);
		  //}
		  $result = move_uploaded_file($temploadfile , $upFilePath);
		  ////檔名重覆處理 end
		  if (is_file($upFilePath)){
		    echo '位置:'.$CFG->web_user.$fileDir.$file_name;
		  }else{
		    echo '<font color:red>上傳失敗!!'.$upFilePath.'</font>';
		  }

    }
  }



?>