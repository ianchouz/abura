<?php
include_once("../../applib.php");

$path = pgParam("path",'');
$rootpath = pgParam("rootpath",'');
$webpath = pgParam("webpath",'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php require('../../base_header_lib.php');?>
    <script src="<?=$CFG->f_admin?>js/zxxFile.js"></script>
    <style>
    #uploadForm {
      font-family: 'Lucida Grande',Verdana;
    }
    .upload_box {
      width: 800px;
      margin: 1em auto;
    }
    .upload_main {
      border-width: 1px 1px 2px;
      border-style: solid;
      border-color: #ccc #ccc #ddd;
      background-color: #fbfbfb;
    }
    .upload_choose {
      padding: 1em;
    }
    .upload_drag_area {
      display: inline-block;
      width: 60%;
      padding: 4em 0;
      margin-left: .5em;
      border: 1px dashed #ddd;
      background: #fff ;
      color: #999;
      text-align: center;
      vertical-align: middle;
    }
    .upload_image {
      max-height: 250px;
      max-width: 250px;
      padding: 5px;
    }

    .upload_append_list{
      margin:10px;
      float:left;
      font-size:12px;
      font-family: 'Lucida Grande',Verdana;
    }
    .upload_preview{
      position:relative;
    }
    .upload_inf{
      height:200px;
      overflow-y:auto;
      font-size:12px;
      margin-top:10px;
    }
    .upload_submit{
      margin:20px auto;
    }
    </style>
  </head>    
  
    <div style="margin:10px;">
      此上傳方法僅提供給有支援HTML5的瀏覽器。若無法正常使用，請選擇其他上傳方法或更換新版的Chorme、Firefox等主流瀏覽器
      <br>準備上傳至：<?=$path?>
    </div>
    <div id="all_form">
  <form id="uploadForm" action="image_html5_action.php" method="post" enctype="multipart/form-data">

    <div class="upload_box">
      <div class="upload_main">
        <div class="upload_choose">
          <input id="fileImage" type="file" size="30" name="fileselect[]" multiple="" class="">
          <span id="fileDragArea" class="upload_drag_area">或將圖片拖拉到此</span>
        </div>
        <div class="upload_submit">
          <button type="button" id="fileSubmit" class="upload_submit_btn" style="display: none;">確認上傳圖片</button>
        </div>
        <div id="preview" class="upload_preview">
        </div>
        <div style="clear:both;"></div>
      </div>

      <div id="uploadInf" class="upload_inf"></div>
    </div>
  </form>
  </div>
  <script type="text/javascript">
	if (typeof window.FileReader === 'undefined') {
	  $("#uploadForm").hide();
	  $("#all_form").html('<div style="text-align:center;color:red;">抱歉，您的瀏覽器不支援HTML5，請選擇其他上傳方法或更換新版的Chorme、Firefox等主流瀏覽器</div>');
	}
	var maxsize = 10 * 1024 * 1024;
  var params = {
    rootpath: '<?=$rootpath?>',
    webpath:  '<?=$webpath?>',
    fileDir:  '<?=$path?>',
  	fileInput: $("#fileImage").get(0),
  	dragDrop: $("#fileDragArea").get(0),
  	upButton: $("#fileSubmit").get(0),
  	url: $("#uploadForm").attr("action"),
  	filter: function(files) {
  		var arrFiles = [];
  		for (var i = 0, file; file = files[i]; i++) {
  			if (file.type.indexOf("image") == 0 || (!file.type && /\.(?:jpg|png|gif|svg)$/.test(file.name) /* for IE10 */)) {
  				if (file.size >= maxsize) {
  					alert('您這張"'+ file.name +'"圖片大小過大，應小於10MB');
  				} else {
  					arrFiles.push(file);
  				}
  			} else {
  				alert('文件"' + file.name + '"不是圖片。');
  			}
  		}
  		return arrFiles;
  	},
  	onSelect: function(files) {
  		var html = '', i = 0;
  		$("#preview").html('<div class="upload_loading"></div>');
  		var funAppendImage = function() {
  			file = files[i];
  			if (file) {
  				var reader = new FileReader()
  				reader.onload = function(e) {
  					html = html + '<div id="uploadList_'+ i +'" class="upload_append_list"><p><strong>' + file.name + '</strong>'+
  						'<a href="javascript:" class="upload_delete" title="删除" data-index="'+ i +'">删除</a><br />' +
  						'<img id="uploadImage_' + i + '" src="' + e.target.result + '" class="upload_image" /></p>'+
  						'<span id="uploadProgress_' + i + '" class="upload_progress"></span>' +
  					'</div>';
  					i++;
  					funAppendImage();
  				}
  				reader.readAsDataURL(file);
  			} else {
  				$("#preview").html(html);
  				if (html) {
  					//删除方法
  					$(".upload_delete").click(function() {
  						ZXXFILE.funDeleteFile(files[parseInt($(this).attr("data-index"))]);
  						return false;
  					});
  					//提交按钮显示
  					$("#fileSubmit").show();
  				} else {
  					//提交按钮隐藏
  					$("#fileSubmit").hide();
  				}
  			}
  		};
  		funAppendImage();
  	},
  	onDelete: function(file) {
  		$("#uploadList_" + file.index).fadeOut();
  	},
  	onDragOver: function() {
  		$(this).addClass("upload_drag_hover");
  	},
  	onDragLeave: function() {
  		$(this).removeClass("upload_drag_hover");
  	},
  	onProgress: function(file, loaded, total) {
  		var eleProgress = $("#uploadProgress_" + file.index), percent = (loaded / total * 100).toFixed(2) + '%';
  		eleProgress.show().html(percent);
  	},
  	onSuccess: function(file, response) {
  		$("#uploadInf").append("<p>上傳完成，" + response + "</p>");
  	},
  	onFailure: function(file) {
  		$("#uploadInf").append("<p>圖片" + file.name + "上傳失敗!!</p>");
  		$("#uploadImage_" + file.index).css("opacity", 0.2);
  	},
  	onComplete: function() {
  		//提交按钮隐藏
  		$("#fileSubmit").hide();
  		//file控件value置空
  		$("#fileImage").val("");
  		$("#uploadInf").append("<p>檔案已全部上傳，可繼續新增上傳。</p>");
  		$('#uploadInf').scrollTop($('#uploadInf')[0].scrollHeight);
  	}
  };
  ZXXFILE = $.extend(ZXXFILE, params);
  ZXXFILE.init();
  </script>
</body>
</html>