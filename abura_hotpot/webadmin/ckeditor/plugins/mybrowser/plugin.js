var noweditor;
(function(){
  //Section 1 :���U�۩w�q���s�ɰ��檺�N�X
  var a= {
    exec:function(editor){
      var set_fix_path = "editor/";
      if( typeof(editor_fix_path) != "undefined"){
        set_fix_path = editor_fix_path;
      }
      var set_go_dir = '';
      if( typeof(go_dir_path) != "undefined"){
        set_go_dir = go_dir_path;
      }
      var openurl=fulladmin+"include/image/choice_editor.php?path="+set_fix_path+'&gdir='+set_go_dir;
      winopen(800,0,openurl);
      noweditor = editor;
    }
  },
  //Section 2 :�Ыئ۩w�q���s�B�j�w��k
  b= 'mybrowser' ;
  CKEDITOR.plugins.add(b,{
    init:function(editor){
      editor.addCommand(b,a);
      editor.ui.addButton( 'mybrowser' ,{
        label : editor.lang.mybrowser ,
        icon: this.path + 'picture_add.png' ,
        command:b
      });
    }
  });
})();
  function setImageEditor(data){
    if (data && data!= null && data.length >0){
      for(var i=0;i< data.length;i++){
        var hh = '<div><img src="' + data[i].url + '"></div>';
      //CKEDITOR.instances.content.insertHtml(hh);
        noweditor.insertHtml(hh)
      }
    }
  }