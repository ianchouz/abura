<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../header.php';
checkAuthority($menu_id);

$mod = pgParam("mod", "");
if(isset($_POST["active"]) && $_POST["active"] == "run") {
    $dao->update_onepage();
    if($dao->action_message == "true") {
        echo "<script>info_msg('操作成功!!');</script>";
    } else {
        echo "<script>info_msg('$dao->action_message')</script>";
    }
}
$dao->load_onepage();
?>
<!-- @ Album  STEP: 1, includes -->
<?php
$_REQUEST["mainid"] = $dao->dbrow["id"];
if($mod!=="edit") {
    $_REQUEST["mainid"] = "tmp_".time();
}
include_once("photo/fn_set.inc");
//清理暫存相簿
$dao->tmpPhotoDelete();
?>
<link rel="stylesheet" href="../css/animate.css">
<script src="../js/jquery.shapeshift.js"></script>
<script src="../js/dropzone.js"></script>
<link rel="stylesheet" href="../css/dropzone.css">
<link rel="stylesheet" href="../css/fileUpload.css">
<style type="text/css">
.sqs-action-overlay { display: block; }
.sqs-action-overlay > div.resize-image-s { display: inline-block; }
.act-hide { display:none; }
.dropzone .dz-preview .dz-image img { width: auto; }
</style>
<script>
var cfg_mainid='<?=$mainid?>';
var work = <?=json_encode($incSet["work"])?>;
    work.name="work";
    $(window).load(function(){
        newdropzone(work);
    });

</script>
<?php  include('../include/dropzone.php');?>
<!-- @ Album STEP: 1, END-->

<form name="eForm1" id="eForm1" method="post" enctype="multipart/form-data">
<input type="hidden" name="active" value="run"/>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header"><?=$page_navigation.$headstr?></div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
        </div>
    </div>

    <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
        <div>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="2" style="text-align:left; font-size:14px;">Banner</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">標語</th>
                    <td class="x-td"><?=$dao->html('s1data_title_en')?>
                      <span style="color:red">特殊符號，請輸入||代替</span>
                    </td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">顯示為影片</th>
                    <td class="x-td"><?=$dao->html('s1data_video_active')?></td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">影片網址</th>
                    <td class="x-td"><?=$dao->html('s1data_video')?></td>
                </tr>
            </table>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1" valign="top">
                    <?php for($i=1;$i<=5;$i++) {?>
                    <td>
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <th class="x-th" colspan="2">Banner <?=$i?></th>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">標語</th>
                                <td class="x-td"><?=$dao->html('s1_title'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">圖片 <?=$i?></th>
                                <td class="x-td"><?=$dao->html('dsk'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">圖片 (手機版)</th>
                                <td class="x-td"><?=$dao->html('mbl'.$i)?></td>
                            </tr>
                        </table>
                    </td>
                    <?php }?>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left; font-size:14px;">STORY 會社介紹</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s21data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s21data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th">標語</th>
                    <td class="x-td"><?=$dao->html('s21data_intro_title')?></td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">說明</th>
                    <td class="x-td"><?=$dao->html('s21data_intro_content')?></td>
                </tr>
                <tr class="x-tr1">
                    <td class="x-td" colspan="2">
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <?php for($i=1;$i<=3;$i++) {?>
                                <th class="x-th">圖片<?=$i?></th>
                                <td class="x-td"><?=$dao->html('s21data_img'.$i)?></td>
                                <?php }?>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left; font-size:14px;">MENU 味自慢集</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s22data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s22data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse; font-size:14px;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left;">MEAL 套餐</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s23data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s23data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
            <table class="x-table" style=" border-collapse: collapse; font-size:14px;">
                <tr class="x-tr1">
                    <th class="x-th">視窗頁尾標題</th>
                    <td class="x-td"><?=$dao->html('s23data_popupNote')?></td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">視窗頁尾說明</th>
                    <td class="x-td"><?=$dao->html('s23data_popupNotenote')?></td>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">10%服務費</th>
                    <td class="x-td"><?=$dao->html('s23data_priceNote')?></td>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left;">ENVIRONMENT 店內寫真</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s3data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s3data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <th class="x-th">顯示為影片</th>
                                <td class="x-td" align="left"><?=$dao->html('s3data_video_active')?></td>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">影片網址</th>
                                <td class="x-td" align="left"><?=$dao->html('s3data_video')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1" valign="top">
                    <th class="x-th"> </th>
                    <?php for($i=1;$i<=5;$i++) {?>
                    <td>
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <th class="x-th" style="text-align:left;">圖片 <?=$i?></th>
                            </tr>
                            <tr class="x-tr1">
                                <td class="x-td"><?=$dao->html('s3data_img'.$i)?></td>
                            </tr>
                        </table>
                    </td>
                    <?php }?>
                </tr>
                <tr class="x-tr1" valign="top">
                    <th class="x-th"> </th>
                    <?php for($i=6;$i<=10;$i++) {?>
                    <td>
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <th class="x-th" style="text-align:left;">圖片 <?=$i?></th>
                            </tr>
                            <tr class="x-tr1">
                                <td class="x-td"><?=$dao->html('s3data_img'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <td class="x-td">影片優先<?=$dao->html('s3data_type'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <td class="x-td">影片網址<?=$dao->html('s3data_video'.$i)?></td>
                            </tr>
                        </table>
                    </td>
                    <?php }?>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left;">RECOMMEND 達人秘訣</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s4data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s4data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1" valign="top">
                    <?php for($i=1;$i<=5;$i++) {?>
                    <td>
                        <table class="x-table" style=" border-collapse: collapse;">
                            <tr class="x-tr1">
                                <th class="x-th">圖片 <?=$i?></th>
                                <td class="x-td"><?=$dao->html('s4data_img'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">標語</th>
                                <td class="x-td"><?=$dao->html('s4data_title'.$i)?></td>
                            </tr>
                            <tr class="x-tr1">
                                <th class="x-th">內容</th>
                                <td class="x-td"><?=$dao->html('s4data_content'.$i)?></td>
                            </tr>
                        </table>
                    </td>
                    <?php }?>
                </tr>
            </table>
            <div class="x-panel-bbar">
                <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                    <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
                </div>
            </div>

            <table class="x-table" style=" border-collapse: collapse;">
                <tr class="x-tr1">
                    <th class="x-th" colspan="5" style="text-align:left;">NEWS 揭示板</th>
                </tr>
                <tr class="x-tr1">
                    <th class="x-th">
                        <table class="x-table" style=" border-collapse: collapse; width: 800px;">
                            <tr class="x-tr1">
                                <th class="x-th">英文標語</th>
                                <td class="x-td"><?=$dao->html('s5data_title_en')?></td>
                                <th class="x-th">中文標語</th>
                                <td class="x-td"><?=$dao->html('s5data_title_ch')?></td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </table>
        </div>
    </div>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
        </div>
    </div>
</div>
</form>
<?php  include '../footer.php';?>
<script type="text/javascript">
$(document).ready(function(){
    $("#mtabs").tabs();
});

//編輯器設定-->
var editors = new Array();
var editor_fix_path = "<?=$dao->cfg["ed"]?>";

function goSubmit(){
    <?php if($useckeditor){?>
    deal_editor_to_sendajaxform();
    <?php }?>

    var errormessage = "";
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    $("#eForm1").submit();
}
</script>
