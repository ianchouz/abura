<?php 
function rel($key){
global $rel_cate_sel,$rel_cate,$sql_List,$CFG;
?>
 <div style="margin:5px;" id="btn_choice_open_<?=$key?>"><input type="button" value="選取" onclick="rel_choice('<?=$key?>')"></div>
    <div id="choice_form_<?=$key?>" style="height:0px;width:0px; background-color: #BEC2C3;position:relative;overflow:hidden;margin:10px;">
        <div style="margin:0px;text-align:right;">
            <input type="button" value="&nbsp;關&nbsp;&nbsp;閉&nbsp;" onclick="rel_close('<?=$key?>')">
        </div>
        <div style="margin-left:30px;">
            <?php if($rel_cate){?>
            分類：<select id="_cate_id_<?=$key?>">
            <option value="">請選擇</option><?
            $cc = 0;
            foreach ($rel_cate as $item) {
            echo "<option id='pid".$cc."' value='".$item['id']."'/>&nbsp;".$item['fullname']."</option>";
            $cc++;
            }?>
            </select>
            <?php }?>
            關鍵字：<input type="text" id="_keyword_<?=$key?>" size="20"> <input type="button" value="&nbsp;查&nbsp;&nbsp;詢&nbsp;" onclick="rel_query('<?=$key?>')">
        </div>
        <div style="margin:30px;overflow:auto;" id="___list_<?=$key?>"></div>
    </div>
    <div id="prod_rel_<?=$key?>_info" style="margin:30px;"></div>
    <div id="product_rel_<?=$key?>_list" style="position:relative;">
        <?php 
        $sql = sprintf($sql_List,$key);
        
        $rs = @sql_query($sql);
        while($item = @sql_fetch_assoc($rs)){
            $img="";
            $xmlvo    = new parseXML($item['imagexml']);
            $cover = $xmlvo->value('/content/cover');
            if (is_file($CFG->root_user.$CFG->product["path"].$cover )){
                $img="<img src=\"".($CFG->web_user.$CFG->product["path"].$cover)."\" style=\"float:left;margin:3px 0;\">";  
            }
            
            $xmlvo    = new parseXML($item['catename']);
            $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
            $xmlvo    = new parseXML($item['title']);
            $title = $xmlvo->value('/content/title'.$CFG->langs_show);              
            if($rel_cate_sel) $title=$catename ." - ".$item['vNumber'] ." ".$title;
            
        ?>
            <div class="relitem" product_id="<?=$item['id']?>" style="padding:5px 5px;">
                <input type="hidden" name="product_rel_<?=$key?>[]" value="<?=$item['id']?>"  ><?=$title?>
                
                <div class="btn_remove" d-type="<?=$key?>" title="刪除"></div><br>
                <?=$img?>
            </div>
        <?php }?>
    </div>
<?php }?>
<style>
.relitem .btn_remove {
    background: url("../images/remove.png") no-repeat scroll left top rgba(0, 0, 0, 0);
    cursor: pointer;
    height: 16px;
    position: absolute;
    right: 2px;
    top: 2px;
    width: 13px;
}
.relitem {
    background: none repeat scroll 0 0 #111111;
    color: #FFFFFF;
    float: left;
    font-size: 12px;
    line-height: 20px;
    margin: 10px;
    padding: 0 20px 0 10px;
    position: relative;
}
.p_show .pEdit {
    color: rgb(228, 209, 209);
    font-family: 微軟正黑體, 'Lucida Sans Unicode', 'Lucida Grande', sans-serif;
    font-size: 15px;
    line-height: 30px;
    letter-spacing: 0.1em;
}
</style>

