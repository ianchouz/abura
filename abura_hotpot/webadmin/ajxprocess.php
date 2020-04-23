<?php include './applib.php';
$method = $_POST["actype"];
$ip     = $_SERVER['REMOTE_ADDR'];

switch($method) {
    ##city/zone
    case "GetSubCate":
        $serial=(int) $_POST["cityid"];
        $str="";
        $str=City::GetZoneList($serial);
        build(true,$str);
    break;
    ##zipcode
    case "GetZipcode":
        $serial=(int) $_POST["cityid"];
        $str="";
        $str=City::GetZipcode($serial);
        build(true,$str);
    break;
}
   
?>