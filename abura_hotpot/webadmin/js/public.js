//index
function runAdd(){
    var f = document.getElementById("QForm1");
    f.action=editPage;
    wait_msg("請稍等...");
    f.mod.value="add";
    f.submit();
}

function runEdit(idx){
    var f = document.getElementById("QForm1");
    f.action=editPage+"?id=" + idx;
    wait_msg("請稍等...");
    f.mod.value="edit";
    f.submit();
}

function runCopy(idx){
    var f = document.getElementById("QForm1");
    f.action=editPage+"?id=" + idx;
    wait_msg("請稍等...");
    f.mod.value="copy";
    f.submit();
}

function runUpdate(){
    if (getBoxVals("sel[]")==""){
      warning_msg("<font color='red'><b>請先選擇欲更新編號的資料!!</b></font>");
      return false;
    }else if (!getBoxVals("sel[]")){
    }else{
      hiConfirm('您確認要更新選取的資料編號!?', '確認對話', function(r) {
        if (r){
          var f = document.getElementById("QForm1");
          wait_msg("請稍等...");
          f.mod.value="update";
          f.submit();
        }else{
          return false;
        }
      });
    }
}

function runDel(){
    //檢查是否有子項目，若有子項目，則自動取消打勾
    //$('input[name=cateid]:checked')
    var uncheck=0;
    $("input[name=sel\\[\\]]:checked").each(
      function() {
        var key = $(this).val();
        var subrow = $("#rowsubcc_"+key).val();
        if( typeof (subrow)=="undefined")subrow=0;
        
        if (subrow!=0  ){
          $(this).attr("checked",false);
          uncheck++;
        }
      }
    );
    var addmsg="";
      if (uncheck!=0){
        addmsg = "<br>您勾選的目錄中有"+uncheck+"個，子目錄尚有資料，不能刪除。";
      }
    if (getBoxVals("sel[]")==""){
      warning_msg("<font color='red'><b>請先選擇欲刪除的資料!!"+addmsg+"</b></font>");
      return false;
    }else if (!getBoxVals("sel[]")){
    }else{
      hiConfirm('您確認要刪除選取的資料!?'+addmsg, '確認對話', function(r) {
        if (r){
          var f = document.getElementById("QForm1");
          wait_msg("請稍等...");
          f.mod.value="del";
          f.submit();
        }else{
          return false;
        }
      });
    }
}

function runUpColumn(msgtype,modtype,value){
    if (getBoxVals("sel[]")==""){
        warning_msg("<font color='red'><b>請先選擇欲["+msgtype+"]的資料!!</b></font>");
        return false;
    }else if (!getBoxVals("sel[]")){
    }else{
        hiConfirm('您確認要修改選取的資料為['+msgtype+']!?', '確認對話', function(r) {
            if (r){
                var f = document.getElementById("QForm1");
                wait_msg("請稍等...");
                f.mod.value="runUpColumn";
                f.modData.value=modtype+"|"+value;
                f.submit();
            }else{
                return false;
            }
        });
    }
}

function goQuery(){
    var f = document.getElementById("QForm1");
    var errormessage = "";
    //檢查是不是正整數
    var rowsperpage = f.rowsperpage.value;
    if (rowsperpage && rowsperpage.value !="" && !isNumber(rowsperpage)){
        errormessage +="[每頁筆數]只能為數字<br/>";
    }
    var currentpage = f.currentpage.value;
    if (currentpage && currentpage.value !="" && !isNumber(currentpage)){
        errormessage +="[頁數]只能為數字<br/>";
    }
    if (errormessage!=""){
        warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
        return false;
    }
    wait_msg("請稍等...");
    f.mod.value="";
    f.submit();
}

function goDetail(id){
    wait_msg("請稍等...");
    location.href=listPage+"?qcateid="+id;
}

function changePID(pid){
    /*  var f = document.getElementById("QForm1");
    // f.uplevel.value=pid; 
    f.qcateid.value=pid;
    goQuery();*/
    location.href=cateList+"?qcateid=" + pid;
}

function changeCATE(cateid){
    location.href=listPage+"?qcateid=" + cateid;
}







function openWin(url){
    window.open(url);
}



//edit
function goBack(){
    transPageInfoForm('','qFrom1');
}

function check_sub(chk){
    var $clickobj = $(chk);
    if ($clickobj.attr('checked')){
        $clickobj.parent().parent().find('.dir').each(function(){
            $(this).attr('checked',true);
        });
    }else{
        $clickobj.parent().parent().find('.dir').each(function(){
            $(this).attr('checked',false);
        });
    }
}

function check_parent(chk){
    var $clickobj = $(chk);
    if ($clickobj.attr('checked')){
        $clickobj.parent().parent().find('.folder').eq(0).attr('checked',true);
    }else{
        var parent_obj = $clickobj.parent().parent().find('.folder').eq(0);
        var total_size = $clickobj.parent().parent().find('.dir:checked').length;
        if (total_size==0){
            parent_obj.attr('checked',false);
        }
    }
}


function runUnLock(){
    var selLen = $("input[name=sel\[\]]:checked").length;
    if (selLen==0){
      warning_msg("<font color='red'><b>請先選擇欲解鎖的資料!!</b></font>");
      return false;
    }else{
      hiConfirm('您確認要解鎖選取的資料!?', '確認對話', function(r) {
        if (r){
          var f = document.getElementById("QForm1");
          wait_msg("請稍等...");
          f.mod.value="unlock";
          f.submit();
        }else{
          return false;
        }
      });
    }
}


function editPageKeyUp2(){

  
} 

function setUpRadio(radios){
    var $radios = $('input:radio[name='+radios+']');
    $radios.filter('[value=3]').prop("checked", true);
//    var $radios = $('input:radio[name=upflag]');
//    $radios.filter('[value=3]').attr('checked', true);
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

 $.fn.maxNum = function() {
        var maxHeight = this.map(function( i, e ) {
            return $(e).html();
        }).get();
        return  Math.max.apply( this, maxHeight ) ;
    };