//[
(function($) {
  jSyoutubeSelect = function(config) {
    var parent = $("body");
    if( config.width == undefined ) config.width = 760;
    if( config.height == undefined ) config.height = 200;
    var width = config.width;
    var height = config.height;
    if( config.title == undefined ) config.title = "youtube連結取得";
    if( config.objid == undefined ) config.objid = null;
    //檢查物件是否已經存在
    if ($("#__youtube_select").is("div")){
      var __youtube_select = $("#__youtube_select");
      __youtube_select.html("");
    }else{
      var __youtube_select = $('<div id="__youtube_select"></div>');
    }
    var mask = $('<div class="mask" style="width:0px;height:0px;display: none;"></div>');
    mask.appendTo(__youtube_select);

    var jBoxWraper = $('<div class="jBox-wraper" style="z-index: 1100; width: ' + width + 'px;visibility: hidden;"></div>');
    var jBox = $('<div id="jBoxID" class="jBox"></div>');
    var jBoxHandler = $('<div class="jBox-handler"></div>');
    var jBoxHandlerTitle = $('<h3>' + config.title + '</h3>');
    var jBoxHandlerClose = $('<input type="button" class="jBox-close" title="關閉"/>');
                    
    var jBoxContent = $('<div class="jBox-content" style="width:100%;height:' + height + 'px; overflow: hidden;margin:0px;padding:0px;"></div>');

    var QueryArea = $('<div id="jBox-QueryArea" style="width:98%;height:100%;border:1px solid #111111;padding:5px;" ></div>');
    var QueryLimit = $('<div id="jBox-QueryLimit" style="width:98%;padding:3px;margin:5 auto;" >請輸入在YouTube網站中的，點選<嵌入>所取得的程式字串：<br>影片內嵌程式碼：以(&lt;object ....)開頭字串<br><textarea style="width:95%;height:80px" id="youtucontent"/></div><div id="jBox-QueryBtn" style="text-align:center;width:100%;"><input type="button" value="&nbsp;分&nbsp;析&nbsp;" id="btn_go_youtube_query" class="jobx_btn"/><input type="button" value="&nbsp;取&nbsp;消&nbsp;" id="btn_go_close_query" class="jobx_btn"/><input type="button" value="&nbsp;帶&nbsp;入&nbsp;" id="btn_go_get_query" class="jobx_btn" disabled="true"/></div>');
    var QueryList = $('<div id="jBox-QueryList" style="margin:5 auto;padding:5px;width:98%;"><div id="actionmessage" style="font-size:12px;color:red;"></div></div>');
    QueryLimit.appendTo(QueryArea);
    QueryList.appendTo(QueryArea);

    var ShowArea = $('');
    QueryArea.appendTo(jBoxContent);
    //ShowArea.appendTo(jBoxContent);

    jBoxWraper.close = function() {
      jBoxWraper.css("visibility", "hidden");
      mask.hide();
    }
    var youtubeurl="";
    jBoxWraper.open = function() {
      if (config.objid==null){
        alert("設定錯誤!!");
        return false;
      }
      jBoxWraper.css("visibility", "visible");
      jBoxWraper.cover();
      mask.show();
      $("#btn_go_youtube_query").click(function() {
        var gg = _parseyoutube();
        if (gg=="error" || gg==""){
          youtubeurl="";
          $("#btn_go_get_query").attr("disabled",true);
          $("#__youtube_select").find("#actionmessage").html("抱歉,您輸入的字串無法分析!!");
        }else{
          youtubeurl = gg;
          $("#btn_go_get_query").attr("disabled",false);
          $("#__youtube_select").find("#actionmessage").html("此影片的網址為：<br>"+gg);
        }
      });
      $("#btn_go_get_query").click(function() {
         $("#"+config.objid).val(youtubeurl);
         $("#"+config.objid).blur();
         jBoxWraper.close();
      });
      $("#btn_go_close_query").click(function() {
        jBoxWraper.close();
      });
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
    jBoxWraper.appendTo(__youtube_select);
    parent.append(__youtube_select);
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
    var _parseyoutube = function (){
      try{
        var youtustr = $("#__youtube_select").find("#youtucontent").val();
        var finds = "<param name=\"movie\" value=\"";
        var ss = youtustr.indexOf(finds);
        youtustr = youtustr.substring(ss,youtustr.length);
        var find2s = "\"></param>";
        var ss2= youtustr.indexOf(find2s);
        youtustr = youtustr.substring(0,ss2);
        youtustr = youtustr.substring(finds.length,youtustr.length);
        return youtustr;
      }catch(ee){
        return "error";
      }
    }
})(jQuery);
//]