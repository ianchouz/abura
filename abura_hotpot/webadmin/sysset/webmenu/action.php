<?php
include_once("dao.php");
if(!checkAuthority($menu_id)) {
    build(false, "您無此權限!!");
}
$mod = pgParam("mod", "");
if(isset($_POST["active"]) && $_POST["active"] == "run") {
    $dao->loadForm();
    if($mod == "edit") {
        $actflag = $dao->update();
    } else {
        $actflag = $dao->insert();
    }
    if($actflag) {
        build(true);
    }
    build(false, $dao->action_message);
}
build(false, "請重新操作!!");
?>