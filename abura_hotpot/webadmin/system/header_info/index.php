<?php
  include 'dao.php';
  include '../../header.php';
  checkAuthority("system");
  //初始化DAO
  $dao = new header_info();
  //讀取操作模式
  $mod = pgParam("mod","");
  if(isset($_POST["active"]) && $_POST["active"] =="run"){
    $dao->update();
    if ($dao->action_message=="true"){
      echo "<script>info_msg('操作成功!!');</script>";
    }else{
      echo "<script>info_msg('$dao->action_message')</script>";
    }
  }
  $dao->load();
?>
<form name="eForm1" id="eForm1" method="post">
  <input type="hidden" name="active" value="run"/>
  <div class="x-panel" id="x-webedit">
    <div class="x-panel-header">
      <? echo $page_navigation ?>
    </div>
    <div>
      <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
        <ul>
          <li><a href='#mtabs-01' style='font-size:12px;padding:5px 5px;'>共同設定</a></li>
          <li><a href='#mtabs-02' style='font-size:12px;padding:5px 5px;'>首頁</a></li>
          <?
            foreach ($dao->keyname as $key =>$value){
              $keynameshow = $dao->keynameshow[$value];
          ?>
          <li><a href='#mtabs-O<?=$key?>' style='font-size:12px;padding:5px 5px;'><?=$keynameshow?></a></li>
          <?
            }
          ?>
        </ul>

        <div id="mtabs-01">
          <div class="x-panel-body">
            <?php foreach($CFG->langs as $lkey=> $lval){ ?>
            <table class="x-table" style=" border-collapse: collapse;">

              <tr class="x-tr1">
                <th class="x-th" colspan="2" style="line-height:15px;background-color:#DEDEDE;text-align:left;padding:5px;"><?=$lval["name"]?> - 共同設定 (累加在每個頁面的資訊)</th>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">title</th>
                <td class="x-td"><input type="text" name="html_title_<?=$lkey?>" id="html_title_<?=$lkey?>" size="40" maxLength="100" value="<? echo $dao->drow["html_title_".$lkey]; ?>"></td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th">keywords</th>
                <td class="x-td"><textarea cols="80" rows="2" name="html_keywords_<?=$lkey?>" id="html_keywords_<?=$lkey?>" ><? echo $dao->drow["html_keywords_".$lkey]; ?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">description</th>
                <td class="x-td"><textarea cols="80" rows="2" name="html_description_<?=$lkey?>" id="html_description_<?=$lkey?>"><? echo $dao->drow["html_description_".$lkey]; ?></textarea></td>
              </tr>
              <? if ($_SESSION['authority']=='all'){?>
              <tr class="x-tr2" >
                <th class="x-th">head script</th>
                <td class="x-td">放置在 head 裡面的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="headscript_<?=$lkey?>" id="headscript_<?=$lkey?>"><?=$dao->drow["headscript_".$lkey]?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">body 結束前 script</th>
                <td class="x-td">放置在 body結尾前面(<?=htmlspecialchars('</body>')?>)的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="endbodyscript_<?=$lkey?>" id="endbodyscript_<?=$lkey?>"><?=$dao->drow["endbodyscript_".$lkey]?></textarea></td>
              </tr>
              <?}else{?>
              <tr class="x-tr2" style="display:none;">
                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="headscript_<?=$lkey?>" id="headscript_<?=$lkey?>"><?=$dao->drow["headscript_".$lkey]?></textarea></td>

                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="endbodyscript_<?=$lkey?>" id="endbodyscript_<?=$lkey?>"><?=$dao->drow["endbodyscript_".$lkey]?></textarea></td>
              </tr>

              <?}?>

            </table>
            <?php }?>
          </div>
        </div>

        <div id="mtabs-02">
          <div class="x-panel-body">

            <table class="x-table" style=" border-collapse: collapse;">
           <?php foreach($CFG->langs as $lkey=> $lval){ ?>
              <tr class="x-tr1">
                <th class="x-th" colspan="2" style="line-height:15px;background-color:#DEDEDE;text-align:left;padding:5px;"><?=$lval["name"]?> - 首頁</th>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">title</th>
                <td class="x-td"><input type="text" name="index_html_title_<?=$lkey?>" id="index_html_title_<?=$lkey?>" size="40" maxLength="100" value="<? echo $dao->drow["index_html_title_".$lkey]; ?>"></td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th">keywords</th>
                <td class="x-td"><textarea cols="80" rows="2" name="index_html_keywords_<?=$lkey?>" id="index_html_keywords_<?=$lkey?>" ><? echo $dao->drow["index_html_keywords_".$lkey]; ?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">description</th>
                <td class="x-td"><textarea cols="80" rows="2" name="index_html_description_<?=$lkey?>" id="index_html_description_<?=$lkey?>"><? echo $dao->drow["index_html_description_".$lkey]; ?></textarea></td>
              </tr>
              <? if ($_SESSION['authority']=='all'){?>
              <tr class="x-tr2">
                <th class="x-th">head script</th>
                <td class="x-td">放置在 head 裡面的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="index_headscript_<?=$lkey?>" id="index_headscript_<?=$lkey?>"><?=$dao->drow["index_headscript_".$lkey]?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">body 結束前 script</th>
                <td class="x-td">放置在 body結尾前面(<?=htmlspecialchars('</body>')?>)的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="index_endbodyscript_<?=$lkey?>" id="index_endbodyscript_<?=$lkey?>"><?=$dao->drow["index_endbodyscript_".$lkey]?></textarea></td>
              </tr>
              <?}else{?>
              <tr class="x-tr2" style="display:none;">
                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="index_headscript_<?=$lkey?>" id="index_headscript_<?=$lkey?>"><?=$dao->drow["index_headscript_".$lkey]?></textarea></td>

                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="index_endbodyscript_<?=$lkey?>" id="index_endbodyscript_<?=$lkey?>"><?=$dao->drow["index_endbodyscript_".$lkey]?></textarea></td>
              </tr> <?}?>
               <?php }?>
            </table>

          </div>
        </div>

        <?PHP
        foreach ($dao->keyname as $key =>$value){

        ?>
        <div id="mtabs-O<?=$key?>">
          <div class="x-panel-body">
            <table class="x-table" style=" border-collapse: collapse;">
            <?php foreach($CFG->langs as $lkey=> $lval){
             $keynameshow = $dao->keynameshow[$value];
          $tmparray = $dao->keyarray[$value."_".$lkey]; ?>
              <tr class="x-tr1">
                <th class="x-th" colspan="2" style="line-height:15px;background-color:#DEDEDE;text-align:left;padding:5px;"><?=$lval["name"]?> - <?echo $keynameshow?></th>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">title</th>
                <td class="x-td"><input type="text" name="<?echo $value?>html_title_<?=$lkey?>" id="<?echo $value?>html_title_<?=$lkey?>" size="40" maxLength="100" value="<? echo $tmparray['html_title']; ?>"></td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th">keywords</th>
                <td class="x-td"><textarea cols="80" rows="2" name="<?echo $value?>html_keywords_<?=$lkey?>" id="<?echo $value?>html_keywords_<?=$lkey?>" ><? echo $tmparray['html_keywords']; ?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">description</th>
                <td class="x-td"><textarea cols="80" rows="2" name="<?echo $value?>html_description_<?=$lkey?>" id="<?echo $value?>html_description_<?=$lkey?>"><? echo $tmparray['html_description']; ?></textarea></td>
              </tr>
              <?PHP if ($_SESSION['authority']=='all'){?>
              <tr class="x-tr2">
                <th class="x-th">head script</th>
                <td class="x-td">放置在 head 裡面的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="<?=$value?>headscript_<?=$lkey?>" id="<?=$value?>headscript_<?=$lkey?>"><?=$tmparray['headscript']; ?></textarea></td>
              </tr>
              <tr class="x-tr1">
                <th class="x-th">body 結束前 script</th>
                <td class="x-td">放置在 body結尾前面(<?=htmlspecialchars('</body>')?>)的語法,若不清楚,請勿隨意變更<br/><textarea cols="80" rows="5" name="<?=$value?>endbodyscript_<?=$lkey?>" id="<?=$value?>endbodyscript_<?=$lkey?>"><?=$tmparray['endbodyscript']; ?></textarea></td>
              </tr>
              <?PHP }else{?>
               <tr class="x-tr2" style="display:none;">
                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="<?=$value?>headscript" id="<?=$value?>headscript"><?=$tmparray['headscript']; ?></textarea></td>
                <th class="x-th"></th>
                <td class="x-td"><textarea cols="80" rows="5" name="<?=$value?>endbodyscript_<?=$lkey?>" id="<?=$value?>endbodyscript_<?=$lkey?>"><?=$tmparray['endbodyscript']; ?></textarea></td>
              </tr> <?PHP }?>
               <?PHP }?>
            </table>
          </div>
        </div>
        <?PHP
        }
        ?>
      </div>
    </div>

    <div class="x-panel-bbar">
      <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
        <div class="btn_panel"><Button type="button" title="存檔" class="button" onclick="goSubmit();"><div class="btn_save">存檔</div></Button></div>
      </div>
    </div>
  </div>
</form>
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
<?php
  include('../../footer.php');
?>
