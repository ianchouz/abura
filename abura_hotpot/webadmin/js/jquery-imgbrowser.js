//[
(function($) {
  jQuery.fn.bindAll = function(options) {
    var $this = this;
    jQuery.each(options, function(key, val){
      $this.bind(key, val);
    });
    return this;
  }
  jSFworld = function(config) {
    var parent = $("body");
    if( config.width == undefined ) config.width = 760;
    if( config.height == undefined ) config.height = 400;
    var width = config.width;
    var height = config.height;
    if( config.fixwidth == undefined ) config.fixwidth = 80;
    if( config.fixheight == undefined ) config.fixheight = 80;
    if( config.showwidth == undefined ) config.showwidth = 80;
    if( config.showheight == undefined ) config.showheight = 80;
    if( config.asigndir == undefined ) config.asigndir = "";


    var flashobj = null;
    
    //檢查物件是否已經存在
    if ($("#jBoxHolder").is("div")){
      var jBoxHolder = $("#jBoxHolder");
      jBoxHolder.html("");
    }else{
      var jBoxHolder = $('<div id="jBoxHolder"></div>');
    }
    var mask = $('<div class="mask" style="width:0px;height:0px;display: none;"></div>');
    mask.appendTo(jBoxHolder);
    var jBoxWraper = $('<div class="jBox-wraper" style="z-index: 1100; width: ' + width + 'px;visibility: hidden;"></div>');
    var jBox = $('<div id="jBoxID" class="jBox"></div>');
    var jBoxHandler = $('<div class="jBox-handler"></div>');
    var jBoxHandlerTitle = $('<h3>' + config.title + '</h3>');
    var jBoxHandlerClose = $('<input type="button" class="jBox-close" title="關閉"/>');
                    
    var jBoxContent = $('<div class="jBox-content" style="width:100%;height:' + height + 'px; overflow: hidden;margin:0px;padding:0px;"></div>');
    
    var jBoxTree = $('<div id="jBox-dirtree" class="jBox-tree-menu" style="width:180px;float:left;height:' + height + 'px; overflow: auto"></div>');
    jBoxTree.appendTo(jBoxContent);
    var jBoxTabs = $('<div id="jBox-tabs_image" style="width:'+(width-183)+'px;float:left;height:' + height + 'px; overflow: auto;padding:0px;margin:0px;border:1px;"></div>');
    var jBoxTabsMenu = $('<ul><li><a href="#jBox-tabs-browser__" style="font-size:10px;padding:2px 2px;">圖片清單</a></li><li><a href="#jBox-tabs-upload" style="font-size:10px;padding:2px 2px;">檔案上傳</a></li></ul>');
    
    
    //
    jBoxTabsMenu.appendTo(jBoxTabs);

    var jBox_tabs_browser = $('<div id="jBox-tabs-browser__" style="display:none;"></div>');
    var jBox_tabs_browser_btn = $('<div id="jBox_btn_action"></div>');
    //add button
    var selectbtn = $('<input type="button" value="選擇" id="btn_clickselectimg" class="btn_startupload" style="font-size:12px;"/>');
    var delbtn = $('<input type="button" value="刪除檔案" id="btn_clickdeleteimg" class="btn_startupload" style="font-size:12px;"/>');
    var createdirbtn = $('<input type="button" value="建立新目錄" id="btn_clickcreatedir" class="btn_startupload" style="font-size:12px;"/>');
    var deletedirbtn = $('<input type="button" value="刪除目前目錄" id="btn_clickdeletedir" class="btn_startupload" style="font-size:12px;"/>');
    selectbtn.appendTo(jBox_tabs_browser_btn);
    delbtn.appendTo(jBox_tabs_browser_btn);
    createdirbtn.appendTo(jBox_tabs_browser_btn);
    deletedirbtn.appendTo(jBox_tabs_browser_btn);
    jBox_tabs_browser_btn.appendTo(jBox_tabs_browser);
    var jBox_tabs_browser_nowinfo = $('<div id="jBox_now_info" class="jBox_now_info"><div id="jBox_now_dir">目前目錄：</div><div id="jBox_now_fileinfo">目前選中檔案：</div><div>每頁筆數:<select id="jBox_image_pagesize"  style="font-size:9px;hiehgt:12px;"><option value="20">20</option><option value="50">50</option><option value="100">100</option></select> &nbsp;檔名過濾：<input type="text" size="10" maxlength="10" id="jBox_filterkey"> &nbsp;<span id="jBox_page_list"></span></div></div><hr/>');
    jBox_tabs_browser_nowinfo.appendTo(jBox_tabs_browser);
    var jBox_tabs_browser_list = $('<div class="clearfix" id="photo-list" style="display: block;height:'+(height-165)+'px; overflow: auto"><ul></ul></div><div style="clear : both;"></div>');
    jBox_tabs_browser_list.appendTo(jBox_tabs_browser);

    var jBox_tabs_upload = $('<div id="jBox-tabs-upload"><div id="jBox_up_dir" class="jBox_now_info"></div><hr><div class="swfupload-control"><div class="fieldset flash" id="fsUploadProgress"><span class="legend">訊息</span></div></div><div style="display: inline;"><div style="float:right;" id="spanButtonPlaceholder"></div></div>&nbsp;<div id="btn_flupclose" style="float:right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div><div style="clear:left;heiht:1px;"></div>');
    
    $('<div id="jBox-tabs-browser__message" style="padding:20px;">載入中...請稍候!!</div>').appendTo(jBoxTabs);
    jBox_tabs_browser.appendTo(jBoxTabs);
    jBox_tabs_upload.appendTo(jBoxTabs);
    jBoxTabs.appendTo(jBoxContent);
    
    jBoxWraper.close = function() {
      jBoxWraper.css("visibility", "hidden");
      mask.hide();
    }
    
    jBoxWraper.open = function() {
      jBoxWraper.css("visibility", "visible");
      jBoxWraper.cover();

      $('#jBox-dirtree').fileTree({listurl:fulladmin+'include/image/imageBrowser.php',
       delurl:fulladmin+'include/image/imageDelete.php',
       mkdirurl:fulladmin+'include/image/imageDir.php',
 root: "",asigndir:config.asigndir, script: fulladmin+'include/jqueryFileTree/connectors/jqueryFileTree.php',showwidth:80, showheight:80,fixwidth:config.fixwidth,fixheight:config.fixheight}, function(obj) {
        jBoxWraper.close();
        if (config.callback) {
          eval(config.callback+"(obj)");
        }
      });
      $("#jBox-tabs_image").tabs({ 
        select: function(e, ui) { 
          var thistab = ui;
          if (thistab.index==0){
            var nn = $("#jBox_up_dir").attr("data-dir");

            $("#jBox-dirtree").find("a[rel="+nn+"]").click();

            if ($("#jBox-dirtree").find("a[rel="+nn+"]").parent().hasClass("collapsed")){
              $("#jBox-dirtree").find("a[rel="+nn+"]").click();
            }

          }else if (thistab.index==1){
            //open flash model                            
            $(".progressWrapper").remove();
          }
        } 
      });

      $('.swfupload-control').each(function(){
        $(this).swfupload({
          upload_url: fulladmin+"include/image/imageUpload.php",                     
          file_size_limit : "10240",
          file_types : "*.jpg;*,png;*.gif",
          file_types_description : "圖片格式",
          file_upload_limit : "0",
          flash_url : fulladmin+"js/swfupload/swfupload.swf",
	  	  button_image_url : fulladmin+'images/swfupload/XPButtonUploadText_61x22.png',
	  	  button_width : 70,
	  	  button_height : 22,
          button_placeholder_id : "spanButtonPlaceholder",
          button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
          debug: false
        });
      });
  
      

      $("#btn_flup").click(function(){
        flashobj.startUpload();
      });
      $("#btn_flupclose").click(function(){
        $("a[href=#jBox-tabs-browser__]").click();
      });
      
      //                  


      mask.show();
      
      var upsize=0;
      var listeners = {
        swfuploadLoaded: function(event){
          $('#fsUploadProgress').html("");
          upsize=0;
          $('#fsUploadProgress', this).append('<li>元件載入成功，請點選[選擇圖片]按鈕，進行上傳，可同時點選多個。</li>');
        },
        fileQueued: function(event, file){
          upsize++;
          $('#fsUploadProgress', this).append('<li>準備上傳 - '+file.name+'</li>');
          // start the upload once it is queued
          // but only if this queue is not disabled
          //if (!$('input[name=disabled]:checked', this).length) {
            //$(this).swfupload('startUpload');
            var param = {'gdir' : $("#jBox_up_dir").attr("data-dir")};
            $(this).swfupload('setPostParams', param);
            $(this).swfupload('startUpload');
          //}
        },
        fileQueueError: function(event, file, errorCode, message){
          $('#fsUploadProgress', this).append('<li>File queue error - '+message+'</li>');
        },
        fileDialogStart: function(event){
          $('#fsUploadProgress').html("");
          //$('#fsUploadProgress', this).append('<li>打開檔案瀏覽</li>');
        },
        fileDialogComplete: function(event, numFilesSelected, numFilesQueued){
          //$('#fsUploadProgress', this).append('<li>結束檔案瀏覽</li>');
        },
        uploadStart: function(event, file){
          $('#fsUploadProgress', this).append('<li>開始上傳 - '+file.name+'</li>');
          // don't start the upload if this queue is disabled
          //if ($('input[name=disabled]:checked', this).length) {
          //  event.preventDefault();
          //}
        },
        uploadProgress: function(event, file, bytesLoaded){
	      //var percent = Math.ceil((bytesLoaded / file.size) * 100);
	      //$('#____swfProgress').css('width',percent+'px');
	      //$('#____swfProgress').html(percent+'%');
          //$('#fsUploadProgress', this).append('<li>Upload progress - '+bytesLoaded+'</li>');
        },
        uploadSuccess: function(event, file, serverData){
          if (!serverData || serverData=="success"){
            $('#fsUploadProgress', this).append('<li><font color="green">上傳成功 - '+file.name+'</font></li>');
          }else{
            $('#fsUploadProgress', this).append('<li><font color="red">上傳失敗 - '+file.name+',訊息:'+serverData+'</font></li>'); 
          }
        },
        uploadComplete: function(event, file){
          //$('#fsUploadProgress', this).append('<li>Upload complete - '+file.name+'</li>');
          // upload has completed, lets try the next one in the queue
          // but only if this queue is not disabled
          
          //if (!$('input[name=disabled]:checked', this).length) {
            //$(this).swfupload('startUpload');
            var param = {'gdir' : $("#jBox_up_dir").attr("data-dir")};
            $(this).swfupload('setPostParams', param);
            $(this).swfupload('startUpload');
          //}
        },
        uploadError: function(event, file, errorCode, message){
          $('#fsUploadProgress', this).append('<li>上傳失敗 - '+message+'</li>');
        }
      };
      $("#btn_flupclose").attr("disabled", false);
      $('.swfupload-control').bindAll(listeners);              
    }
    
    
    //追加??和??按?到 jBoxHandlerTitle '<div class="jBox-handler"></div>
    jBoxHandlerTitle.appendTo(jBoxHandler);
    
    jBoxHandlerClose.click(function() {
      jBoxWraper.close();
    });
    
    jBoxHandlerClose.appendTo(jBoxHandler);
    
    //?部追加到 jBox <div id="jBoxID" class="jBox"></div>
    jBoxHandler.appendTo(jBox);
    
    //?容追加到 jBox <div id="jBoxID" class="jBox"></div>
    jBoxContent.appendTo(jBox);
    
    //jBox追加到 jBoxWraper
    jBox.appendTo(jBoxWraper);
    jBoxWraper.appendTo(jBoxHolder);
    parent.append(jBoxHolder);
    /*

    var left = ($(window).width() / 2 - jBoxWraper.width() / 2);
    var top = ($(window).height() / 2 - jBoxWraper.height()/2);
    */
    var pos = _center(jBoxWraper.width(), jBoxWraper.height());
    var left = pos[0];
    var top = pos[1];
    if (top<0){top=5;}
    if (left<0){left=5;}
    
    jBoxWraper.css({ left: left, top: top });
    
    jBoxWraper.cover = function() {
      var doc = $(document);
      var w = doc.width();
      var h = doc.height();
      mask.css({ width: w, height: h });
    }


    jBoxWraper.open();
  }
    var _center = function (w, h) {
      _doc = jQuery(document);
      _win = jQuery(window);
      _docHeight = _doc.height();
      _winHeight = _win.height();
      _winWidth = _win.width();
      return [(_docHeight > _winHeight) ? _winWidth/2 - w/2 - 18: _winWidth/2 - w/2,
      _doc.scrollTop() + _winHeight/2 - h/2];
    };
})(jQuery);
//]