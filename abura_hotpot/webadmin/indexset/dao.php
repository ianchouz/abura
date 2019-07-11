<?php
include_once("../applib.php");
include_once("../include/dbTool.php");
include_once("../include/model/controller.php");
include_once("../include/QueryParames.php");
include_once("../include/upFile.php");
$menu_id = "indexset";
class main extends controller {
    function __construct() {
        global $CFG;
        $this->id = "indexset";
        $this->table = $CFG->tbext . "webconfig";
        $this->cfg = $CFG->indexset; // 上傳路徑/編輯器路徑

        ##XML欄位名稱=欄位性質(img/file;editor/textarea,空白表純文字)
        // Banner
        $this->_xmls['s1data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // $this->_xmls['s1data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // Banner IMG
        for($i=1;$i<=5;$i++) {
            $this->_xmls['s1_title'.$i] = array('type'=>'textarea', 'style'=>'');
            $this->_xmls['dsk'.$i] = array('type'=>'img','set'=> $CFG->indexset);
            $this->_xmls['mbl'.$i] = array('type'=>'img','set'=> $CFG->indexset_mbl);
            $this->_xmls['type'.$i] = array('type'=>'checkbox');
            $this->_xmls['video'.$i] = array('type'=>'');
        }

        // STORY 會社介紹
        $this->_xmls['s21data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s21data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // STORY INTRO
        $this->_xmls['s21data_intro_title'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s21data_intro_content'] = array('type'=>'textarea', 'style'=>'');
        for($i=1;$i<=3;$i++) {
            $this->_xmls['s21data_img'.$i] = array('type'=>'img','set'=> $CFG->s21data_img);
        }

        // MENU 味自慢集
        $this->_xmls['s22data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s22data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');

        // MEAL 套餐
        $this->_xmls['s23data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s23data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s23data_popupNote'] = array('type'=>'');
        $this->_xmls['s23data_popupNotenote'] = array('type'=>'textarea', 'style'=>'textarea_small_height');

        // ENVIRONMENT 店內寫真
        $this->_xmls['s3data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s3data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // ENVIRONMENT IMG
        for($i=1;$i<=10;$i++) {
            $this->_xmls['s3data_img'.$i] = array('type'=>'img','set'=> $CFG->s3data_img);
            $this->_xmls['s3data_type'.$i] = array('type'=>'checkbox');
            $this->_xmls['s3data_video'.$i] = array('type'=>'');
        }


        // RECOMMEND 達人秘訣
        $this->_xmls['s4data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s4data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        // ENVIRONMENT IMG
        for($i=1;$i<=5;$i++) {
            $this->_xmls['s4data_img'.$i] = array('type'=>'img','set'=> $CFG->s4data_img);
            $this->_xmls['s4data_title'.$i] = array('type'=>'textarea', 'style'=>'textarea_small_height');
            $this->_xmls['s4data_content'.$i] = array('type'=>'textarea', 'style'=>'');
        }

        // NEWS 揭示板
        $this->_xmls['s5data_title_en'] = array('type'=>'textarea', 'style'=>'textarea_small_height');
        $this->_xmls['s5data_title_ch'] = array('type'=>'textarea', 'style'=>'textarea_small_height');


        // $this->_xmls['cover'] = array('type'=>'img' ,'set'=>array_merge($CFG->indexsetImg,array("path"=>$CFG->indexset["path"])));
    }
}
$dao = new main();
?>
