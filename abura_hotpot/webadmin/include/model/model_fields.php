<?php
class Fields{
    function setFields($arr=array(),$pid=0 ){
        global $CFG;
        $str="";
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields where pid=".$pid." order by seq asc");
        while($row = @mysql_fetch_assoc($res)){
            $sel="";
            $re = @mysql_query("select * from ".$CFG->tbext."product_fields where pid=".$row["id"]);
            $num = @mysql_num_rows($re);
            if($num>0){
                $str .= "<br>".$row["f_title"]." : ".$this->setFields($arr,$row["id"])."<br>";    
            }else{
                if(in_array($row["id"], $arr))$sel="checked";
                $str.="<input type=\"checkbox\" name=\"usefileds\" value=\"".$row["id"]."\" $sel>".$row["title"];
            }
              $str.="<br>";
        }
        return $str;
    }
    function setProData($cateId=0,$data_arr=array(),$table='product_cate'){
        
    	global $CFG;
        $cres = mysql_query("select * from ".$CFG->tbext.$table." where id=".$cateId);
        $crow = mysql_fetch_assoc($cres);
        $str="";
        if(!$crow["fields_set"])return;
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields where id in (".$crow["fields_set"].") order by seq asc");

        while($row=mysql_fetch_assoc($res)){
        
        	//if(!$val->name)continue;
            echo "<tr class=\"x-tr1\">
                  <th class=\"x-th\"><em>".($val->required==1?"*":"")."</em>".$row["title"]."</th>
                  <td class=\"x-td\">";


            

            $this->type_text($row["id"],$data_arr[$row["id"]]);
            
			echo "</td></tr>";
            if($row["pid"]==0){
                $str="";
            }else{

            }

        }


        //$fields=explode(",", $dao->row["fields_set"]);
        
        return $return;
    }

    


    function setCateFields(){


    }
    
    function setProFields(){

        
    }

    function setData($arr,$dao=array()){
        global $CFG;
        $data  = array();
        $serch = implode("','",array_keys($arr));
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields where f_name in ('".$serch."')");
        while($row = @mysql_fetch_assoc($res)){
            $data[$row["f_name"]]=Array(     "id" => $row["id"],
                                          "title" => $row["f_title"],
                                           "name" => $row["f_name"],
                                           "type" => $row["f_type"],
                                      "data_type" => $row["data_type"],
                                          "value" => $dao[$row["f_name"]],
                                       "required" => $arr[$row["f_name"]][0],
                                        "default" => $arr[$row["f_name"]][1],
                                        );
            //echo $row["f_name"]."-".$arr[$row["f_name"]][0]."<br>";
        }
        // $this->adm_eiditform(json_encode($data));
        return json_encode($data);
    }
    public static function _setData($serch,$dao=array()){
        global $CFG;
        $data  = array();
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields where f_name in (".$serch.")");
        while($row = @mysql_fetch_assoc($res)){
            $data[$row["f_name"]]=Array(     "id" => $row["id"],
                                          "title" => $row["f_title"],
                                           "name" => $row["f_name"],
                                           "type" => $row["f_type"],
                                      "data_type" => $row["data_type"],
                                          "value" => $dao[$row["f_name"]],
                                       "required" => $arr[$row["f_name"]][0],
                                        "default" => $arr[$row["f_name"]][1],
                                        );
            //echo $row["f_name"]."-".$arr[$row["f_name"]][0]."<br>";
        }
        // $this->adm_eiditform(json_encode($data));
        return json_encode($data);
    }

    ##建立後台表單
    function bulidFrom($o_data){
        global $CFG,$dao;
        $data=json_decode($o_data);
        foreach($data as $key => $val){
            if(!$val->name)continue;
            echo "<tr class=\"x-tr1\">
                        <th class=\"x-th\"><em>".($val->required==1?"*":"")."</em>".$val->title."</th>
                        <td class=\"x-td\">";
            switch($val->type){
                case "link":
                    $this->type_text($val->name,$val->value,$val->required);
                    echo "(ex:http://....)<br>";
                    //$this->type_checkbox($val->name."_webNewWin",$dao->dbrow[$val->name."_webNewWin"],1,"另開視窗");
                break;
                case "text":
                case "int":
                    $this->type_text($val->name,$val->value,$val->required);
                break;
                
                case "ckedit_simple":
                    $this->type_ckedit_simple($val->name,$val->value,$val->id);
                break;
                case "radio":
                    $this->type_radio($val->name,$val->value,$val->id);
                break;
                
                case "checkbox":

                break;
                case "select":
                    $this->type_select($val->name,$val->value,$val->id);
                break;
            }
            echo "</td></tr>";
        }
    }
   
