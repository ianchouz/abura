<?php
include 'dao.php';
$useimgbrowser = true;
$useckeditor = true;
include '../../header.php';
checkAuthority("communitysetting");
//初始化DAO

//讀取操作模式
$mod = pgParam("mod","");
if(isset($_POST["active"]) && $_POST["active"] =="run"){
    $dao->update_onepage();
    if ($dao->action_message=="true"){
        echo "<script>info_msg('操作成功!!');</script>";
    }else{
        echo "<script>info_msg('$dao->action_message')</script>";
    }
}
$dao->load_onepage();
?>
<form name="eForm1" id="eForm1"  enctype="multipart/form-data" method="post">
<input type="hidden" name="active" value="run"/>
<div class="x-panel" id="x-webedit">
    <div class="x-panel-header"><? echo $page_navigation ?></div>
    <div>
        <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
            <ul>
             <li><a href='#mtabs-02' style='font-size:12px;padding:5px 5px;'>Google</a></li>
              <li><a href='#mtabs-01' style='font-size:12px;padding:5px 5px;display: none;'>Facebook</a></li>
             
              
            </ul>
             <div id="mtabs-02">
               
                <div style="margin:10px;font-size:14px;"><a href="google_mapkey/index.html" target="_blank">申請步驟參考</a></div>
                <div class="x-panel-body">                           
                    <table class="x-table" style=" border-collapse: collapse;">
                        <tr class="x-tr1" >
                            <th class="x-th">MAP API KEY</th>
                            <td class="x-td">
                                <input type="text" name="mapkey" id="mapkey" value="<?=$dao->dbrow['mapkey']?>" style="width:200px;">
                            </td>
                        </tr>             
                    </table>
                </div>       
        </div>
            <div id="mtabs-01" style="display: none;">
               
                <div style="margin:10px;font-size:14px;"><a href="fb/index.html" target="_blank">申請步驟參考</a></div>
                <div class="x-panel-body">                           
                    <table class="x-table" style=" border-collapse: collapse;">
                        <tr class="x-tr1" >
                            <th class="x-th">應用程式編號</th>
                            <td class="x-td">
                                <input type="text" name="fbid" id="fbid" value="<?=$dao->dbrow['fbid']?>" style="width:200px;">
                            </td>
                        </tr>             
                        <tr class="x-tr1">
                            <th class="x-th">應用程式密鑰</th>
                            <td class="x-td">
                                <input type="text" name="fbpwd" id="fbpwd" value="<?=$dao->dbrow['fbpwd']?>" style="width:200px;">
                            </td>
                        </tr>
                        <tr class="x-tr1">
                            <th class="x-th">管理者UserID</th>
                            <td class="x-td">
                                <input type="text" name="fbadmin" id="fbadmin" value="<?=$dao->dbrow['fbadmin']?>" style="width:200px;"> 多位管理者以 半形 "," 分隔
                            </td>
                        </tr>
                    </table>
                </div>

            </div>  
            
    </div>
    <div class="x-panel-bbar">
      <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
        <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
      </div>
    </div>
  </div>
</form>
<?php include('../../footer.php');?>
<script type="text/javascript">
  $(document).ready(function(){
    $("#mtabs").tabs();
  });
  function goSubmit(){
    var errormessage = "";
    if (errormessage!=""){
      warning_msg("<font color='red'><b>請檢查以下錯誤:</b></font><br>" + errormessage);
      return false;
    }
    wait_msg("請稍等...");
    $('#eForm1').submit();
  }
</script>