//[
(function($) {
  var jBoxWraper = null;
  jSFmulitworld = function(config) {
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
    jBoxWraper = $('<div class="jBox-wraper" style="z-index: 1100; width: ' + width + 'px;visibility: hidden;"></div>');
    var jBox = $('<div id="jBoxID" class="jBox"></div>');
    var jBoxHandler = $('<div class="jBox-handler"></div>');
    var jBoxHandlerTitle = $('<h3>' + config.title + '</h3>');
    var jBoxHandlerClose = $('<input type="button" class="jBox-close" title="關閉"/>');
                    
    var jBoxContent = $('<div class="jBox-content" style="width:100%;height:' + height + 'px; overflow: hidden;margin:0px;padding:0px;"></div>');
    
    var jBoxTree = $('<div><input type="radio" value="img" name="_radio_browser_type" checked/>圖片&nbsp;<input type="radio" value="fla" name="_radio_browser_type"/>FLASH&nbsp;</div><div id="jBox-dirtree" class="jBox-tree-menu" style="width:150px;float:left;height:' + height + 'px; overflow: auto"></div>');
    jBoxTree.appendTo(jBoxContent);
    var jBoxTabs = $('<div id="jBox-tabs_image" style="width:'+(width-153)+'px;float:left;height:' + height + 'px; padding:0px;margin:0px;border:1px;"></div>');
    var jBoxTabsMenu = $('<ul><li><a href="#jBox-tabs-browser__" style="font-size:10px;padding:2px 2px;">檔案清單</a></li><li><a href="#jBox-tabs-upload" style="font-size:10px;padding:2px 2px;">檔案上傳</a></li></ul>');

    jBoxTabsMenu.appendTo(jBoxTabs);

    var jBox_tabs_browser = $('<div id="jBox-tabs-browser__"></div>');
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
    var jBox_tabs_browser_list = $('<div class="clearfix" id="photo-list" style="display: block;height:'+(height-180)+'px;overflow: auto;" ><ul></ul></div><div style="clear : both;"></div>');
    jBox_tabs_browser_list.appendTo(jBox_tabs_browser);

    var jBox_tabs_upload = $('<div id="jBox-tabs-upload"><div id="jBox_up_dir" class="jBox_now_info"></div><hr><div id="swfupload-control"><div class="fieldset flash" id="fsUploadProgress"><span class="legend">訊息</span></div></div><div style="display: inline;"><div style="float:left;" id="spanButtonPlaceholder"><input type="button" id="btn_img_open" value="上傳圖片"/></div>&nbsp;<div id="btn_flupclose" style="float:right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div><div style="clear:left;heiht:1px;"></div>');
    
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
            bindUploadControl();
          }
        } 
      });

      $("#btn_flupclose").click(function(){
        $("a[href=#jBox-tabs-browser__]").click();
      });
      mask.show();
      bindNewTree(config,'');
      $('input[name=_radio_browser_type]').click(function(){
        bindNewTree(config,'');
        $("a[href=#jBox-tabs-browser__]").click();
      });
    }

    jBoxHandlerTitle.appendTo(jBoxHandler);
    
    jBoxHandlerClose.click(function() {
      jBoxWraper.close();
    });
    
    jBoxHandlerClose.appendTo(jBoxHandler);
    
    jBoxHandler.appendTo(jBox);
    
    jBoxContent.appendTo(jBox);
    
    jBox.appendTo(jBoxWraper);
    jBoxWraper.appendTo(jBoxHolder);
    parent.append(jBoxHolder);

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

  var bindUploadControl = function () {
    var uploadurl = fulladmin+"include/image/multiUpload.php";
    var file_types = "*.jpg;*.png;*.gif;*.swf";
    var file_types_description = "圖片檔案;FLASH檔案";
    
    //圖片批次上傳
    $('#swfupload-control').swfupload({
       flash_url : fulladmin+"js/swfupload/swfupload.swf",
       upload_url: uploadurl,
       file_size_limit : "10240",
       file_types : file_types,
       file_types_description : file_types_description,
       file_upload_limit : "0",
       //圖片按鈕
       button_image_url : fulladmin+"images/swfupload/XPButtonUploadText_61x22.png",
       button_width : 70,
       button_height : 22,
       button_placeholder : $('#btn_img_open')[0],
       //是否debug
       debug: false
    })
    
    
    //綁定flash上傳訊息
    .bind('swfuploadLoaded', function(event){
      $('#fsUploadProgress').html("");
      $('#fsUploadProgress', this).append('<li>元件載入成功，請點選[上傳]按鈕，進行上傳，可同時點選多個。</li>');
    })
    .bind('fileQueued', function(event, file){
      $('#log').append('<li>準備上傳 - '+file.name+' 至 ' + $("#targetdir").val() +'</li>');
      // start the upload since it's queued
            var param = {'gdir' : $("#jBox_up_dir").attr("data-dir")};
            $(this).swfupload('setPostParams', param);
            $(this).swfupload('startUpload');
      //movescroll();
    })
    .bind('fileQueueError', function(event, file, errorCode, message){
      $('#fsUploadProgress', this).append('<li>上傳發生錯誤 - '+message+'</li>');
      //movescroll();
    })
    .bind('fileDialogStart', function(event){
      //$('#log').append('<li>File dialog start</li>');
      //movescroll();
    })
    .bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
      //$('#log').append('<li>File dialog complete</li>');
      //movescroll();
    })
    .bind('uploadStart', function(event, file){
      $('#fsUploadProgress', this).append('<li>開始上傳 - '+file.name+'</li>');
    })
    .bind('uploadProgress', function(event, file, bytesLoaded){
      //$('#log').append('<li>Upload progress - '+bytesLoaded+'</li>');
      //movescroll();
    })
    .bind('uploadSuccess', function(event, file, serverData){
      if (!serverData || serverData=="success"){
        $('#fsUploadProgress', this).append('<li><font color="green">上傳成功 - '+file.name+'</font></li>');
      }else{
        $('#fsUploadProgress', this).append('<li><font color="red">上傳失敗 - '+file.name+',訊息:'+serverData+'</font></li>'); 
        }
    })
    .bind('uploadComplete', function(event, file){
      //$('#log').append('<li>上傳完成 - '+file.name+'</li>');
      // upload has completed, lets try the next one in the queue
      var param = {'gdir' : $("#jBox_up_dir").attr("data-dir")};
      $(this).swfupload('setPostParams', param);
      $(this).swfupload('startUpload');
    })
    .bind('uploadError', function(event, file, errorCode, message){
      $('#fsUploadProgress', this).append('<li>上傳失敗 - '+message+'</li>');
    });
    //$('#swfupload-control').swfupload('setFileTypes', file_types,file_types_description);
  };
    var bindNewTree = function (config,root){
      $('#jBox-dirtree').fileTree({
        script: fulladmin+'include/jqueryFileTree/connectors/jqueryFileTree.php',
        asigndir:config.asigndir,
        showwidth:80, 
        showheight:80,
        fixwidth:config.fixwidth,
        fixheight:config.fixheight,
        multitype:true,
        root:root
      },function(obj) {
        jBoxWraper.close();
        if (config.callback) {
          eval(config.callback+"(obj)");
        }
      });
    };
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