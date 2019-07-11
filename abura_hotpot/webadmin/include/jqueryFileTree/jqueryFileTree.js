if(jQuery) (function($){
  $.extend($.fn, {
    fileTree: function(o, h) {
      // Defaults
      if( !o ) var o = {};
      if( o.root == undefined ) o.root = '/';
      if( o.script == undefined ) o.script = 'jqueryFileTree.php';
      if( o.folderEvent == undefined ) o.folderEvent = 'click';
      if( o.expandSpeed == undefined ) o.expandSpeed= 500;
      if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
      if( o.expandEasing == undefined ) o.expandEasing = null;
      if( o.collapseEasing == undefined ) o.collapseEasing = null;
      if( o.multiFolder == undefined ) o.multiFolder = false;
      if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
      if( o.fixwidth == undefined ) o.fixwidth = 80;
      if( o.fixheight == undefined ) o.fixheight = 80;
      if( o.showwidth == undefined ) o.showwidth = 80;
      if( o.showheight == undefined ) o.showheight = 80;
      if( o.gdir == undefined ) o.gdir = null;
      var rootobj = $(this);
      
      $(this).each( function() {
        var fixwidth = o.fixwidth;
        var fixheight = o.fixheight ;
        var showwidth = o.showwidth;
        var showheight = o.showheight;
        var data = null;
        if (showwidth==0){
          showwidth = 80;
        }
        if (showheight==0){
          showheight = 80;
        }
        if (fixwidth==0){
          fixwidth = 80;
        }
        if (fixheight==0){
          fixheight = 80;
        }
        var imagedatajson = null;
        var nowshowobj = null;
        var totalpage = 0;
        var totalnum = 0;
        var page=1;
        var reLoadList = function(gdir,pagesize,page,filterkey) {
          $("#jBox-tabs-browser__").hide();
          $("#jBox-tabs-browser__message").show();
          $.getJSON(o.listurl+"?gdir="+gdir +"&pagesize=" + pagesize +"&page=" + page +"&filterkey=" + filterkey +"&m="+Math.random(),"", function(json){
            $("#jBox_now_dir").html("讀取["+gdir+"]中...");
            $("#photo-list").find("ul>li").remove();

            imagedatajson = json.images;
            totalnum = json.num;
            totalpage = json.totalpages;
            page = json.page;

            if (imagedatajson){
              for(var i=0;i < json.images.length;i++){
                var ff = json.images[i];
                if (ff.name.indexOf(".swf")!=-1){
                  ff.thumbfwidth = fixwidth;
                  ff.thumbfheight = fixheight;
                  $("#photo-list").find("ul").append("<li><div data-idx='"+i+"' style='float:left;width:"+showwidth+"px;height:"+showheight+"px;' class='__blockimg' title='"+ff.name+"'>"+ff.name+'<br><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'+showwidth+'" height="'+showheight+'">'+
                  '<param name="movie" value="'+ff.url+'">'+
                  '<param name="quality" value="high">'+
                  '<param name="wmode" value="transparent">'+
                  '<embed src="'+ff.url+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+showwidth+'" height="'+(showheight-20)+'"></embed></object></div></li>');
                }else if (ff.name.indexOf(".jpg")!=-1 || ff.name.indexOf(".png")!=-1 || ff.name.indexOf(".gif")!=-1 || ff.name.indexOf(".bmp")!=-1   ){
                  var ff = formatData(ff);
                  $("#photo-list").find("ul").append("<li><div data-idx='"+i+"' style='float:left;width:"+showwidth+"px;' class='__blockimg' title='"+ff.leble+"'><div style='height:"+(showheight)+"px;'><img src='"+ff.url+"' width='"+ff.thumbwidth+"' height='"+ff.thumbheight+"' data-tt='"+ff.thumbtop+"' style='padding-top:"+ff.thumbtop+";left:"+ff.thumbleft+";' title='"+ff.leble+"'/></div><div style='font-size:10px;overflow:hidden;'>"+ff.name+"</div></div></li>");
                }else if (ff.name.indexOf(".svg")!=-1 ){
$("#photo-list").find("ul").append("<li><div data-idx='"+i+"' style='float:left;width:"+showwidth+"px;' class='__blockimg' title='"+ff.leble+"'><div style='height:"+(showheight)+"px;'><img src='"+ff.url+"'  data-tt='"+ff.thumbtop+"' style='padding-top:"+ff.thumbtop+";left:"+ff.thumbleft+";' title='"+ff.leble+"'/></div><div style='font-size:10px;overflow:hidden;'>"+ff.name+"</div></div></li>");
                }else{

                  var ff = formatData2(ff);
                  $("#photo-list").find("ul").append("<li><div data-idx='"+i+"' style='float:left;width:"+showwidth+"px;' class='__blockimg' title='"+ff.leble+"'><div style='height:"+(showheight)+"px;'><img src='textfile.png' style='padding-top:16px;left:16px;'/></div><div style='font-size:10px;overflow:hidden;'>"+ff.name+"</div></div></li>");
                }
                imagedatajson[i]=ff;
              }
            }
            //bind事件
            $(".__blockimg").hover(function() {
              $(this).addClass("__blockimg-over");
            },function(){
              $(this).removeClass("__blockimg-over");
            });
            $(".__blockimg").click(function() {
              $(".__blockimg-selected").removeClass("__blockimg-selected");
              $(this).addClass("__blockimg-selected");
              $("#jBox_now_fileinfo").html("目前選中檔案："+$(this).attr("title")+"");
              var idx = parseInt($(this).attr("data-idx"));
              data = imagedatajson[idx];
            });
            $(".__blockimg").dblclick(function() {
              $(".__blockimg-selected").removeClass("__blockimg-selected");
              $(this).addClass("__blockimg-selected");
              $("#jBox_now_fileinfo").html("目前選中檔案："+$(this).attr("title")+"");
              var idx = parseInt($(this).attr("data-idx"));
              data = imagedatajson[idx];
              doCallback();
            });
            $("#btn_clickselectimg").click(function(){
              if (data != null){
                doCallback();
              }else{
                //warning_msg("您尚未選取檔案!!");
              }
            });

            //刪除檔案
            $("#btn_clickdeleteimg").click(function(){
              if (data!=null){
                hiConfirm("您確認要刪除"+data.name+"!?", '確認訊息', function(r) {
                  if (r){
                    doDelImg();
                  }
                });
              }else{
                warning_msg("您尚未選取檔案!!");
              }
            });
            //建立新目錄
            $("#btn_clickcreatedir").click(function(){
              hiPrompt("請輸入新目錄名稱","","建立新目錄", function(r) {
                if( r && r !=""){
                  doCreateDIR(r);
                }
              });
            });
            //刪除當前目錄
            $("#btn_clickdeletedir").click(function(){
              var gdir = $("#jBox_now_info").attr("data-dir");
              if (gdir!=null && gdir !="/" && gdir !="" && gdir!= o.root){
                hiConfirm("您確認要刪除["+gdir+"]資料夾，裡面檔案將全部被刪除!?", '確認訊息', function(r){
                  if (r){
                    doDelDir();
                  }
                });
              }else{
                if (gdir == o.root){
                  warning_msg("根目錄不可以刪除!!");
                }else{
                  warning_msg("您尚未選取目錄!!");
                }
              }
            });
            $("#jBox_now_dir").html("目前目錄：["+(gdir==""?"/":gdir)+"]" + " (共:"+totalnum+"筆)");
            $("#jBox_now_info").attr("data-dir",gdir);
            //產生頁數選單
             var pagehtml='總頁數：' + totalpage + '頁，目前頁數：';
             pagehtml +='<select id="jBox_image_page">'
             for(var i=1;i<=totalpage;i++){
               if (page==i){
                 pagehtml +="<option value='"+i+"' selected>"+i+"</option>";
               }else{
                 pagehtml +="<option value='"+i+"'>"+i+"</option>";
               }
             }
             pagehtml +='</select>&nbsp;<input type="hidden" value="'+totalpage+'" id="jBox_image_totalpage">';
             pagehtml +=' <input type="button" value="go" id="btn_reload">';
             $("#jBox_page_list").html(pagehtml);
            $("#btn_reload").click(function() {
              reLoadImage();
            });
            $("#jBox-tabs-browser__message").hide();
            $("#jBox-tabs-browser__").show();
          });
        }
        var doCallback = function() {
          if (data != null){
            h(data);
          }
        };
        var doDelImg = function() {
          if (data!=null){
            //
            var gdir = $("#jBox_now_info").attr("data-dir");
            $.post(o.delurl,
              {"goaction":"run","filename":data.name,"gdir":gdir, "pdir":""},
              function(jj){
                if (jj.success=="true" || jj.success=="1"){
                  $("#jBox_now_fileinfo").html("目前選中檔案：");
                  data = null;
                  $("#btn_reload").click();
                }else{
                  warning_msg("刪除失敗"+jj.message+"!!");
                }
              },
              "json"
            );
          }
        };
        var doCreateDIR = function(dirname) {
          if (dirname!=null && dirname !=""){
            var gdir = $("#jBox_now_info").attr("data-dir");
            $.post(o.mkdirurl,
              {"goaction":"run","newdir":dirname,"gdir":gdir, "pdir":""},
              function(jj){
                if (jj.success=="true" || jj.success=="1"){
                  reLoadTree(gdir);
                }else{
                  warning_msg("建立資料夾失敗,"+jj.message+"!!");
                }
              },
              "json"
            );
          }
        };
        var reLoadTree = function(reloadname){
          reloadname = reloadname.toString();
          $("#jBox_now_fileinfo").html("目前選中檔案：");
          data = null;
          if (reloadname != o.root){
            var nowobj = $("#jBox-dirtree").find("a[rel="+reloadname+"]");
            nowobj.parent().find("ul").remove();
            showTree(nowobj.parent(),escape(reloadname))
            nowobj.parent().removeClass('collapsed').addClass('expanded');
          }else{
            rootobj.html("");
            showTree(rootobj, escape(o.root));
          }
        }
        function getParentRel(nowrel){
          var tmparr = nowrel.toString().split("\/");
          var parentrel = "";
          for(var i=0;i<tmparr.length-2;i++){
            parentrel += ((i!=0)?"/":"") + tmparr[i];
          }
          if (parentrel==""){
            parentrel = o.root;
          }else{
            parentrel +="/";
          }
          return parentrel;
        }
        var doDelDir = function() {
          var gdir = $("#jBox_now_info").attr("data-dir");
          if (gdir!=null && gdir !="/"){
            //
            $.post(o.delurl,
              {"goaction":"run","filename":gdir,"gdir":"", "pdir":""},
              function(jj){
                if (jj.success=="true" || jj.success=="1"){
                  $("#jBox_now_fileinfo").html("目前選中檔案：");
                  var parentrel = getParentRel(gdir);
                  reLoadTree(parentrel);
                }else{
                  warning_msg("刪除失敗"+jj.message+"!!");
                }
              },
              "json"
            );
          }
        };
        function reLoadImage(){
          $("#photo-list").find("ul>li").remove();
          $("#jBox_now_fileinfo").html("目前選中檔案：");
          data = null;
          var targetdir = o.root;
          if (nowshowobj !=null){
            targetdir = $(nowshowobj).find('A:first').attr("rel");
          }
          //載入圖片
          var pagesize = parseInt($("#jBox_image_pagesize").val());
          var page = parseInt($("#jBox_image_page").val());
          var totalpage = parseInt($("#jBox_image_totalpage").val());
          var filterkey = $("#jBox_filterkey").val();
          if (isNaN(totalpage) || totalpage <=0){
            totalpage = 1;
          }
          if (isNaN(pagesize) || pagesize <=0){
            pagesize = 50;
          }
          if (isNaN(page) || page <=0 || page>totalpage){
            page = 1;
          }
          reLoadList(targetdir,pagesize,page,filterkey);
        }
        var formatData = function(data) {
          data.leble = "檔名: " + data.name +
          ",像素: " + data.width + "px * " + data.height +
          "px,大小: " + ((data.size < 1024) ? data.size + " bytes"
          : (Math.round(((data.size * 10) / 1024)) / 10) + " KB");
          data.title = data.leble;

          var scalex = 1;
          var scaley = 1;
          if (data.width-showwidth >0 && ((data.width-showwidth) > (data.height-showheight))) {
            scalex = ( showwidth / data.width );
            scaley = scalex;
          }else if (data.height-showheight >0){
            scalex = ( showheight / data.height );
            scaley = scalex;
          }

          data.thumbwidth = data.width * scalex;
          data.thumbheight = data.height * scaley;

          data.thumbleft = (Math.round((showwidth - data.thumbwidth) / 2)) +2+ "px";
          data.thumbtop = (Math.round((showheight - data.thumbheight) / 2)) +2+ "px";
          data.thumbwidth = Math.round(data.thumbwidth) + "px";
          data.thumbheight = Math.round(data.thumbheight) + "px";

          scalex = 1;
          scaley = 1;

          if (data.width-fixwidth >0 && ((data.width-fixwidth) > (data.height-fixheight))) {
            scalex = ( fixwidth / data.width );
            scaley = scalex;
          }else if (data.height-fixheight >0){
            scalex = ( fixheight / data.height );
            scaley = scalex;
          }
          data.thumbfwidth = data.width * scalex;
          data.thumbfheight = data.height * scaley;

          data.thumbfleft = (Math.round((fixwidth - data.thumbfwidth) / 2)) + "px";
          data.thumbftop = (Math.round((fixheight - data.thumbfheight) / 2)) + "px";
          data.thumbfwidth = Math.round(data.thumbfwidth) + "px";
          data.thumbfheight = Math.round(data.thumbfheight) + "px";

          return data;
        };
        var formatData2 = function(data) {
          data.leble = "檔名: " + data.name +
          "大小: " + ((data.size < 1024) ? data.size + " bytes"
          : (Math.round(((data.size * 10) / 1024)) / 10) + " KB");
          data.title = data.leble;

          return data;
        };
        function showTree(c, t) {
          $(c).addClass('wait');
          $(".jqueryFileTree.start").remove();
          $.post(o.script, { dir: t }, function(data) {
            $(c).find('.start').html('');
            $(c).removeClass('wait').append(data);
            if( o.root == t ) {
              $(c).find('UL:hidden').show();
              nowshowobj = null;
            }else {
              nowshowobj = c;
              $("#jBox_now_info").attr("data-dir",t);
              $(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });
            }
            bindTree(c);
            if (o.gdir==null || o.gdir==""){
              reLoadList(t,20,1,"");
            }else{
              reLoadTree(o.gdir);
              o.gdir = null;
            }
          });
        }
        
        function bindTree(t) {
          $(t).find('LI A').bind(o.folderEvent, function() {
            if( $(this).parent().hasClass('directory') ) {
              if( $(this).parent().hasClass('collapsed') ) {
                // Expand
                if( !o.multiFolder ) {
                  $(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
                  $(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
                }
                $(this).parent().find('UL').remove(); // cleanup
                showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
                $(this).parent().removeClass('collapsed').addClass('expanded');
              } else {
                var tmprel = $(this).attr('rel').match( /.*\// );
                var parentrel = getParentRel(tmprel);
                reLoadTree(parentrel);
                // Collapse
                $(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
                $(this).parent().removeClass('expanded').addClass('collapsed');
              }
            } else {
              h($(this).attr('rel'));
            }
            return false;
          });
          // Prevent A from triggering the # on non-click events
          if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
        }
        // Loading message
        $(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
        // Get the initial file list
        showTree( $(this), escape(o.root) );
      });
    }
  });
  
})(jQuery);