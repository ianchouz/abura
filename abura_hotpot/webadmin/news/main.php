<?php
include 'dao.php';
include '../header.php';
checkAuthority($menu_id);
include '../include/pagelib.php';

$mod =$_POST["mod"];
$dao->controller($mod);

/*資料*/
$result = $dao->getQueryResult();
$qcateid = $dao->qVO->val("qcateid");

/*導覽*/
$subNavigation=$dao->subNavigation($qcateid);
?>
<form name="QForm1" id="QForm1" method="post">
<input type="hidden" name="mod" value=""/>
<input type="hidden" name="modData" value=""/>
<input type="hidden" name="qcateid" id="qcateid" value="<?=$qcateid?>"/>
<div class="x-panel" id="weblist">
    <div class="x-panel-header">
        <div class="page_navigation"><?=$page_navigation?></div>
        <div class="div-navigation"><?=$subNavigation?></div>
    </div>
    <div class="x-panel-header">
        <div class="query_area">
            <div style="float:left;padding-top:5px;">
                &nbsp;關鍵字：<input type="text" name="qkeyvalue" size="20" value="<?=$dao->qVO->val("qkeyvalue")?>" maxLength="100"/>
                &nbsp;狀態：<select name="qpublishtype">
                    <option value="all" <?=HSelChk("all",$dao->qVO->val("qpublishtype"));?>>全部</option>
                    <option value="A" <?=HSelChk("A",$dao->qVO->val("qpublishtype"));?>>永久</option>
                    <option value="P" <?=HSelChk("P",$querypars->qpublishtype);?>>暫停</option>
                    <option value="D" <?=HSelChk("D",$dao->qVO->val("qpublishtype"));?>>區間</option>
                </select>
                <!-- <div style="padding-top:5px;">
                    &nbsp;上架日期：<input type="text" name="qcreatedate" id="qcreatedate" size="10" value="<?=$dao->qVO->val("qcreatedate")?>" maxLength="10"/>
                    <span style="padding-left: 15px;"><input type="checkbox" name="qshowtop" id="qshowtop" value="Y" <?=$dao->qVO->val("qshowtop")=="Y"?'checked':''?>/>首頁顯示</span>
                </div> -->
                <div style="padding-top:5px;">
                    <span style="padding-left: 15px;"><input type="checkbox" name="qlatest" id="qlatest" value="Y" <?=$dao->qVO->val("qlatest")=="Y"?'checked':''?>/>最新</span>
                </div>
            </div>
            <div style="float:left;padding-top:0px;padding-left:20px;width:80px;"><Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button></div>
            <?php if($qcateid!="" && $qcateid!="-1" && $useCate == 1) {?>
            <div style="float:left;padding-top:0px;padding-left:5px;"><Button type="button" title="不分類查詢" class="button" onclick="$('#qcateid').val('');goQuery();"><div class="btn_query">不分類查詢</div></Button></div>
            <?php }?>
        </div>
        <div class="clearfloat"></div>
    </div>
    <div class="x-panel-body">
        <div class="list_but">
            <span class="l_ss" style="float:none;"　title="請勾選項目後再點選按鈕"></span>
            <span><Button type="button" title="刪除" class="button" onclick="runDel();"><div class="btn_del">刪除</div></Button></span>
            <span><Button type="button" title="啟用狀態" class="button" onclick="runUpColumn('啟用狀態','publishtype','A');"><div class="btn_run">啟用狀態</div></Button></span>
            <span><Button type="button" title="停用狀態" class="button" onclick="runUpColumn('停用狀態','publishtype','P');"><div class="btn_stop">停用狀態</div></Button></span>
            <span><Button type="button" title="更新編號" class="button" onclick="runUpdate();"><div class="btn_update">更新編號</div></Button></span>
            <!-- <span><Button type="button" title="設定首頁顯示" class="button" onclick="runUpColumn('設定為首頁顯示','showindex','Y');"><div class="btn_update">設定為首頁顯示</div></Button></span>
            <span><Button type="button" title="取消為首頁顯示"   class="button" onclick="runUpColumn('取消為首頁顯示','showindex','N');"><div class="btn_update">取消為首頁顯示</div></Button></span>
            <span style="color:blue;">｜</span>
            <span><Button type="button" title="設定列表置頂" class="button" onclick="runUpColumn('設定為列表置頂','showtop','Y');"><div class="btn_update">設定為列表置頂</div></Button></span>
            <span><Button type="button" title="取消為列表置頂"   class="button" onclick="runUpColumn('取消為列表置頂','showtop','N');"><div class="btn_update">取消為列表置頂</div></Button></span> -->
            <!-- <span style="color:blue;">｜</span> -->
            <span><Button type="button" title="設定最新" class="button" onclick="runUpColumn('設定為最新','latest','Y');"><div class="btn_update">設定為最新</div></Button></span>
            <span><Button type="button" title="取消為最新"   class="button" onclick="runUpColumn('取消為最新','latest','N');"><div class="btn_update">取消為最新</div></Button></span>
            <span style="color:blue;">｜</span>
            <span><Button type="button" title="新增" class="button" onclick="runAdd();"><div class="btn_add">新增</div></Button></span>
        </div>
        <div class="clearfloat"></div>
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');"/></th>
                <th class="x-th" style="width:50px;">操作</th>
                <th class="x-th" style="width:50px;">項次</th>
                <?php if($qcateid =="" && $useCate == 1) {?><th class="x-th" style="width:100px;">分類</th><?php }?>
                <th class="x-th" style="width:100px;">排序編號</th>
                <th class="x-th" style="width:100px;">上架日期</th>
                <th class="x-th" style="width:60px;">圖片</th>
                <th class="x-th" >標題</th>
                <th class="x-th" style="width:40%;">內容</th>
                <th class="x-th" style="width:100px;">最新</th>
                <!-- <th class="x-th" style="width:110px;">發佈型態</th> -->
                <!--@stand_th-->

                <th class="x-th" style="width:80px;">狀態</th>
            </tr>
            <?php
            for ($i=1; $i <= $pagetool->rrows; $i++){
                $item = sql_fetch_array($result);
                $trcolor="";
                if ($item['publishtype']=="D"){
                    $diff = compare2Date($item['stopdate'],"");
                    if ($diff>0){
                        $trcolor = " style='color:#C0C0C2;'";
                    }
                    $diff = compare2Date($item['startdate'],"");
                    if ($diff<0){
                        $trcolor = " style='color:#C0C0C2;'";
                    }
                }

                // $xmlvo    = new parseXML($item['catename']);
                // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
                // $xmlvo    = new parseXML($item['webURL']);
                // $webURL = $xmlvo->value('/content/webURL'.$CFG->langs_show);

        				//圖片
        				$imgsrc='';
        				$xmlvo  = new parseXML($item["imagexml"]);
        				$cover = $xmlvo->value('/content/cover');
        				if ($cover !=""){
        					$imgsrc = $CFG->web_user.$dao->cfg["path"].$cover;
        				}
                //--@stand_query
            ?>
            <tr class="x-tr<?=($i % 2)?'1':'2'?>" <?=$trcolor?>>
                <td class="x-td"><input type="checkbox" name="sel[]" id="sel_<?=$item['id']?>" value="<?=$item['id']?>"/></td>
                <td class="x-td">
                    <input type="button" value="　" alt="編輯" title="編輯" class="l_edit" onclick="runEdit('<?=$item['id']?>')"/>
                    <span class="l_split">&nbsp;</span>
                    <input type="button" value="　" alt="複製" title="複製" class="l_copy" onclick="runCopy('<?=$item['id']?>')"/>
                </td>
                <td class="x-td"><?=$i?></td>
                <?php if($qcateid =="" && $useCate == 1) {?>
                <td class="x-td al" ><a href="javascript:changeCATE('<?=$item['cateId']?>');" style="font-size:10pt;"><?=$catename?></a></td>
                <?php }?>
                <td class="x-td"><input type="text" name="seq_<?=$item['id']?>" value="<?=$item['seq']?>" size="5" maxLength="5" onclick="setSel('sel_<?=$item['id']?>')"/></td>

                <td class="x-td"><?=$item['createdate']?></td>
                <td class="x-td"><div class="listimg" style="background-image:url('<?=$imgsrc?>');"></div></td>
                <td class="x-td al"><?=$item['title']?></td>
                <td class="x-td al"><?=htmlSubString($item["content"],40)?></td>
                <!-- <td class="x-td"><?=$webURL==""?"文章內文":"網頁連結".($item['webNewWin']?"(另跳視窗)":"")?></td> -->
                <td class="x-td al"><?=$item['latest']=='Y'?'最新':''?></td>

                <!--@stand_td-->

                <td class="x-td"><?
                if($item['publishtype']=="A") {
                    echo "永久";
                } else if ($item['publishtype']=="P") {
                    echo "暫停";
                } else {
                    echo $item['startdate']." ~ ".$item['stopdate'];
                }?></td>
            </tr>
            <?php }?>
        </table>
        <?php
        //無資料顯示
        if ($pagetool->rrows==0){
            echo "<div style=\"text-align:left;padding:5px;\"><font color=\"red\"><b>查無資料</b></font></div>";
        }
        ?>
    </div>
    <div class="x-panel-bbar"><?=$pagetool->builePage();?></div>
</div>
</form>
<?php  include '../footer.php';?>
<script>
var editPage="edit.php";
var listPage="main.php";
var cateList="index.php";
$(document).ready(function(){
    datasel("qcreatedate");
});
</script>
