<?php
class controller {
    public $table;
    function __construct($table) {
        if($table)
            $this->table = $table;
    }

    function controller($mod) {
        if(!$mod) return;
        switch($mod){
            case 'del': ///*刪除*/
                $this->delrow();
                break;
            case 'runUpColumn': ///*更新某個欄位*/
                $this->runUpColumn();
                break;
            case "update": ///*更新排序*/
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

    /*列表頁方法*/
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
            $sql = "update " . $this->table . " set $column='$column_val' where id in ($idvals)" . $this->condition;
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
            $sql = "update " . $this->table . " set inuse=true where id in ($idvals)" . $this->condition;
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
            $sql = "update " . $this->table . " set inuse=false where id in ($idvals)" . $this->condition;
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
                $sql = "update " . $this->table . " set seq='$tmpseq' where id=$value" . $this->condition;
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
            $sql = "delete from " . $this->table . " where id in ($idvals)" . $this->condition;
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

    /*編輯頁方法*/
    /*取得最大的排序編號*/
    function getMexSeq() {
        global $CFG;
        $strSQLQ = "select max(seq) as seq from " . $this->table . " where 1 " . $this->condition;
        if(!empty($this->dbrow["cateId"])) {
            $strSQLQ .= " AND cateId=" . $this->dbrow["cateId"];
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
        if(empty($GLOBALS['useCate'])) return false;
        if((int) $tmpid > 0) {
            $cidx = 0;
            while(true) {
                $tmpData = $this->_getPIDData($tmpid);
                if($tmpData != null) {

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
            $subsql = "select catename,pid,id,leaf from " . $this->table_cate . " where id=" . $pid . $this->condition_cate;
            $subquery = @sql_query($subsql);
            if($subquery) {
                return @sql_fetch_array($subquery);
            } else {
                return null;
            }
        }
        return null;
    }

    /*建立一般輸入資料*/
    function toDATA(){
        global $CFG;
        //建立資料陣列 (公用)
        $datas = array();
        foreach($this->_cols as $key => $tmp) {
            $this->$key = pgParam("$key",$tmp['d4']);

            if($tmp['type']=='editor' || $tmp['type']=='editor_simple'){
                $datas[$key] = pSQLStr(html2db($this->$key));
            }else if($tmp['type']=='xlang_editor_simple' || $tmp['type']=='xlang_editor' || $tmp['type']=='xlang_textarea' || $tmp['type']=='xlang'){
                $arr=array();
                $type="editor";
                if($tmp['type']=='xlang') $type="";
                $arr = $this->mergeLangArr($key);
                //var_dump($arr);
                $datas[$key]=pSQLStr($this->toXML_v2($arr,$type));
            }else{
                //資料型態
                if($tmp['type'] == 'int') {
                    $datas[$key] = pSQLInt($this->$key);
                }elseif($tmp['type'] == 'bool') {
                    $datas[$key] = pSQLBoolean($this->$key);
                }else{
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
                if($fval["type"]=='editor' || $fval['type']=='editor_simple' || $fval['type']=='xlang_editor' || $fval['type']=='xlang_editor_simple'){
                    $xmlstring .='<'.$name.'><![CDATA['.html2db($this->dbrow[$name]).']]></'.$name.'>';
                } else{
                    $xmlstring .='<'.$name.'><![CDATA['.$this->dbrow[$name].']]></'.$name.'>';
                }
            }
            $xmlstring .='</content>';
            $datas[$fkey] =pSQLStr($xmlstring);
        }


		//自動過濾不存在的欄位
		$colums = sql2map("describe ".$this->table);
		$result=array_intersect_key($datas,$colums);
        return $result;
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


    function dbrowDefault($imgname) {
        if(is_array($this->_cols)){
            foreach($this->_cols as $key=>$set) {
                $this->dbrow[$key] = $set['d4'];
            }
        }
    }


    /*讀取分類清單*/
    function loadCategoryList() {
        global $CFG;
        unset($this->categoryList);
        $slq = "select * from " . $this->table_cate . " where pid=-1 " . $this->condition_cate . " order by inuse desc,seq";
        $query = @sql_query($slq);
        while($row = sql_fetch_array($query)) {
            //$this->loadSubCategoryList($row['id'],"",$row['catename']);
            $leaf = $row["leaf"];
            // $xmlvo    = new parseXML($row['catename']);
            // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
            $catename = $row['catename'];
            if(!$leaf) {
                $this->loadSubCategoryList($row['id'], $append, $catename);
            } else {
                $this->categoryList[] = array(
                    'id' => $row['id'],
                    'name' => $append . "└ " . $catename,
                    'fullname' => $catename
                );
            }
        }
        return true;
    }
    /*與前同一組*/
    function loadSubCategoryList($pid, $append, $parentname = "") {
        global $CFG;
        $append .= "　&nbsp;";
        $slq = "select * from " . $this->table_cate . " where pid=$pid " . $this->condition_cate . " order by inuse desc,seq";
        $query = @sql_query($slq);
        while($row = sql_fetch_array($query)) {
            $leaf = $row["leaf"];
            // $xmlvo    = new parseXML($row['catename']);
            // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
            $catename = $row['catename'];
            if(!$leaf) {
                $this->loadSubCategoryList($row['id'], $append, $parentname . "=>" . $catename);
            } else {
                $this->categoryList[] = array(
                    'id' => $row['id'],
                    'name' => $append . "└ " . $catename,
                    'fullname' => $parentname . "=>" . $catename
                );
            }
        }
        return true;
    }

    static public function _loadCategoryList($table_cate,$pid=-1,$append="") {

        global $CFG;
        unset($categoryList);
        $categoryList=array();
        $slq = "select * from " . $table_cate . " where pid=$pid order by inuse desc,seq";

        $query = @sql_query($slq);
        while($row = sql_fetch_assoc($query)) {
            $leaf = $row["leaf"];
            // $xmlvo    = new parseXML($row['catename']);
            // $catename = $xmlvo->value('/content/catename'.$CFG->langs_show);
            //echo $catename;
            $catename = $row['catename'];
            if(!$leaf) {
                $data=self::_loadCategoryList($table_cate, $row['id'], $append. $catename ."=> ");

                if($data){
                    if($categoryList){
                        $tmp=$categoryList;
                        $categoryList=array_merge($tmp,$data);
                    }else{
                        $categoryList=$data;
                    }
                }
            }else{
                $categoryList[] = array(
                    'id' => $row['id'],
                    'name' => $catename,
                    'fullname' =>  $append .  $catename
                );
            }
        }

        return $categoryList;
    }

    /*單頁編輯*/
    function load_onepage(){
        global $CFG;
        $sql = "select * from ".$this->table." where id='".sql_real_escape_string($this->id)."'";
        $res = sql_query($sql);
        $row=sql_fetch_assoc($res);
        if(isset($row["xmlcontent"])) {
            $this->parseXML($row["xmlcontent"]);
        }
        $this->dataXML ="";
        if(isset($row["dataXML"])) {
            $this->dataXML = $row["dataXML"];
            //$this->tableModify_read();
        }
        return true;
    }

    function loadForm_onepage() {
        $this->xmlcontent = $this->toXML();
        $this->dataXML = $this->tableModify();
    }

    function update_onepage() {
        global $CFG;
        $this->loadForm_onepage();
        $strSQL = "delete from ".$this->table." where id=".pSQLStr($this->id);
        $result = sql_query($strSQL);
        $strSQL = "insert into ".$this->table." (xmlcontent,dataXML,id) values("
                ." ".pSQLStr($this->xmlcontent)
                ." ,".pSQLStr($this->dataXML)
                ." ,".pSQLStr($this->id).")";
        //echo $strSQL;
        $result = sql_query($strSQL);
        if($result) {
            $this->action_message = "true";
        } else {
            $this->action_message = "修改失敗";
            echo "<br>".sql_error()."<br>".$strSQL."<br>";
        }
        $this->deal_other();
    }

    function deal_other() {
    }

     /*共用方法*/
    ##生成HTML，XML欄位名稱=欄位性質(img,file,editor,editor_simple,textarea,link,checkbox,text_date,空白表純文字)
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
            $file1 = buildFilehtml($col,$tmp["path"]);
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
        }else if($display=='checkbox_onoff'){
            ?><input type="checkbox" name="<?=$col?>" id="<?=$col?>" value="Y" <?=$this->dbrow[$col]=="Y"?"checked":""?> data-toggle="toggle" data-size="sm" /><?php
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




    //編xml
    function toXML() {
        global $CFG;
        $xmlstring = "<content>";
        foreach($this->_xmls as $key => $tmp) {
            $input_val = pgParam("$key","");
            //echo $key.":".$input_val."<br>";
            if($tmp['type']=='editor'){
                $xmlstring .='<'.$key.'>'.turnCDDATA(html2db($input_val)).'</'.$key.'>';
            }else if($tmp['type']=='img'){
                $img = $input_val;
                // $dealImg = new dealUploadImg($CFG->root_user);
                // $dealImg->docPath = $tmp['set']["path"];
                // $img = $dealImg->doImg($tmp['set']["w"], $tmp['set']["h"], $key, "");
                //
                // //未上圖,則綁定主圖
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
                $path=$tmp['path'];
                $file = $this->deal_upFile($key,$path);
                $xmlstring .='<'.$key.'>'.turnCDDATA($file).'</'.$key.'>';
            }else{
                $xmlstring .='<'.$key.'>'.turnCDDATA($input_val).'</'.$key.'>';
            }
        }
        $xmlstring .= '</content>';
        //echo "#".$xmlstring;
        return $xmlstring;
    }

    function toXML_v2($arr=array(),$type="") {
        global $CFG;
        $xmlstring = "<content>";
        foreach($arr as $key => $tmp) {
            $input_val = pgParam("$key","");
          //  echo $type."<br>";
            if($type=='editor' ){
                $xmlstring .='<'.$key.'>'.turnCDDATA(html2db($input_val)).'</'.$key.'>';
            }else if($type=='img'){
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
            }else if($type=='file'){
                $path=$tmp['path'];
                $file = $this->deal_upFile($key,$path);
                $xmlstring .='<'.$key.'>'.turnCDDATA($file).'</'.$key.'>';
            }else{
                $xmlstring .='<'.$key.'>'.($input_val).'</'.$key.'>';//turnCDDATA
            }
        }
        $xmlstring .= '</content>';
       // echo "#".$xmlstring;
        return $xmlstring;
    }

    //解xml data
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

    /* function parseXML_v3($xmlstring,$type=""){
        global $CFG;
        if ($xmlstring!= null){
            $xml = simplexml_load_string($xmlstring);
            $data= $this->object2array($xml);
            $trees = $xml->xpath('/content');
            foreach($data as $key => $val){
                $value = $xml->xpath('/content/'.$key);
                $this->dbrow[$key] = $value[0];
            }
        }
    }*/

    //上傳檔案
    function deal_upFile($upname = "filename",$append="", $limitedext = "", $size_mb = 10) {
        global $CFG;
        $dirpath = $CFG->root_user_file . $this->cfg["path"].$append;
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
        $sql = "select count(1) as cc from " . $this->table . " where imagexml like '%" . $filename . "%'" . $this->condition;
        $tmp = sql2var($sql);
        return $tmp["cc"];
    }


    //自訂表格
    function tableModify(){
        global $CFG;

        $maxseq=max($_POST["fseq"])+1;
        $xmlstring = "<content>";
        //列
        foreach($_POST["f1"] as $key=>$val){
            $input_seq  = $_POST["fseq"][$key];
            $input_val1 = $_POST["f1"][$key];
            $input_val2 = $_POST["f2"][$key];

            //欄
            if($input_val1==""  && $input_val2=="") continue;
            if(!$input_seq){
                $input_seq=$maxseq;
                $maxseq++;
            }
            $xmlstring .= "<data seq=\"$input_seq\">";
            $xmlstring .='<key>'.turnCDDATA($input_val1).'</key>';
            $xmlstring .='<key>'.turnCDDATA($input_val2).'</key>';

            $xmlstring .= "</data>";
        }
        $xmlstring .= '</content>';
        return $xmlstring;
    }

    //讀取單筆資料
    function load($id = "") {
        global $CFG;
        if(empty($id)) {
            $id = pgParam("id", null);
        }
        if(empty($id)) {
            return false;
        }
        $strSQLQ = "select * from " . $this->table . " where id='" . sql_real_escape_string($id) . "'" . $this->condition;
        $query = sql_query($strSQLQ);
        $this->dbrow = sql_fetch_array($query);
        //讀取XML資料
        if(isset($this->dbrow["imagexml"])) {
            $this->parseXML($this->dbrow["imagexml"]);
        }


        foreach($this->_cols as $key => $tmp) {

            if($tmp['type']=='xlang_editor_simple' || $tmp['type']=='xlang_editor' || $tmp['type']=='xlang_textarea'){
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

    function loadForm(){

    }




}


?>
