<?php 
function modifyTable($name,$xmlstring=""){
global $dao,$CFG;
?>
    <div class="btnList" data-table="#modifyTable<?=$name?>">
        <!-- <div class="btn_panel"><Button type="button" title="新增欄" class="button" ><div class="btn_add">新增欄</div></Button></div>     -->
        <div class="btn_panel"><Button type="button" title="新增列" class="button" ><div class="btn_add" >新增列</div></Button></div> 
        <div class="btn_panel"><Button type="button" title="刪除" class="button" ><div class="btn_del">刪除</div></Button></div> 
        <!-- <div class="btn_panel"><Button type="button" title="欄位排序" class="button" ><div class="btn_update ">欄位排序</div></Button></div>     -->
    </div>
    <div  style="display: inline;"><span style="float:left;"> 滑鼠拖曳  </span> <span class="ui-icon ui-icon-arrowthick-2-n-s"  style="float:left;"></span> 可調整順序。</div>
    <!-- <?=$dao->html('showtop', 'checkbox')?> 使用欄位標題<br><br> -->
    <table id="modifyTable<?=$name?>" class="modifyTable">
        <thead>
            <tr>
                <th class="x-th" style="width:50px;text-align:center;" >全選 <input type="checkbox" name="" class="ckb_list" value="ckbox"></th>
                <th class="x-th" style="width:50px;text-align:center;" >順序</th>
                <th class="x-th" style="text-align:center;width:200px;">標題<!-- <input type="text"> --></th>
                <th class="x-th" style="text-align:center;width:500px;">值<!-- <input type="text"> --></th>
            </tr>
            <tr class="templeteRow">
                <td align="center" style="width:50px;text-align:center;"><input type="checkbox" name="ckbox[]" value=""></td>
                <td align="center"  style="width:50px;text-align:center;">
                <span class="seq">0</span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <input type="hidden" name="fseq<?=$name?>[]" value=""></td>
                <td  style="text-align:center;width:200px;"><input type="text" name="f1<?=$name?>[]" value="" style="width:100%;"></td>
                <td  style="text-align:center;width:500px;"><input type="text" name="f2<?=$name?>[]" value="" class="input_full"></td>
            </tr>
        </thead>
        <tbody>
            <?php 
            /*$info='<content>
            <data seq="1" ><key>data 1</key><val>value 1</val></data>
            <data seq="2" ><key>data 2</key><val>value 2</val></data>
            <data seq="5" ><key>data 5</key><val>value 5</val></data>
            <data seq="4" ><key>data 4</key><val>value 4</val></data>
            </content>';
            $dao->dr["tableXML"] = <<<EOS
            $info
            EOS;*/                  
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
                <td align="center"  style="width:50px;text-align:center;">
                <span class="seq"><?=($key+1)?></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <input type="hidden" name="fseq<?=$name?>[]" value=""></td>
                <td  style="text-align:center;width:200px;"><input type="text" name="f1<?=$name?>[]" value="<?=$val->key[0]?>" style="width:100%;"></td>
                <td  style="text-align:center;width:500px;"><input type="text" name="f2<?=$name?>[]" value="<?=$val->val[0]?>" class="input_full"></td>
            </tr>
            <?php } ?>
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
    $(".modifyTable tbody ").sortable({
        update: function (event, ui) {
            $('span.seq').each(function(indx){
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
    });
    $(".btn_del").on("click",function(){
        var TableName=$(this).parent().parent().parent().attr("data-table");
        $(TableName+' input[name^=ckbox]:checked').each(function(indx){
           $(this).parent().parent("tr").remove();
        });        
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