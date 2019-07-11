<?php
  class pageTool{
  	//每頁筆數
    public $rowsperpage = 50;
    //總頁數
    public $totalpages = 0;
    //查詢結果筆數
    public $numrows = 0;
    //目前第幾頁(查詢頁數)
    public $currentpage = 1;
    //起始位置
    public $offset = 0;
    //查詢顯示筆數
    public $rrows = 0;

    function __construct() {
    	//從POST中取值
    	if (isset($_POST['rowsperpage']) && is_numeric($_POST['rowsperpage'])){
        $this->rowsperpage = (int) $_POST['rowsperpage'];
      }
    	if (isset($_POST['currentpage']) && is_numeric($_POST['currentpage'])){
        $this->currentpage = (int) $_POST['currentpage'];
      }
    }

    //傳入總筆數
    function excute($sql){
      //echo "<br>".$sql."<br>";
      $result = sql_query('select count(*) from ('.$sql.') as t1') or trigger_error("SQL", E_USER_ERROR);
      $r = sql_fetch_row($result);
      $this->numrows = $r[0];
      $this->totalpages = ceil($this->numrows / $this->rowsperpage);
      // 若過當前的頁數大於頁數總數
      if ($this->currentpage > $this->totalpages) {
      	// 把當前頁數設定為最後一頁
      	$this->currentpage = $this->totalpages;
      }
      // end if
      // 若果當前的頁數小於 1
      if ($this->currentpage < 1) {
      	// 把當前頁數設定為 1
      	$this->currentpage = 1;
      }
      // end if
      // 根據當前頁數計算名單的起始位置
      $this->offset = ($this->currentpage - 1) * $this->rowsperpage;

      $sql = $sql.' LIMIT '. $this->offset .','.$this->rowsperpage;
      //echo "<br>".$sql."<br>";
      $result = sql_query($sql) or trigger_error("SQL", E_USER_ERROR);
      $this->rrows = sql_num_rows($result);
      return $result;
    }
//if (!empty($_SERVER['QUERY_STRING'])) {
    function builePage(){

      //上一頁
    	$pnum = $this->currentpage-1;
      if ($pnum < 1) {
      	$pnum = 1;
      }
      //下一頁
    	$nnum = $this->currentpage+1;
      if ($nnum > $this->totalpages) {
      	$nnum = $this->totalpages;
      }

      //顯示X~X筆
      $ss = (($this->currentpage-1) * $this->rowsperpage) + 1;
      $ee = (($this->currentpage-1) * $this->rowsperpage) + $this->rrows;

      $pagestring = '<div class="x-toolbar x-small-editor x-toolbar-layout-ct">'
                   .'<div class="total" id="pagetotal">顯示'.$ss.'-'.$ee.'筆,共'.$this->numrows.'筆</div>'
                   .'<div class="pageview" id="pageview">'
                   .'<div class="frist" title="最前頁" onclick="document.getElementById(\'currentpage\').value=1;goQuery();"></div>'
                   .'<div class="prev" title="上一頁" onclick="document.getElementById(\'currentpage\').value='.$pnum.';goQuery();"></div>'
                   .'<span class="split">&nbsp;</span>'
                   .'<div class="page">第&nbsp;<input type="text" value="'.$this->currentpage.'" size="3" id="currentpage" name="currentpage" maxLength="4" onKeyUp="javascript:editPageKeyUp2()"/>&nbsp;頁,共&nbsp;'.$this->totalpages.'&nbsp;頁,每頁&nbsp;<input type="text" name="rowsperpage" id="rowsperpage" size="3" maxLength="4" value="'.$this->rowsperpage.'"/>&nbsp;筆</div>'
                   .'<span class="split">&nbsp;</span>'
                   .'<div class="next" title="下一頁" onclick="document.getElementById(\'currentpage\').value='.$nnum.';goQuery();"></div>'
                   .'<div class="final" title="最後頁" onclick="document.getElementById(\'currentpage\').value='.$this->totalpages.';goQuery();"></div>'
                   .'<span class="split">&nbsp;</span>'
                   .'<div class="refresh" title="查詢" onclick="goQuery();"></div>'
                   .'</div></div>';
       return $pagestring;
    }
  }
  $pagetool = new pageTool();
?>