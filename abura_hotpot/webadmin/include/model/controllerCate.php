<?php
class controller {
    public $table_cate;

    function __construct($table_cate) {
        if($table_cate)
            $this->table_cate = $table_cate;
    }


    function controller($mod) {
        if(!$mod)
            return;
        switch($mod) {
            case 'del':
                /*刪除*/
                $this->delrow();
                break;
            case 'runUpColumn':
                /*更新某個欄位*/
                $this->runUpColumn();
                break;
            case "update":
                /*更新排序*/
                $this->updaterowdata();
                break;
            default:
                if(method_exists($this, "SubController")) {
                    $this->SubController($mod);
                } else {
                    return;
                }
                break;
        }

        if($this->action_message != null) {
            echo "<div id='__action_message' class=\"action_message\">$this->action_message</div>";
            echo "<script>setTimeout(\"$('#__action_message').fadeOut();\",2000);</script>";
        }
    }

    /*更新欄位*/
    function runUpColumn() {
        global $CFG;
        $cc = 0;
        $modData = $_POST["modData"];
        list($column, $column_val) = explode("|", $modData);
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "update " . $this->table_cate . " set $column='$column_val' where id in ($idvals)" . $this->condition;
            //echo $sql;
            $result = sql_query($sql) or die("Query failed : " . sql_error());
            $cc = sql_affected_rows();
            $this->action_message = "總共修改: $cc 筆！";
            $this->action_error_debug = $sql . "<br>" . sql_error();
        }
    }
    function activerow() {
        global $CFG;
        //先取得要啟用的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "update " . $this->table_cate . " set inuse=true where id in ($idvals)" . $this->condition;
            $result = sql_query($sql) or die("Query failed : " . sql_error());

            $this->action_message = "總共啟用: $cc 筆！";
        }
    }
    function stoprow() {
        global $CFG;
        //先取得要啟用的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            $sql = "update " . $this->table_cate . " set inuse=false where id in ($idvals)" . $this->condition;
            $result = sql_query($sql) or die("Query failed : " . sql_error());

            $this->action_message = "總共停用: $cc 筆！";
        }
    }

    /*更新排序*/
    function updaterowdata() {
        global $CFG;
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $tmpseq = pgParam("seq_$value", null);
                if($tmpseq == null) {
                    $tmpseq = "";
                }
                $sql = "update " . $this->table_cate . " set seq='$tmpseq' where id=$value" . $this->condition;
                $result = sql_query($sql);
                if($result) {
                    $cc++;
                }
            }
            $this->action_message = "總共更新: $cc 筆！";
        }
    }

    /*刪除*/
    function delrow() {
        global $CFG;
        //先取得要刪除的編號
        $cc = 0;
        $option = $_POST['sel'];
        $idvals = "";
        if(isset($option)) {
            foreach((array) $option as $key => $value) {
                $idvals .= (($cc != 0) ? "," : "") . "'$value'";
                $cc++;
            }
            //刪除
            $sql = "delete from " . $this->table_cate . " where id in ($idvals)" . $this->condition;
            $result = sql_query($sql);
            $sql = "delete from " . $this->table . " where cateid in ($idvals)";
            $result = sql_query($sql);

            if($result) {
                $cc = sql_affected_rows();
                $this->action_message = "總共刪除: $cc 筆！";
            } else {
                $this->action_message = "總共刪除: 0 筆！";
                $this->action_error_debug = $sql . "<br>" . sql_error();
            }
        }
    }

    /*取得最大的排序編號*/
    function getMexSeq() {
        global $CFG;
        $strSQLQ = "select max(seq) as seq from " . $this->table_cate . " where 1 " . $this->condition;
        $pid = $this->dbrow["pid"];
        if(!empty($pid)) {
            $strSQLQ .= " AND pid=" . $pid;
        } else {
            $strSQLQ .= " AND pid=-1";
        }
        $seq = sql2var($strSQLQ);
        $seq = formatNUM($seq, 1, 5);
        return $seq;
    }

	//清理暫存相簿
	function tmpPhotoDelete() {
		global $baseRoot;

		$dirs = glob($baseRoot . '*', GLOB_MARK);
		foreach ($dirs as $dir) {
			if(strpos($dir,'tmp_')!==false && strpos($dir,$_REQUEST["mainid"])===false && is_dir($dir)) {
				$ftimepos = strpos($dir,'tmp_')+4;
				$ftime = substr($dir, $ftimepos, -1);
				$x = (time()-$ftime)/60;
				if($x<5) continue;
				$this->delTree($dir);
			}
		}
	}
	function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}

    /*導覽*/
    function subNavigation($tmpid) {
        global $CFG;
        if((int) $tmpid > 0) {
            $cidx = 0;
            while(true) {
                $tmpData = $this->_getPIDData($tmpid);
                if($tmpData != null) {
                  //  $catename = $tmpData["catename"];

                    // $xmlvo    = new parseXML($tmpData['catename']);
                    // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
                    $catename = $tmpData['catename'];

                    if($cidx == 0) {
                        $subNavigation = "<div class='div-nonbtn'> " . $catename . "</div>";
                    } else {
                        if($tmpData['leaf'] === 1) {
                            $subNavigation = "<div class='div-button' onclick='changeCATE(" . $tmpData["id"] . ")'> " . $catename . "</div><div class='div-expand'>&nbsp;</div> " . $subNavigation;
                        } else {
                            $subNavigation = "<div class='div-button' onclick='changePID(" . $tmpData["id"] . ")'> " . $catename . "</div><div class='div-expand'>&nbsp;</div> " . $subNavigation;
                        }
                    }
                    $tmpid = $tmpData['pid'];
                    if($tmpid == -1) {
                        break;
                    }
                    $cidx++;
                } else {
                    break;
                }
            }
            $subNavigation = "<div class='div-button' onclick='changePID(-1)'>根節點</div><div class='div-expand'>&nbsp;</div> " . $subNavigation;
        } else {
            if(isset($showroot) && $showroot) {
                $subNavigation = "<div class='div-button' onclick='changePID(-1)'>根節點</div>";
            } else {
                $subNavigation = "<div class='div-nonbtn'>根節點</div>";
            }
        }
        return $subNavigation;
    }
    function _getPIDData($pid) {
        global $CFG;
        if($pid != "-1") {
            $subsql = "select catename,pid,id,leaf from " . $this->table_cate . " where id=" . $pid . $this->condition;
            $subquery = @sql_query($subsql);
            if($subquery) {
                return @sql_fetch_array($subquery);
            } else {
                return null;
            }
        }
        return null;
    }


    function deal_upFile($upname = "filename", $limitedext = "", $size_mb = 10) {
        global $CFG;
        $dirpath = $CFG->root_user_file . $this->cfg["path"];
        __chkdir($dirpath);
        $upflag = pgParam("upflag" . $upname, ""); //檔案上傳註記
        $filename = pgParam($upname, ""); //舊的檔名

        //上傳檔案
        switch($upflag) {
            case '3': //新檔案上傳
                $up1 = new upFile();
                if(!empty($limitedext)) {
                    $upl->limitedext = $limitedext;
                } else {
                    $up1->limitedext = array(
                        '.pdf',
                        '.doc',
                        '.docx',
                        '.zip',
                        '.rar',
                        '.xls',
                        '.xlsx',
                        '.jpeg',
                        '.jpg'
                    );
                }
                $up1->dirpath = $dirpath;
                $up1->size_bytes = 1024 * 1024 * $size_mb;
                $up1->check('newfile' . $upname);
                if($up1->_message != "") {
                    $this->action_message = $up1->_message;
                    return false;
                } else {
                    if($up1->write()) {
                        //判斷新舊檔案是否相同
                        if($filename != "" && $filename != $up1->file_name && $this->checkFILE($filename) <= 0) {
                            $tt = $dirpath . $this->filename;
                            //@unlink($tt);
                        }
                        $filename = $up1->file_name;
                    } else {
                        $this->action_message = $up1->_message;
                    }
                }
                break;
            case '2': //使用原本檔案
                break;
            default: //不使用檔案
                if($filename != "" && $this->checkFILE($filename) <= 0) {
                    $tt = $dirpath . $this->filename;
                    //@unlink($tt);
                }
                $filename = "";
                break;
        }
        return $filename;
    }
    function checkFILE($filename) {
        global $CFG;
        $sql = "select count(1) as cc from " . $this->table_cate . " where imagexml like '%" . $filename . "%'" . $this->condition;
        $tmp = sql2var($sql);
        return $tmp["cc"];
    }
    function checkIMG($imgname) {
        global $CFG;
        $sql = "select count(1) as cc from " . $this->table_cate . " where imagexml like '%" . $imgname . "%'" . $this->condition;
        $tmp = sql2var($sql);
        return $tmp["cc"];
    }

    function parseXML($xmlstring) {
        global $CFG;
        if($xmlstring != null) {

            $xmlvo = new parseXML($xmlstring);
            foreach($this->_xmls as $key => $tmp) {
                if($tmp['type']=='editor'){
                    $this->dbrow[$key] = db2html($xmlvo->value('/content/'.$key));
                }else{
                    $this->dbrow[$key] = $xmlvo->value('/content/'.$key);
                }
            }
        }
    }


    function toXML() {
        global $CFG;
        $xmlstring = "<content>";

        foreach($this->_xmls as $key => $tmp) {
            $input_val = pgParam("$key","");
            if($tmp['type']=='editor'){
                $xmlstring .='<'.$key.'>'.turnCDDATA(html2db($input_val)).'</'.$key.'>';
            }else if($tmp['type']=='img'){
                $img = $input_val;
                // $dealImg = new dealUploadImg($CFG->root_user);
                // $dealImg->docPath = $tmp['set']["path"];
                // $img = $dealImg->doImg($tmp['set']["w"], $tmp['set']["h"], $key, "");

                //未上圖,則綁定主圖
                // $this->_bindImg[$key]=$img;
                // if( isset($tmp['bind']) && $tmp['bind']!="" &&  $this->_bindImg[$tmp['bind']]!="" && $key!="" ){
                //     //有大圖,處理小圖
                //     if ($img ==""){//沒有選擇小圖,直接以大圖進行縮圖
                //         $imagename = $this->_bindImg[$tmp['bind']];
                //         $newfilename = getNewFileName($imagename,"_autos");
                //         quickReSizeIMG($tmp['set']["w"],$tmp['set']["h"],$CFG->root_user,$tmp['set']["path"].$imagename,$tmp['set']["path"].$newfilename);
                //         $img = $newfilename;
                //     }
                // }
                $xmlstring .='<'.$key.'>'.turnCDDATA($img).'</'.$key.'>';
            }else if($tmp['type']=='file'){
                $file = $this->deal_upFile($key);
                $xmlstring .='<'.$key.'>'.turnCDDATA($file).'</'.$key.'>';
            }else{
                $xmlstring .='<'.$key.'>'.turnCDDATA($input_val).'</'.$key.'>';
            }
        }
        $xmlstring .= '</content>';
        return $xmlstring;
    }
     function toXML_v2($arr=array(),$type="") {
        global $CFG;
        $xmlstring = "<content>";
        foreach($arr as $key => $tmp) {
            $input_val = pgParam("$key","");
            //echo $key.":".$input_val."<br>";
            if($tmp['type']=='editor'){
                $xmlstring .='<'.$key.'>'.turnCDDATA(html2db($input_val)).'</'.$key.'>';
            }else if($tmp['type']=='img'){
                $dealImg = new dealUploadImg($CFG->root_user);
                $dealImg->docPath = $tmp['set']["path"];
                $img = $dealImg->doImg($tmp['set']["w"], $tmp['set']["h"], $key, "");

                //未上圖,則綁定主圖
                $this->_bindImg[$key]=$img;
                if( isset($tmp['bind']) && $tmp['bind']!="" &&  $this->_bindImg[$tmp['bind']]!="" && $key!="" ){
                    //有大圖,處理小圖
                    if ($img ==""){//沒有選擇小圖,直接以大圖進行縮圖
                        $imagename = $this->_bindImg[$tmp['bind']];
                        $newfilename = getNewFileName($imagename,"_autos");
                        quickReSizeIMG($tmp['set']["w"],$tmp['set']["h"],$CFG->root_user,$tmp['set']["path"].$imagename,$tmp['set']["path"].$newfilename);
                        $img = $newfilename;
                    }
                }
                $xmlstring .='<'.$key.'>'.turnCDDATA($img).'</'.$key.'>';
            }else if($tmp['type']=='file'){
                $file = $this->deal_upFile($key);
                $xmlstring .='<'.$key.'>'.turnCDDATA($file).'</'.$key.'>';
            }else{
                $xmlstring .='<'.$key.'>'.turnCDDATA($input_val).'</'.$key.'>';
            }
        }
        $xmlstring .= '</content>';
       // echo "#".$xmlstring;
        return $xmlstring;
    }
    /*建立一般輸入資料*/
    function toDATA() {
         global $CFG;
        //建立資料陣列 (公用)
        $datas = array();
        foreach($this->_cols as $key => $tmp) {
            $this->$key = pgParam("$key",$tmp['d4']);

            if($tmp['type']=='editor' || $tmp['type']=='editor_simple'){
                $datas[$key] = pSQLStr(html2db($this->$key));
            }else if($tmp['type']=='xlang_editor_simple' || $tmp['type']=='xlang_editor' || $tmp['type']=='xlang'){
                $arr=array();
                $type="editor";
                if($tmp['type']=='xlang') $type="";
                $arr = $this->mergeLangArr($key);
                //var_dump($arr);
                $datas[$key]=pSQLStr($this->toXML_v2($arr),$type);
            }else{
                //資料型態
                if($tmp['type'] == 'int') {
                    $datas[$key] = pSQLInt($this->$key);
                } elseif($tmp['type'] == 'bool') {
                    $datas[$key] = pSQLBoolean($this->$key);
                } else {
                    $datas[$key] = pSQLStr($this->$key);
                }
            }
        }

        //建立XML資料 (公用)

        $datas["imagexml"] = pSQLStr($this->toXML());

		//建立資料 (多語言)
        foreach($this->_Lcols as $fkey=>$fval){
            $xmlstring = '<content>';
            foreach($CFG->langs as $lkey=>$lval){
                $name=$fkey."_".$lkey;
                $this->dbrow[$name] = pgParam($name,null);
                if($fval["type"]=='editor' || $fval['type']=='editor_simple'){
                    $xmlstring .='<'.$name.'><![CDATA['.html2db($this->dbrow[$name]).']]></'.$name.'>';
                } else{
                    $xmlstring .='<'.$name.'><![CDATA['.$this->dbrow[$name].']]></'.$name.'>';
                }
            }
            $xmlstring .='</content>';
            $datas[$fkey] =pSQLStr($xmlstring);
        }

		//自動過濾不存在的欄位
		$colums = sql2map("describe ".$this->table_cate);
		$result=array_intersect_key($datas,$colums);

        return $result;
    }

    function dbrowDefault($imgname) {
        if(is_array($this->_cols)){
            foreach($this->_cols as $key=>$set ) {
                $this->dbrow[$key] = $set['d4'];
            }
        }
    }

    /*生成HTML*/
    ##XML欄位名稱=欄位性質(img,file,editor,editor_simple,textarea,link,checkbox,text_date,空白表純文字)
    ##data/cate/stands/one一致
    function html($col,$display='') {
        //拆出多個欄位
        list($col, $co2) = explode(',',$col);
        $tmp = $this->_cols[$col];
        if(empty($tmp)) $tmp = $this->_xmls[$col];
        //來自DAO: editor/img
        if($tmp['type']!='') $display = $tmp['type'];
        $style = !empty($tmp['style']) ? $tmp['style'] : false;

        ob_start();
        if($display=='img'){
            /*buildImageBroser("欄位名稱",  "檔案路徑",  "寬高等設定值",  "檔案")*/
            $file1 = new buildImageBroser($col,  $tmp['set']["path"],  $tmp['set'],  $this->dbrow[$col], $this);
        }else if($display=='file'){
            /*buildImageBroser("欄位名稱",  "檔案路徑",  "寬高等設定值",  "檔案")*/
            $file1 = buildFilehtml($col);
        }else if($display=='editor'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='editor_simple'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor simple"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='textarea'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="<?=$style?$style:'textarea_full'?>"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='link'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" class="input_full" maxLength="500" value="<?=$this->dbrow[$col]?>">(ex:http://....)<br><input type="checkbox" name="<?=$co2?>" id="<?=$co2?>" value="true" <?=($this->dbrow[$co2])?"checked":""?>> 另開視窗<?php
        }else if($display=='checkbox'){
            ?><input type="checkbox" name="<?=$col?>" id="<?=$col?>" value="Y" <?=$this->dbrow[$col]=="Y"?"checked":""?>/><?php
        }else if($display=='text_date'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" size="11" maxLength="10" value="<?=$this->dbrow[$col]?>" class="dateset"><?php
        }else if($display=='color'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" size="11" maxLength="10" value="<?=$this->dbrow[$col]?>" class="colorpicker"><?php
        }else if($display=='xlang_textarea'){
            ?><textarea name="<?=$col?>" id="<?=$col?>"  <?=($tmp['style']==""?'class="textarea_full"':$tmp['style'])?> ><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='xlang_editor'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor"><?=$this->dbrow[$col]?></textarea><?php
        }else if($display=='xlang_editor_simple'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor simple"><?=$this->dbrow[$col]?></textarea><?php
        }else{
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" class="<?=$style?$style:'input_full'?>" value="<?=htmlentities($this->dbrow[$col], ENT_QUOTES, "UTF-8");?>"><?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

        function html_text($col)
    {
        $sql = "select * from tw_product_cate";
        // var_dump($sql);
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        $icon = new parseXML($row['icon_data_xml']);
        // var_dump($icon);
ob_start();
$_col = "/content/".$col;
$value = $icon->value($_col);
$html = "<input type='text' name=".$col." id=".$col." size='11' maxLength='10' value=".$value.">";
ob_get_contents();
ob_end_clean();
        echo $html;

    }


    function html2($col,$display='') {
        //拆出多個欄位
        list($col, $co2) = explode(',',$col);
        $tmp = $this->_cols[$col];
        if(empty($tmp)) $tmp = $this->_xmls[$col];
        //來自DAO: editor/img
        if($tmp['type']!='') $display = $tmp['type'];

        ob_start();
        if($display=='img'){
            /*buildImageBroser("欄位名稱",  "檔案路徑",  "寬高等設定值",  "檔案")*/
            $file1 = new buildImageBroser($col,  $tmp['set']["path"],  $tmp['set'],  $this->dbrow[$col]);
        }else if($display=='file'){
            /*buildImageBroser("欄位名稱",  "檔案路徑",  "寬高等設定值",  "檔案")*/
            $file1 = buildFilehtml($col);
        }else if($display=='editor'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='editor_simple'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor simple"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='textarea'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="textarea_full"><?=db2html($this->dbrow[$col])?></textarea><?php
        }else if($display=='link'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" class="input_full" maxLength="500" value="<?=$this->dbrow[$col]?>">(ex:http://....)<br><input type="checkbox" name="<?=$co2?>" id="<?=$co2?>" value="true" <?=($this->dbrow[$co2])?"checked":""?>> 另開視窗<?php
        }else if($display=='checkbox'){
            ?><input type="checkbox" name="<?=$col?>" id="<?=$col?>" value="Y" <?=$this->dbrow[$col]=="Y"?"checked":""?>/><?php
        }else if($display=='text_date'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" size="11" maxLength="10" value="<?=$this->dbrow[$col]?>" class="dateset"><?php
        }else if($display=='color'){
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" size="11" maxLength="10" value="<?=$this->dbrow[$col]?>" class="colorpicker"><?php
        }else if($display=='xlang_editor'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor"><?=$this->dbrow[$col]?></textarea><?php
        }else if($display=='xlang_editor_simple'){
            ?><textarea name="<?=$col?>" id="<?=$col?>" class="callckeditor simple"><?=$this->dbrow[$col]?></textarea><?php
        }else{
            ?><input type="text" name="<?=$col?>" id="<?=$col?>" class="input_full" value="<?=htmlentities($this->dbrow[$col], ENT_QUOTES, "UTF-8");?>"><?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /*讀取分類清單*/
    function loadCategoryList( ) {
        global $CFG;
        $layer=1;
        unset($this->categoryList);
        $this->categoryList[] = array(
            'id' => '-1',
            'name' => '分類目錄',
            'layer' => '0',
            'fullname' => '分類目錄'
        );
        $slq = "select * from " . $this->table_cate . " where leaf=false and pid=-1 " . $this->condition . " order by inuse desc,seq";
        $query = sql_query($slq);
        while($row = @sql_fetch_array($query)) {


            // $xmlvo    = new parseXML($row['catename']);
            // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
            $catename = $row['catename'];
            $this->categoryList[] = array(
                'id' => $row['id'],
                 'layer' => $layer,
                'name' => "└ " . $catename,
                'fullname' => $catename
            );
            $this->loadSubCategoryList($row['id'], "", $catename);
        }
        return true;
    }
    /*與前同一組*/
    function loadSubCategoryList($pid, $append, $parentname = "" ,$layer=1) {
        global $CFG;
        $layer++;
        $append .= "　&nbsp;";
        $slq = "select * from " . $this->table_cate . " where leaf=false and pid=$pid " . $this->condition . " order by inuse desc,seq";
        $query = @sql_query($slq);
        while($row = @sql_fetch_array($query)) {
             $xmlvo    = new parseXML($row['catename']);
            $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);

            $leaf = $row["leaf"];
            $this->categoryList[] = array(
                'id' => $row['id'],
                'name' => $append . "└ " . $catename,
                'layer' => $layer,
                'fullname' => $parentname . "=>" . $catename
            );
            if($leaf == "0") {
                $this->loadSubCategoryList($row['id'], $append, $parentname . "=>" .  $catename,$layer);
            }
        }
        return true;
    }

    /*取得第幾層*/
    function getTreeDeep($pid) {
        global $CFG;
        unset($this->tree_list);
        $slq = "select * from " . $this->table_cate . " where leaf=false and pid=-1 " . $this->condition . " order by inuse desc,seq";
        $query = sql_query($slq);
        while($row = @sql_fetch_array($query)) {
            $deep = 1;
            $this->tree_list[$row['id']] = $deep;
            $this->treeLoop($row['id'], $deep);
        }
        return $this->deep_now = $this->tree_list[$pid]+1;
    }
    function treeLoop($pid, $deep) {
        global $CFG;
        $deep++;
        $slq = "select * from " . $this->table_cate . " where leaf=false and pid=$pid " . $this->condition . " order by inuse desc,seq";
        $query = @sql_query($slq);
        while($row = @sql_fetch_array($query)) {
            $this->tree_list[$row['id']] = $deep;
            if($leaf == "0") {
                $this->treeLoop($row['id'], $deep);
            }
        }
    }

    /*下層分類筆數*/
    function countSubCategory($pid) {
        global $CFG;
        $sql = "select count(*) as cc from " . $this->table_cate . " where pid =" . $pid . " " . $this->condition;
        $var = sql2var($sql);
        return $var;
    }

    /*下層資料筆數*/
    function countSub($pid) {
        global $CFG;
        $sql = "select count(*) as cc from " . $this->table . " where cateId = $pid";
        $var = sql2var($sql);
        return $var;
    }

    //解xml data 方法2:指定欄位
    function parseXML_v2($xmlstring,$arr=array()){

        global $CFG;
        if($xmlstring!= null){
            $xmlvo = new parseXML($xmlstring);
            $arr=$xmlvo->dataList($arr);
            foreach($arr as $key => $val){
                $this->dbrow[$key] = $val;
            }
        }
    }

    //讀取單筆資料
    //讀取單筆資料
    function load($id = "") {
        global $CFG;
        if(empty($id)) {
            $id = pgParam("id", null);
        }
        if(empty($id)) {
            return false;
        }
        $strSQLQ = "select * from " . $this->table_cate . " where id='" . sql_real_escape_string($id) . "'" . $this->condition;

        $query = sql_query($strSQLQ);
        $this->dbrow = sql_fetch_array($query);
        //讀取XML資料

        if(isset($this->dbrow["imagexml"])) {
            $this->parseXML($this->dbrow["imagexml"]);
        }



        foreach($this->_cols as $key => $tmp) {

            if($tmp['type']=='xlang_editor_simple' || $tmp['type']=='xlang_editor'){
                $arr = $this->mergeLangArr($key);
                $this->parseXML_v2($this->dbrow[$key],$arr);
            }else if($tmp['type']=='xlang'){
                $arr = $this->mergeLangArr($key);
                $this->parseXML_v2($this->dbrow[$key],$arr);
            }else if($tmp['type']=='xml'){
                //$this->parseXML_v2($this->dbrow["html_title"],array("html_title_tw"));
            }
        }



        return true;
    }


    function mergeLangArr($key=""){
        $arr=array();
        global $CFG;
        if($key=="")return;
        foreach($CFG->langs as $fkey=>$fval){
            $arr[$key."_".$fkey]=true;
        }
        return $arr;
    }

    //取得表單輸入的資料
    function loadForm() {}
}


?>
