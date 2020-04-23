<?include 'config.php';

$localDir = @$_REQUEST["localDir"];
if (empty($localDir)){
  $localDir = $CFG->root_user_img;
}
$filename = @$_REQUEST["filename"];
$success = false;
if (!empty($filename)){
  if (is_file($localDir.$filename)){
    $filesizes = @filesize( $localDir.$filename);
    list($width, $height, $type, $attr) = @getimagesize( $localDir.$filename );
    if ($width !="" && $width !=0){
    	$success = true;
    }else{
    	$success = false;
    }
  }
}
$json = array(
  success => $success?"true":"false",
  width => $width,
  height => $height,
  type => $type,
  filesizes => $filesizes
);
echo json_encode($json);
?>