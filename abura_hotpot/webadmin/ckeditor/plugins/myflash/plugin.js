var noweditor;
(function(){
	//Section 1 :按下自定義按鈕時執行的代碼
	var a= {
		exec:function(editor){
      var set_fix_path = "editor/";
      if( typeof(editor_fix_path) != "undefined"){
        set_fix_path = editor_fix_path;
      }
      var openurl=fulladmin+"include/flash/choice_editor.php?path="+set_fix_path;
      winopen(800,0,openurl);
      noweditor = editor;
	  }
	},
  //Section 2 :創建自定義按鈕、綁定方法
  b= 'myflash' ;
  CKEDITOR.plugins.add(b,{
  	init:function(editor){
  		editor.addCommand(b,a);
  		editor.ui.addButton( 'myflash' ,{
  			label : editor.lang.myflash ,
  			icon: this.path + 'flash_add.png' ,
  			command:b
  	  });

    }
  });
})();
  function set2FEditor(data){
    if (data && data!= null){
    var setwidth = "";
    if (!isNaN(data.width)){
      setwidth =' width="'+data.width +'" ';
    }
    var setheight = "";
    if (!isNaN(data.height)){
      setheight =' height="'+data.height +'" ';
    }

      var hh = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" ' + setwidth + setheight+ ' >' +
'<param name="movie" value="' + data.url + '">'+
'<param name="quality" value="high">'+
'<embed src="' + data.url + '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" ' + setwidth + '></embed></object>';
      noweditor.insertHtml(hh)
    }
  }
