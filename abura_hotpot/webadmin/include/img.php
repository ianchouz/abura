<?
 include 'config.php';
 $img_height = 20;  // 圖形高度
 $img_width = 70;   // 圖形寬度
 $mass = 0;        // 雜點的數量，數字愈大愈不容易辨識

 $num="";              // rand後所存的地方
 $num_max = 5;      // 產生6個驗證碼
 for( $i=0; $i<$num_max; $i++ )
 {
     $num .= rand(0,9);
 }

 $_SESSION["Checknum"] = $num;  // 將產生的驗證碼寫入到session

 // 創造圖片，定義圖形和文字顏色
 Header("Content-type: image/PNG");
 srand((double)microtime()*1000000);
 $im = imagecreate($img_width,$img_height);
 $black = ImageColorAllocate($im, 0,0,0);         // (0,0,0)文字為黑色
 $gray = ImageColorAllocate($im, 200,200,200); // (200,200,200)背景是灰色
 imagefill($im,0,0,$gray);
/*
 // 隨機給予兩條虛線，起干擾作用
 $style = array($black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray);
 imagesetstyle($im, $style);
 $y1=rand(0,$img_height);
 $y2=rand(0,$img_height);
 $y3=rand(0,$img_height);
 $y4=rand(0,$img_height);
 imageline($im, 0, $y1, $img_width, $y3, IMG_COLOR_STYLED);
 imageline($im, 0, $y2, $img_width, $y4, IMG_COLOR_STYLED);
  */
 // 在圖形產上黑點，起干擾作用;
 for( $i=0; $i<$mass; $i++ )
 {
 imagesetpixel($im, rand(0,$img_width), rand(0,$img_height), $black);
 }

 // 將數字隨機顯示在圖形上,文字的位置都按一定波動範圍隨機生成
 $strx=rand(3,8);
 for( $i=0; $i<$num_max; $i++ )
 {
      $strpos=rand(1,3);
      imagestring($im,5,$strx,$strpos, substr($num,$i,1), $black);
      $strx+=rand(8,14);
 }
 ImagePNG($im);
 ImageDestroy($im);
 ?>