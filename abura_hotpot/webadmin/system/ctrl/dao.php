<?php
include_once("../../applib.php");
include_once("../../include/dbTool.php");
include_once("../../include/model/controller.php");
include_once("../../include/QueryParames.php");
include_once("../../include/upFile.php");
$menu_id = "ctrl";
class main extends controller {
    function __construct() {
        global $CFG;
        $this->id = "ctrl";
        $this->table = $CFG->tbext . "webconfig";
        $this->cfg = $CFG->adminset; // 上傳路徑/編輯器路徑
        
        ##XML欄位名稱=欄位性質(img/file;editor/textarea/color,空白表純文字)

        $this->_xmls['bg'] = array('type'=>'img'  ,'set'=> array_merge($CFG->adminBg,array("path"=> $CFG->admin["path"])));
        $this->_xmls['logo'] = array('type'=>'img'  ,'set'=> array_merge($CFG->adminLogo,array("path"=> $CFG->admin["path"])));
        $this->_xmls['logo1'] = array('type'=>'img'  ,'set'=> array_merge($CFG->adminLogo,array("path"=> $CFG->admin["path"])));


        $this->_xmls['open_MemCate'] = array('type'=>'');
        $this->_xmls['open_MemSocial'] = array('type'=>'');

        $this->_xmls['text_MemCate'] = array('d4'=> "0", 'type'=>'', 'style'=>'width:100px' );

        $this->_xmls['admin_MemCate'] = array('type'=>'');
        $this->_xmls['admin_MemSocial'] = array('type'=>'');



        $this->_xmls['copyright'] = array('type'=>'editor_simple');
        
        $this->_xmls['webURL'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['webURLtit'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['webNewWin'] = array('d4'=> null, 'type'=>'bool');

        $this->_xmls['fcolor'] = array('d4'=> "0", 'type'=>'');
        $this->_xmls['color1'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['color2'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['title'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['stitle'] = array('d4'=> null, 'type'=>'');

        $this->_xmls['bn_title'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_title2'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_stitle'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_fcolor'] = array('d4'=> 0, 'type'=>'');
        $this->_xmls['bn_webURL'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_webNewWin'] = array('d4'=> null, 'type'=>'bool');
        $this->_xmls['bn_URLtit'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_videourl'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_autoplay'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['bn_img'] = array('type'=>'img','set'=>$CFG->cover);

      
        $this->_xmls['webURL_h'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['webURLtit_h'] = array('d4'=> null, 'type'=>'');
        $this->_xmls['webNewWin_h'] = array('d4'=> null, 'type'=>'bool');

       
        $this->_xmls['openSocial'] = array('type'=>'');
        $this->_xmls['openSocial2'] = array('type'=>'');
        $this->_xmls['openLangs'] = array('type'=>'');
        $this->_xmls['openVideoBanner'] = array('type'=>'');

        $this->_xtable["step"]=array('fields' => array( array("name"=>"值","width"=>500))); 
        $this->_xtable["tip"]=array('fields' => array( array("name"=>"值","width"=>500))); 
        

       
    }
}
$dao = new main();
?>
