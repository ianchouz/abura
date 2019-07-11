<?php
include 'cate_dao.php';
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

//取得所在層級
$deep_now = $dao->getTreeDeep($qcateid);
?>
<form name="QForm1" id="QForm1" action="index.php" method="post">
<input type="hidden" name="mod" value=""/>
<input type="hidden" name="modData" value=""/>
<input type="hidden" name="qcateid" value="<?=$qcateid;?>"/>
<div class="x-panel" id="weblist">
    <div class="x-panel-header">
        <div class="page_navigation"><?=$page_navigation?> - 分類</div>
        <div class="div-navigation"><?=$subNavigation?></div>
    </div>
    <div class="x-panel-header">
        <div class="query_area">
            <div style="float:left;padding-top:5px;">
                &nbsp;關鍵字：<input type="text" name="qkeyvalue" size="20" value="<?=$dao->qVO->val("qkeyvalue")?>" maxLength="100"/>
                &nbsp;狀態：<select name="qinuse">
                    <option value="" <?=HSelChk("",$dao->qVO->val("qinuse"));?>>全部</option>
                    <option value="true" <?=HSelChk("true",$dao->qVO->val("qinuse"));?>>啟用</option>
                    <option value="false" <?=HSelChk("false",$dao->qVO->val("qinuse"));?>>停用</option>
                </select>
            </div>
            <div style="float:left;padding-top:0px;padding-left:20px;width:80px;"><Button type="button" title="查詢" class="button" onclick="goQuery();"><div class="btn_query">查詢</div></Button></div>
        </div>
        <div class="clearfloat"></div>
    </div>
    <div class="x-panel-body">
        <div class="list_but" title="請勾選項目後再點選按鈕">
            <?php if($deep_now>1) {?>
            <?php }?>
            <?php if($open) {?>
            <span class="l_ss" ></span>
            <span><Button type="button" title="刪除" class="button" onclick="runDel();"><div class="btn_del">刪除</div></Button></span>
            <?php }?>
            <span><Button type="button" title="啟用狀態" class="button" onclick="runUpColumn('啟用狀態','inuse',1);"><div class="btn_run">啟用狀態</div></Button></span>
            <span><Button type="button" title="停用狀態" class="button" onclick="runUpColumn('停用狀態','inuse',0);"><div class="btn_stop">停用狀態</div></Button></span>
            <span><Button type="button" title="更新編號" class="button" onclick="runUpdate();"><div class="btn_update">更新編號</div></Button></span>
            <?php if($open){ ?>
            <span style="color:blue;">｜</span>
            <span><Button type="button" title="新增" class="button" onclick="runAdd();"><div class="btn_add">新增</div></Button></span>
            <?php }?>
        </div>
        <div class="clearfloat"></div>
        <table id="x-data-list-table">
            <tr class="x-tr1">
                <th class="x-th" style="width:50px;"><div class="btn_ifelse" title="反向選取" onclick="runIfElse('sel[]');"/></th>
                <th class="x-th" style="width:50px;">操作</th>
                <th class="x-th" style="width:50px;">項次</th>
                <th class="x-th" style="width:100px;">排序編號</th>
                <?php if($deep_now==1) {?>
                <th class="x-th" style="width:60px;">圖片</th>
                <?php }?>
                <th class="x-th" >分類名稱</th>
                <th class="x-th" style="width:80px;">狀態</th>
            </tr>
            <?php for ($i=1; $i <= $pagetool->rrows; $i++){
                $item = sql_fetch_array($result);
                $rownum = ($pagetool->rowsperpage * ($pagetool->currentpage-1))+($i);
                $catename = $item['catename'];

        				//圖片
        				$imgsrc='';
        				$xmlvo  = new parseXML($item["imagexml"]);
        				$cover = $xmlvo->value('/content/cover1');
        				if ($cover !=""){
        					$imgsrc = $CFG->web_user.$dao->cfg["path"].$cover;
        				}
            ?>
            <tr class="x-tr<?=($i % 2)?'1':'2'?>">
                <td class="x-td"><input type="checkbox" name="sel[]" id="sel_<?=$item['id']?>"   value="<?=$item['id']?>"/></td>
                <td class="x-td">
                    <input type="button" value="  " alt="編輯" title="編輯" class="l_edit" onclick="runEdit('<?=$item['id']?>')"/>
                 <?php if($open){ ?>
                    <span class="l_split">&nbsp;</span>
                    <input type="button" value="  " alt="複製" title="複製" class="l_copy" onclick="runCopy('<?=$item['id']?>')"/>
                <?php }?>
                </td>
                <td class="x-td"><?=$rownum?></td>
                <td class="x-td"><input type="text" name="seq_<?=$item['id']?>" value="<?=$item['seq']?>" size="5" maxLength="5" onclick="setSel('sel_<?=$item['id']?>')"/></td>
                <?php if($deep_now==1) {?>
                <td class="x-td"><div class="listimg" style="background-image:url('<?=$imgsrc?>');"></div></td>
                <?php }?>
                <td class="x-td al" style="text-align:left;padding-left:5px;"><?php
                    if ($item['leaf']==true){
                        $rowsubcc = $dao->countSub($item['id']);
                        echo "<div class=\"leaf folder-btn\"><a href='javascript:goDetail(\"".$item['id']."\")'>".$catename."<span class='numshow'>(".$rowsubcc.")</span></a></div>";
                    }else{
                        $rowsubcc = $dao->countSubCategory($item['id']);
                        echo "<div class=\"folder folder-btn\" onclick=changePID('".$item['id']."')>".$catename."<span class='numshow'>(".$rowsubcc.")</span></div>";
                    }
                    ?>
                    <input type="hidden" id="rowsubcc_<?=$item['id']?>" value="<?=$rowsubcc?>" />
                </td>
                <td class="x-td"><?=Html_TF($item['inuse']);?></td>
            </tr>
            <?php }?>
        </table>
        <?php if ($pagetool->rrows==0){
            echo "<div style=\"text-align:left;padding:5px;\"><font color=\"red\"><b>查無資料</b></font></div>";
        }?>
    </div>
    <div class="x-panel-bbar"><?=$pagetool->builePage();?></div>
</div>
</form>
<?php  include '../footer.php';?>
<script>
var editPage="cate_edit.php";
var listPage="main.php";
var cateList="index.php";
</script>
