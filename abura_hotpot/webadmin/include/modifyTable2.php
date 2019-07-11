<?php 
function modifyTable($name="",$xmlstring="",$set=array(),$maxlength = 999){
global $dao,$CFG;
$fields= $set["fields"];
$fields_num=count($fields);
?>
    <div class="btnList" data-table="#modifyTable<?=$name?>">
        <input type="hidden" id="maxlength" name="maxlength" value="<?=$maxlength?>" />
        <div class="btn_panel"><Button type="button" title="刪除" class="button" ><div class="btn_del">刪除</div></Button></div>
        <div class="btn_panel"><Button id="AddButton" type="button" title="新增列" class="button" ><div class="btn_add" >新增列</div></Button></div> 
        <?=($maxlength != 999)?"<em>最多可新增".$maxlength."筆。</em>":""?>
    </div>
    <div  style="display: inline;"><span style="float:left;"> 滑鼠拖曳  </span> <span class="ui-icon ui-icon-arrowthick-2-n-s"  style="float:left;"></span> 可調整順序。</div>
    <table id="modifyTable<?=$name?>" class="modifyTable">
        <thead>
            <tr>
                <th class="x-th" style="width:50px;text-align:center;" >全選 <input type="checkbox" name="" class="ckb_list" value="ckbox"></th>
                <th class="x-th" style="width:50px;text-align:center;" >順序</th>
                <?php foreach($fields as $k => $v){ ?>
                <th class="x-th" style="text-align:center;width:<?=$v["width"]?>px;"><?=$v["name"]?><!-- <input type="text"> --></th>
                <?php } ?>
            </tr>
            <tr class="templeteRow">
                <td align="center" style="width:50px;text-align:center;"><input type="checkbox" name="ckbox[]" value=""></td>
                <td align="center"  style="width:50px;text-align:center;">
                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <input type="hidden" name="fseq<?=$name?>[]" value=""></td>
                <?php
                for($i=0;$i<$fields_num;$i++){
                    $ThisType = $fields[$i]['type'];
                    switch($ThisType){
                        case "textarea":
                    ?>
                <td style="text-align:center;"><textarea name="f<?=$i?><?=$name?>[]" class="input_full" style="width:100%; height:100px;"></textarea></td>
                    <?php
                        break;
                        default:
                    ?>
                <td style="text-align:center; vertical-align:top;"><input type="text" name="f<?=$i?><?=$name?>[]" value="" class="input_full" style="width:100%;"></td>
                    <?php
                        break;
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody><tr><td colspan="5"><span class="seq" style="display:none;">0</span></td></tr>
            <?php 
            if($xmlstring) {  
            $trees=array();
            $xml = simplexml_load_string($xmlstring);
            
            if($name){
                $trees = $xml->xpath('/content/content[@name="'.$name.'"]/data');
            }else if($xmlstring){
                $trees = $xml->xpath('/content/content/data');
            }

            usort($trees, 'sortTrees');
            $resultK=count($trees)-1;   
            foreach ($trees as $key=>$val) {
            ?>
            <tr>
                <td align="center" style="width:50px;text-align:center;"><input type="checkbox" name="ckbox[]" ></td>
                <td align="center" style="width:50px;text-align:center;">
                <span class="seq"><?=($key+1)?></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <input type="hidden" name="fseq<?=$name?>[]" value=""></td>
                <?php
                for($i=0;$i<$fields_num;$i++){
                    $ThisType = $fields[$i]['type'];
                    switch($ThisType){
                        case "textarea":
                    ?>
                <td style="text-align:center;"><textarea name="f<?=$i?><?=$name?>[]" id="f<?=$i?><?=$name?>[]" class="input_full" style="width:100%; height:100px;">111<?=$val->key[$i]?></textarea></td>
                    <?php
                        break;
                        default:
                    ?>
                <td style="text-align:center; vertical-align:top;"><input type="text" name="f<?=$i?><?=$name?>[]" value="<?=$val->key[$i]?>" class="input_full" style="width:100%;"></td>
                    <?php
                        break;
                    }
                    
                    /*  ?>
                <td  style="text-align:center;"><input type="text" name="f<?=$i?><?=$name?>[]" value="<?=$val->key[$i]?>" style="width:100%;"></td>
                         <?php*/
                }
                ?>                
            </tr>
            <?php } 
            }
            ?>
        </tbody>
    </table>


<?php }?>
<style>
.modifyTable tr span { margin-top: -1.3em;  }
</style>
  <style type="text/css">
                        .btnList{
                            width:100%;
                            height:50px;
                            float:left;
                            margin:10px 0;
                        }
                        .btnList button{
                            margin-right: 10px;
                        }
                    </style>
<script type="text/javascript">
$(document).ready(function(){
    var maxlength = parseInt($("#maxlength").val()) +1;

    $(".modifyTable tbody ").sortable({
        update: function (event, ui) {
            $(this).find('span.seq').each(function(indx){
               $(this).html(indx);
            });
        }
    });
    var templeteRow=$(".modifyTable").find("tr.templeteRow");
    templeteRow.hide();
    $(".btn_add").on("click",function(event){
        var TableName=$(this).parent().parent().parent().attr("data-table");
        var maxNum=$( TableName+" span.seq" ).maxNum()+1;
        
        templeteRow=$(TableName).find("tr.templeteRow");
        templeteRow.find("span.seq").html(maxNum);
        //templeteRow.find("input[name^='fseq']").val(maxNum);
        var newRow = templeteRow.clone(true).removeClass("templeteRow").show();
        $(TableName+" tbody").append(newRow);

        if($(TableName+" input[name^=ckbox]").size() >= maxlength){
            $("#AddButton").hide();
        }else{
            $("#AddButton").show();
        }
    });
    $(".btn_del").on("click",function(){
        var TableName=$(this).parent().parent().parent().attr("data-table");
        $(TableName+' input[name^=ckbox]:checked').each(function(indx){
           $(this).parent().parent("tr").remove();
        });
        if($(TableName+" input[name^=ckbox]").size() >= maxlength){
            $("#AddButton").hide();
        }else{
            $("#AddButton").show();
        }
    });
    $('.ckb_list').click(function(){
        var TableName=$(this).parents("table").attr("id");
        var status="";
        var list=$(this).val(); 
        var name=list;
        if($(this).is(':checked')) status=true; else status=false;
        $("#"+TableName+' input[name^='+name+'][type=checkbox]').each(function(indx){
            console.log(status);
            $(this).attr("checked",status);   
            $(this).prop("checked", status);  
        });        
    });
});
</script>