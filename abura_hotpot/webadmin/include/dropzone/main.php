<?php
class uploadImg{
    function __construct() {
    	$this->includeFile();
    }
    
    function includeFile(){
    	include('../include/dropzone.php');
       
    }
}
?>