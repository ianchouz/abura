<?php
// 以下數組存放允許上傳的文件類型
$allowed_image_types  = array ( 'image/pjpeg' => "jpg" , 'image/jpeg' => "jpg" , 'image/jpg' => "jpg" , 'image/png' => "png" , 'image/x-png' => "png" , 'image/gif' => "gif" , 'image/svg+xml' => "svg");
$allowed_image_ext  = array_unique ( $allowed_image_types );

foreach  ( $allowed_image_ext  as  $mime_type  => $ext ) {
  $image_ext .= strtoupper ( $ext ). " " ;
}
  function setMemoryForImage( $filename ){
    //echo "reset:".$filename."<br>";
    $imageInfo = getimagesize($filename);
    $MB = 1048576; // number of bytes in 1M
    $K64 = 65536; // number of bytes in 64K
    $TWEAKFACTOR = 1.85; // Or whatever works for you
    $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
      * $imageInfo['bits']
      * $imageInfo['channels'] / 8
      + $K64
      ) * $TWEAKFACTOR
    );
    //Default memory limit is 8MB so well stick with that.
    //To find out what yours is, view your php.ini file.
    $memoryLimit = 8 * $MB;
    if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit){
      $newLimit = $memoryLimit + ceil( ( memory_get_usage() + $memoryNeeded - $memoryLimit) / $MB);
      $newLimit=$newLimit + 3000000;
      ini_set( 'memory_limit', $newLimit . 'M' );
      return true;
    } else {
      return false;
    }
  }


function smart_resize_image($thumb_image_name, $file, $width = 0, $height = 0 ,$start_width =0, $start_height=0) {
  $proportional = false;
  $output = 'file';
  $delete_original = false;
  $use_linux_commands = false;
  if ( $height <= 0 && $width <= 0 ) {
    return false;
  }
  $info = getimagesize($file);
  $image = '';
  $final_width = 0;
  $final_height = 0;
  list($width_old, $height_old) = $info;
  if ($proportional) {
    if ($width == 0) $factor = $height/$height_old;
    elseif ($height == 0) $factor = $width/$width_old;
    else $factor = min ( $width / $width_old, $height / $height_old);
    $final_width = round ($width_old * $factor);
    $final_height = round ($height_old * $factor);
  } else {
    $final_width = ( $width <= 0 ) ? $width_old : $width;
    $final_height = ( $height <= 0 ) ? $height_old : $height;
  }

  $final_width = $width;
  $final_height = $height;
  switch ($info[2] ) {
    case IMAGETYPE_GIF:
      $image = imagecreatefromgif($file);
      break;
    case IMAGETYPE_JPEG:
      $image = imagecreatefromjpeg($file);
      break;
    case IMAGETYPE_PNG:
      $image = imagecreatefrompng($file);
      break;
    default:
      return false;
  }
  $image_resized = imagecreatetruecolor( $final_width, $final_height );
  if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
    $trnprt_indx = imagecolortransparent($image);
    // If we have a specific transparent color
    if ($trnprt_indx >= 0) {
      // Get the original image's transparent color's RGB values
      $trnprt_color = imagecolorsforindex($image, $trnprt_indx);
      // Allocate the same color in the new image resource
      $trnprt_indx = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
      // Completely fill the background of the new image with allocated color.
      imagefill($image_resized, 0, 0, $trnprt_indx);
      // Set the background color for new image to transparent
      imagecolortransparent($image_resized, $trnprt_indx);
    }
    // Always make a transparent background color for PNGs that don't have one allocated already
    elseif ($info[2] == IMAGETYPE_PNG) {
      // Turn off transparency blending (temporarily)
      imagealphablending($image_resized, false);
      // Create a new transparent color for image
      $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
      // Completely fill the background of the new image with allocated color.
      imagefill($image_resized, 0, 0, $color);
      // Restore transparency blending
      imagesavealpha($image_resized, true);
    }
  }

  imagecopyresampled($image_resized, $image, 0, 0, $start_width, $start_height, $final_width, $final_height, $width_old, $height_old);

  if ( $delete_original ) {
    if ( $use_linux_commands )
      exec('rm '.$file);
    else
      @unlink($file);
  }

  switch ( strtolower($output) ) {
    case 'browser':
      $mime = image_type_to_mime_type($info[2]);
      header("Content-type: $mime");
      $output = NULL;
      break;
    case 'file':
      $output = $thumb_image_name;
      break;
      case 'return':
      return $image_resized;
      break;
    default:
      break;
  }

  switch ($info[2] ) {
    case IMAGETYPE_GIF:
      imagegif($image_resized, $output);
      break;
    case IMAGETYPE_JPEG:
      imagejpeg($image_resized, $output);
      break;
    case IMAGETYPE_PNG:
      imagepng($image_resized, $output);
      break;
    default:
      return false;
  }
  return true;
}

