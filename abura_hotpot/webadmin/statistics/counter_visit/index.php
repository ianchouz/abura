<?php
include 'dao.php';
include '../../header.php';
checkAuthority("statistics");
$qparams = new QueryParames();
//
$sql = "select min(yy) as yy from " . $CFG->tbext . "counter_visit";
$query = sql_query($sql);
$row = sql_fetch_array($query);
$nowyear = date('Y');
$nowm = date('m');
$nowd = date('d');
if($row) {
    $miny = (int) $row['yy'];
} else {
    $miny = $nowyear;
}
?>
<? if ($dao->action_message != null){ echo "$dao->action_message";}?>
  <div class="x-panel">
    <div class="x-panel-header">
      <div class="page_navigation"><?=$page_navigation?></div>
    </div>
  </div>
  <div class="x-panel-body" style="border:0px;">
    <div class="clearfloat"></div>
    <div id="mtabs" style="height:auto;padding:0px;margin:0px;border:0px;">
      <ul>
        <li><a href="#mtabs-1">網站訪客</a></li>
        <li><a href="#mtabs-2">流量導引</a></li>
      </ul>
      <div id="mtabs-1" style="text-align:center">
        <form name="visitform" id="visitform" action="statistic.php" method="post" target="_blank">
          <input type="hidden" name="stype" value="visit"/>
          <div class="x-panel" id="x-webedit">
            <div class="x-panel-body">
            <table class="x-table" style="border-collapse: collapse;">
              <tr class="x-tr1">
                <th class="x-th"><em>*</em>報表類型</th>
                <td class="x-td" style="text-align:left;padding:5px;"><input type="radio" name="type" id="visitform_type_m" value="m" checked onclick="changetype(this,'visitform')"> 月統計 <input type="radio" name="type" value="d" id="visitform_type_d" onclick="changetype(this,'visitform')"> 日統計</td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th"><em>*</em>統計日期</th>
                <td class="x-td" style="text-align:left;padding:5px;">
                  <select name="yy" id="visitform_yy" style="width:60px;"><?for($x=$miny;$x<=$nowyear;$x++){
                        echo "<option value='$x' selected>$x</option>";
                      }?></select> 年
                  <span id="visitform_month" style="visibility: hidden;">
                  <select name="mm"  id="visitform_mm" style="width:60px;"><?for($x=1;$x<=12;$x++){
                       $val = ($x<10?"0":"").$x;
                       if ($nowm==$val){
                         $sel = "selected";
                       }else{
                         $sel="";
                       }
                        echo "<option value='$val' $sel>$val</option>";
                      }?></select> 月
                  </span>
                </td>
              </tr>
            </table>
            </div>
            <div class="x-panel-bbar">
              <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                <div class="btn_panel"><Button type="button" title="統計" class="button" onclick="goSubmit('visitform');"><div class="btn_query">統計</div></Button></div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div id="mtabs-2">
        <form name="fromform" id="fromform" action="statistic.php" method="post" target="_blank">
          <input type="hidden" name="stype" value="from"/>
          <div class="x-panel" id="x-webedit">
            <div class="x-panel-body">
            <table class="x-table" style="border-collapse: collapse;">
              <tr class="x-tr1">
                <th class="x-th"><em>*</em>報表類型</th>
                <td class="x-td" style="text-align:left;padding:5px;"><input type="radio" name="type" value="m" checked onclick="changetype(this,'fromform')"> 月統計 <input type="radio" name="type" value="d" onclick="changetype(this,'fromform')"> 日統計</td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th"><em>*</em>排序方式</th>
                <td class="x-td" style="text-align:left;padding:5px;">
                  依 <select name="orderbytype"><option value="frequency">次數</option><option value="url">來源網站</option></select>
                  <input type="radio" name="orderby" value="" checked/> 由小到大 <input type="radio" name="orderby" value="desc" /> 由大到小
                </td>
              </tr>
              <tr class="x-tr2">
                <th class="x-th"><em>*</em>統計日期</th>
                <td class="x-td" style="text-align:left;padding:5px;">
                  <select name="yy" id="fromform_yy" style="width:60px;" onchange="changeDate()"><?for($x=$miny;$x<=$nowyear;$x++){
                        echo "<option value='$x' selected>$x</option>";
                      }?></select> 年
                  <select name="mm" id="fromform_mm" style="width:60px;" onchange="changeDate()"><?for($x=1;$x<=12;$x++){
                       $val = ($x<10?"0":"").$x;
                       if ($nowm==$val){
                         $sel = "selected";
                       }else{
                         $sel="";
                       }
                        echo "<option value='$val' $sel>$val</option>";
                      }?></select> 月
                  <span id="fromform_day" style="POSITION: absolute; visibility: hidden">
                    <select name="dd"  id="fromform_dd" style="width:60px;"></select> 日
                  </span>
                </td>
              </tr>
            </table>
            </div>
            <div class="x-panel-bbar">
              <div class="x-toolbar x-small-editor x-toolbar-layout-ct">
                <div class="btn_panel"><Button type="button" title="統計" class="button" onclick="goSubmit('fromform');"><div class="btn_query">統計</div></Button></div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php include('../../footer.php');?>
<SCRIPT type="text/javascript">

  $(function() {
    $("#mtabs").tabs();
  });
  function changetype(obj,idkey){
    if (idkey=="visitform"){
      mm = document.getElementById(idkey+'_month');
      if (obj.value=="m"){
        mm.style.visibility="hidden";
      }else if (obj.value=="d"){
        mm.style.visibility="visible";
      }
    }else{
      dd = document.getElementById(idkey+'_day');
      if (obj.value=="m"){
        dd.style.visibility="hidden";
      }else if (obj.value=="d"){
        dd.style.visibility="visible";
      }
      changeDate();
    }
  }
  var nowm = "<?=$nowm?>";
  var nowd = "<?=$nowd?>";
  //檢查報表的日期
  MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  function changeDate(){
    var yy = document.getElementById('fromform_yy');
    var mm = document.getElementById('fromform_mm');
    var dd = document.getElementById('fromform_dd');
    var MMvalue = mm.value;
    var n = MonHead[MMvalue - 1];
    var str= yy.value;
    if (MMvalue ==2 && IsPinYear(str)){
      n++;
    }
    dd.options.length=0;
    var selidx = 0;
    for (var i=1; i<(n+1); i++){
      vv = i<10?"0"+i:i+"";
      if (MMvalue==nowm && nowd==vv){
        selidx = i-1;
      }
      dd.options.add(new Option(vv,vv));
    }
    dd.options[selidx].selected = true;
  }
  function IsPinYear(year)//判斷是否閏平年
  { return(0 == year%4 && (year%100 !=0 || year%400 == 0));}
  var openwin = null;
  function goSubmit(idkey){
    openwin = window.open("about:blank", "statisticswin","height=500, width=850,toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no");
    var formobj = document.getElementById(idkey);
    formobj.target="statisticswin";
    formobj.submit();
    openwin.focus();
  }
</SCRIPT>