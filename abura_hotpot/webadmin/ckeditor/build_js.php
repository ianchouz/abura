<script type="text/javascript">

//勿修改共用設定, 個案可直接複製function內容, 在form script 取代function
function bind_editors(){
    $(".callckeditor").each(function(idx, item) {
        var tmpidx = idx;
        var _id = $(item).attr("id");
        if ($(item).hasClass('simple')){
            var tmped = CKEDITOR.replace( _id,{height:100,toolbar:<?=$CFG->simpleeditor_config?>});
            var $info = $('<?=$CFG->simpleeditor_info?>');
            $(this).before($info);
        }else{
            var tmped = CKEDITOR.replace(_id);
            var $info = $('<?=$CFG->editor_info?>');
            $(this).before($info);
        }
        var tmparr = new Array();
        tmparr[0] = tmped;
        tmparr[1] = _id;
        editors[tmpidx] = tmparr;
    });
}

function deal_editor_to_sendajaxform(){
    var elen = editors.length;
    for(var i=0; i < elen;i++){
        var tmparr = editors[i];
        var _id   = tmparr[1];
        var tmped = tmparr[0];
        var content = tmped.getData();
        $("#"+_id).val(content);
    }
}

$(document).ready(function(){
    bind_editors();
});
</script>