function  resizeImage( $image , $width , $height , $newwidth , $newheight ) {

  list( $imagewidth , $imageheight , $imageType ) = getimagesize ( $image );
  $imageType  = image_type_to_mime_type( $imageType );
  $scalex = 1;
  $scaley = 1;
  if($height > $width && ($width-$newwidth)>0){
    $scalex = ( $newwidth / $width );
    $scaley = $scalex;
  }
  else if (($height-$newheight)>0){
    $scalex = ( $newheight / $height );
    $scaley = $scalex;
  }

  if( $scalex > 1 ){ $scalex = 1; }
  if( $scaley > 1 ){ $scaley = 1; }
  $newImageWidth = round($width * $scalex);
  $newImageHeight =  round($height * $scaley);
  //$newImageWidth  = ceil ( $width  * $scale );
  //$newImageHeight  = ceil ( $height  * $scale );
  setMemoryForImage($image);
  $newImage  = imagecreatetruecolor( $newImageWidth , $newImageHeight );
  switch ( $imageType ) {
    case  "image/gif" :
      $source =imagecreatefromgif( $image );
      break ;
    case  "image/pjpeg" :
    case  "image/jpeg" :
    case  "image/jpg" :
      $source =imagecreatefromjpeg( $image );
      break ;
    case  "image/png" :
    case  "image/x-png" :
      $source =imagecreatefrompng( $image );
      break ;
  }
  imagecopyresampled( $newImage , $source ,0,0,0,0, $newImageWidth , $newImageHeight , $width , $height );
  switch ( $imageType ) {
    case  "image/gif" :
      imagegif( $newImage , $image );
      break ;
    case  "image/pjpeg" :
    case  "image/jpeg" :
    case  "image/jpg" :
      imagejpeg( $newImage , $image ,100);
      break ;
    case  "image/png" :
    case  "image/x-png" :
      imagepng( $newImage , $image );
      break ;
  }

  chmod ( $image , 0777);
  return  $image ;

}
//裁切主函數
function  resizeThumbnailImage( $thumb_image_name , $file , $width , $height , $start_width , $start_height , $scale ){
  list( $width_old , $height_old , $imageType ) = getimagesize ( $file );
  $info = getimagesize($file);
  $imageType  = image_type_to_mime_type( $imageType );
  
  setMemoryForImage($file);

  $newImageWidth  = round ( $width  * $scale );
  $newImageHeight  = round ( $height  * $scale );
  
  //echo $width_old.' px * '.$height_old.' px<br>';
  //echo $newImageWidth.' px * '.$newImageHeight.' px<br>';

  switch ($info[2] ) {
    case IMAGETYPE_GIF:
      $image = imagecreatefromgif($file);
      break;
    case IMAGETYPE_JPEG:
      $image = imagecreatefromjpeg($file);
      break;
    case IMAGETYPE_PNG:
      $image = imagecreatefrompng($file);
      break;
    default:
      return false;
  }
  $image_resized = imagecreatetruecolor( $newImageWidth, $newImageHeight );
  if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
    $trnprt_indx = imagecolortransparent($image);
    // If we have a specific transparent color
    if ($trnprt_indx >= 0) {
      // Get the original image's transparent color's RGB values
      $trnprt_color = imagecolorsforindex($image, $trnprt_indx);
      // Allocate the same color in the new image resource
      $trnprt_indx = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
      // Completely fill the background of the new image with allocated color.
      imagefill($image_resized, 0, 0, $trnprt_indx);
      // Set the background color for new image to transparent
      imagecolortransparent($image_resized, $trnprt_indx);
    }
    // Always make a transparent background color for PNGs that don't have one allocated already
    elseif ($info[2] == IMAGETYPE_PNG) {
      // Turn off transparency blending (temporarily)
      imagealphablending($image_resized, false);
      // Create a new transparent color for image
      $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
      // Completely fill the background of the new image with allocated color.
      imagefill($image_resized, 0, 0, $color);
      // Restore transparency blending
      imagesavealpha($image_resized, true);
    }
  }

  imagecopyresampled($image_resized, $image, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);


  switch ($info[2] ) {
    case IMAGETYPE_GIF:
      imagegif($image_resized, $thumb_image_name);
      break;
    case IMAGETYPE_JPEG:
      imagejpeg($image_resized, $thumb_image_name);
      break;
    case IMAGETYPE_PNG:
      imagepng($image_resized, $thumb_image_name);
      break;
    default:
      return false;
  }

  return  $thumb_image_name ;
}

function  getHeight( $image ) {
  $size  = getimagesize ( $image );
  $height  = $size [1];
  return  $height ;
}

function  getWidth( $image ) {
  $size  = getimagesize ( $image );
  $width  = $size [0];
  return  $width ;
}

function getThumbName($oldname,$width=0,$height=0){
  $newappendarr = array();
  $newappendarr[] = "_thumb";
  $newappendarr[] = "_".$width;
  $newappendarr[] = "_".$height;
  $newfile = substr($oldname,0,strrpos($oldname,'.'));
  //移除原本的thumb
  if (strrpos($newfile,'_thumb') > 0){
    $newfile = substr($newfile,0,strrpos($newfile,'_thumb'));
  }
  //檢查要新增加的字串是否在最後面
  foreach ($newappendarr as $item){
    $pos = strrpos($newfile,$item);
    $len = strlen($newfile)-strlen($item);
    if ($pos===false || $pos != $len){
      $newfile = $newfile.$item;
    }
  }
  $newfileext = strtolower(strrchr($oldname,'.'));
  return $newfile.$newfileext;
}
?>