    function type_text($name="",$value="",$required=0){
        if(!$name)return;
        $class= $required==1?"class=\"required\"":"";
        echo "<input type=\"text\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"$value\" $class>";
    }
    function type_radio($name="",$value="",$id=0){
        global $CFG; 
        if(!$name)return;
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields_value where pid=$id");
        while($row=@mysql_fetch_assoc($res)){
            $sel=$value==$row["id"]?"checked":"";
            $str.= "<input type=\"radio\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"".$row["id"]."\" $sel>".$row["title"];    
        }
        echo $str;
    }
    function type_ckedit_simple($name="",$value="",$required=0){
        if(!$name)return;
        $class= $required==1?"required":"";
        echo "<textarea type=\"text\" name=\"$name\" id=\"$name\" class=\"$class simple callckeditor\" >".db2html($value)."</textarea>";
    }
    function type_checkbox($name="",$value="",$tf=0,$msg=""){
        if($tf){
            $sel=$value?"checked":"";
            $str.= "<input type=\"checkbox\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"true\" $sel>".$msg;  
        }else{

        }
        echo $str;
       
    }
    function type_select($name="",$value="",$id=0){
        global $CFG; 
        if(!$name)return;
        $str= "<select  name=\"$name\" id=\"$name\"><option value=\"\">請選擇</option>";
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields_value where pid=$id");
        while($row=@mysql_fetch_assoc($res)){
            $sel=$value==$row["id"]?"selected":"";
            $str.= "<option value=\"".$row["id"]."\" $sel>".$row["f_title"]."</option>";    
        }
        $str.= "</select>";
        echo $str;
    }
   



/*
    function bulidFrom($o_data){
        global $CFG,$dao;
        $data=json_decode($o_data);
        foreach($data as $key => $val){
            if(!$val->name)continue;
            echo "<tr class=\"x-tr1\">
                        <th class=\"x-th\"><em>".($val->required==1?"*":"")."</em>".$val->title."</th>
                        <td class=\"x-td\">";
            switch($val->type){
                case "link":
                    $this->type_text($val->name,$val->value,$val->required);
                    echo "(ex:http://....)<br>";
                    //$this->type_checkbox($val->name."_webNewWin",$dao->dbrow[$val->name."_webNewWin"],1,"另開視窗");
                break;
                case "text":
                case "int":
                    $this->type_text($val->name,$val->value,$val->required);
                break;
                
                case "ckedit_simple":
                    $this->type_ckedit_simple($val->name,$val->value,$val->id);
                break;
                case "radio":
                    $this->type_radio($val->name,$val->value,$val->id);
                break;
                
                case "checkbox":

                break;
                case "select":
                    $this->type_select($val->name,$val->value,$val->id);
                break;
            }
            echo "</td></tr>";
        }
    }

    
    function type_text($name="",$value="",$required=0){
        if(!$name)return;
        $class= $required==1?"class=\"required\"":"";
        echo "<input type=\"text\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"$value\" $class>";
    }
    function type_radio($name="",$value="",$id=0){
        global $CFG; 
        if(!$name)return;
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields_value where pid=$id");
        while($row=@mysql_fetch_assoc($res)){
            $sel=$value==$row["id"]?"checked":"";
            $str.= "<input type=\"radio\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"".$row["id"]."\" $sel>".$row["f_title"];    
        }
        echo $str;
    }
    function type_ckedit_simple($name="",$value="",$required=0){
        if(!$name)return;
        $class= $required==1?"required":"";
        echo "<textarea type=\"text\" name=\"$name\" id=\"$name\" class=\"$class simple callckeditor\" >".db2html($value)."</textarea>";
    }
    function type_checkbox($name="",$value="",$tf=0,$msg=""){
        if($tf){
            $sel=$value?"checked":"";
            $str.= "<input type=\"checkbox\" name=\"$name\" id=\"$name\" size=\"80\" maxLength=\"100\" value=\"true\" $sel>".$msg;  
        }else{

        }
        echo $str;
       
    }
    function type_select($name="",$value="",$id=0){
        global $CFG; 
        if(!$name)return;
        $str= "<select  name=\"$name\" id=\"$name\"><option value=\"\">請選擇</option>";
        $res = @mysql_query("select * from ".$CFG->tbext."product_fields_value where pid=$id");
        while($row=@mysql_fetch_assoc($res)){
            $sel=$value==$row["id"]?"selected":"";
            $str.= "<option value=\"".$row["id"]."\" $sel>".$row["f_title"]."</option>";    
        }
        $str.= "</select>";
        echo $str;
    }*/
    /*function type_file($o_data=""){
        $file1 = new buildImageBroser("cover");
                    $file1->showwidth  = $CFG->gia_w;
                    $file1->showheight = $CFG->gia_h;
                    $file1->noneHeight = 'N';
                    $file1->noneWidth = 'N';
                    $file1->filepath = $CFG->gia_path.$dao->dbrow["cover"];
                    $file1->fixdir = $CFG->gia_path;
        echo "   <tr class=\"x-tr1\"><th class=\"x-th\">圖片1</th><td class=\"x-td\">";             
        $file1->build();
                echo "    </td>
                </tr>";
               // return $str;
    }*/

}
?>