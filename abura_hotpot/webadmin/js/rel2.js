
 //相關產品//相關配件//相關顏色------------------------------------------------
function rel_choice(typ,myid){
    $("#btn_choice_open_"+typ).hide();
    $("#choice_form_"+typ).stop(true,false).animate({'height':300+'px','width':'95%','display':'block','overflow':'auto'});
	rel_query(typ,myid);
}

function rel_close(typ){
    $("#btn_choice_open_"+typ).show();
    $("#choice_form_"+typ).stop(true,false).animate({'height':0+'px','width':0+'px','display':'none','overflow':'hidden'});
}

var xhr;
function rel_query(typ,myid){
    if(xhr && xhr.readystate != 4){
        xhr.abort();
    }

    $('#___list_'+typ).html('').stop(true,false).animate({'height':200+'px'}).addClass('loading');
    xhr = $.ajax({
        url: "rel/choice_query_action.php",
        type: 'POST',
        data: {'typ':typ,cid:$("#cid_"+typ).val(),keyword:$("#_keyword_"+typ).val(),'myid':myid},
        dataType: 'html',
        success: function(data){
            if (data!=""){
                $('#___list_'+typ).html(data).removeClass('loading');
            }
            fn_recheck(typ);
        }
    });
}

function fn_recheck(typ){
    $('#___list_'+typ+' .btn').show();
    $('#relTable input[name="product_rel_'+typ+'[]"').each(function(){
        var val = $(this).val();
        $('#___list_'+typ+' #list_product_'+val +'_'+typ+' .btn').hide();
    });
}

function fn_remove(obj){
    var typ=obj.attr("d-type");
    obj.parent().remove();
    $("#prod_rel_"+typ+"_info").html('<span style="color:red;">請記得存檔!!</span>');
    fn_recheck(typ);
}

function addrel(product_id,desc,typ,img){
  var newItem = $('<div class="relitem" product_id="'+product_id+'"><input type="text" name="product_rel_'+typ+'[]" value="'+product_id+'" style="display:none;">'+desc+'<div class="btn_remove" title="刪除" d-type="'+typ+'"></div></div>');
    $("#product_rel_"+typ+"_list").append(newItem);
    $("#product_rel_"+typ+"_list").find(".btn_remove").click(function(event){fn_remove($(this));});
    fn_recheck(typ);
}

$(document).ready(function(){
    $(".btn_remove").click(function(){
        var typ=$(this).attr("d-type");
        $(this).parent().remove();
        $("#prod_rel_"+typ+"_info").html('<span style="color:red;">請記得存檔!!</span>');
        fn_recheck();
    });
});