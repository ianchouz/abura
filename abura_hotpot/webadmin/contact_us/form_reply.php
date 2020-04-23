<?php include_once("../include/config.php");
require '../include/checksession.php';
$id = pgParam("id","");
if (empty($id)){
    die("無法取得聯絡編號!!");
}

$sql = "SELECT * FROM ".$CFG->tbext."contact_us WHERE id=".$id;
$result = @sql_query($sql);
$item = @sql_fetch_array($result);

$tosubject= "[".$CFG->company_name."]".'聯絡我們';

$mailcontent = '';
$var->company = $CFG->company_name;
$xmlvo = new parseXML($item['xmlcontent']);
?>
<br>
<Button type="button" title="載入聯絡內容" class="button" onclick="getOrderContent();"><div class="btn_no">載入聯絡內容</div></Button>
<form id="replyForm" name="replyForm" method="POST">
  <input type="hidden" name="id" value="<?=$item["id"];?>"/>
  <div class="x-panel-header" >
    發送電子信件
  </div>
  <div id="replyarea">
  <div class="x-panel-body">
    <table class="x-table" style=" border-collapse: collapse;">
      <tr class="x-tr1">
        <th class="x-th"><em>*</em>收件者：</th>
        <td class="x-td"><input type="text" name="tomail" id="tomail" value="<?=$xmlvo->value("/content/mail")?>" size="50" maxLength="100"/></td>
      </tr>
      <tr class="x-tr1">
        <th class="x-th"><em>*</em>聯絡主旨：</th>
        <td class="x-td"><input type="text" name="tosubject" id="tosubject" value="<?=$tosubject?>" size="50" maxLength="100"/></td>
      </tr>
      <tr class="x-tr1">
        <th class="x-th">訊息：</th>
        <td class="x-td"><textarea name="tocontent" id="mailcontent"><?=$mailcontent?></textarea></td>
      </tr>
    </table>
    <div class="x-panel-bbar">
        <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
            <div class="btn_panel"><Button type="button" title="確認寄出" class="button" onclick="goReply();"><div class="btn_no">確認寄出</div></Button></div>
      </div>
    </div>
  </div>
  </div>
</form>
<script>

  function goReply(){
    var errormessage = "";
    if ($('#tomail').val()==""){
      errormessage +="[收件者]不可以空白<br/>";
    }
    if (!isEmail($('#tomail').val())){
      errormessage +="[收件者E-mail]格式不正確<br/>";
    }
    if ($('#tosubject').val()==""){
      errormessage +="[聯絡主旨]不可以空白<br/>";
    }

    if (editor_mailcontent.getData().Trim()==""){
      errormessage +="[回覆內容]不可以空白<br/>";
    }
    $('#mailcontent').val(editor_mailcontent.getData());
    if (errormessage!=""){
      warning_msg("<font color='red'><b>請檢查以下錯誤:</b><br>" + errormessage);
      return false;
    }
    wait_msg("請稍等...");
    $.post(
        "processreply.php",
        $("#replyForm").serialize(),
        function(data){
          var showmessage = "";
          if (data.success){
            reSend("作業成功!!");
          }else{
            warning_msg(data.message);
            return false;
          }
        },
        "json"
    );
  }


  function getOrderContent(){
    $.ajax({url: "form_list.php?m="+Math.random(),
      type: 'POST',
      data: {id:'<?=$id?>'},
      dataType: 'html',
      error: function(){alert("error!!")},
      success: function(data){
          editor_mailcontent.setData("<br>"+data);
      }
    });
  }
</script>