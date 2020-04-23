<?php 
function dropzone($key,$folder=""){
global $incSet,$table,$fullDir,$webfullDir,$mainid,$CFG,$baseRoot,$webRoot;
if(!$incSet || !$table || !$fullDir || !$webfullDir || !$mainid || !$CFG ) return false;
?>
<div class="load_spinner">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
</div>
<div class="fileResponse">
    <div class="fileFormat">圖片請上傳 .jpg , .png , .gif , 圖片大小請勿超過: <?=$CFG->imgsize_limit?> MB。大圖尺寸：<?=$incSet[$key]["set"]["bw"]?> px * <?=$incSet[$key]["set"]["bh"]?> px。<?
    if($uSet->maxFiles != 999) {
    ?>最多可上傳 <?=$incSet[$key]["maxFiles"]?>張。<?
    }
    ?></div>
    <div id="<?=$key?>" class="name-dropzone">
        <div class="info-fullDir act-hide"><?=$baseRoot.$folder?></div>
        <div class="info-webfullDir act-hide"><?=$webRoot.$folder?></div>
        <div class="info-m_width act-hide"><?=$incSet[$key]["bw"]?></div>
        <div class="info-m_height act-hide"><?=$incSet[$key]["bh"]?></div>
        <div class="info-s_width act-hide"><?=$incSet[$key]["sw"]?></div>
        <div class="info-s_height act-hide"><?=$incSet[$key]["sh"]?></div>
        
        <div id="previews_<?=$key?>" class="dropzone dropSort">
            <span id="dzDefault_<?=$key?>" class="dzTip">您可將要上傳的檔案拖曳至此視窗</span>
            <div class="fallback"></div>
          
            <?php
            $dbkey = array();
            $sql = "select * from $table where cateid=" . pSQLStr($mainid) . " and typid='$key' order by seq asc";
           // echo $sql;
            $qry = @sql_query($sql);
            while($row = @sql_fetch_array($qry)) {
                $filename = $row["filename"];
                $filesize = round(filesize($fullDir . $filename) / 1024 / 1024, 2); // MB
                list($img_width, $img_height) = @getimagesize($fullDir . $filename);
            ?>
            <div class="dz-preview dz-image-preview" id="_<?=str_replace(".", "", $filename)?>">

                <div class="dz-image"><img data-dz-thumbnail="" alt="<?=$filename?>" src="<?=$webfullDir .$folder. $filename?>"></div>
                <div class="dz-details">
                    <div class="dz-size"><span data-dz-size=""><strong><?=$filesize?></strong> MB</span></div>
                    <div class="dz-wh"><span data-dz-wh=""><?=$img_width?> px * <?=$img_height?> px</span></div>
                    <div class="dz-filename"><span data-dz-name=""><?=$filename?></span></div>
                    
                </div>
                <div class="sqs-action-overlay">
                    <?php if($incSet[$key]["action"]["edit"]) {?> 
                    <div class="button image-info fancybox.ajax" title="編輯" href="photo/fileEdit.php?mainid=<?=$mainid?>&filename=<?=urlencode($filename)?>"></div>
                    <?php }?>
                    <?php if($incSet[$key]["action"]["cropb"]) {?> 
                    <div class="button resize-image" title="裁切"></div>
                    <?php }?>
                    <?php if($incSet[$key]["action"]["crops"]) {?> 
                    <div class="button resize-image-s" title="小圖裁切"></div>
                    <?php }?>
                    <?php if($incSet[$key]["action"]["del"]) {?> 
                    <div class="button remove-image" title="刪除"></div>
                    <?php }?>
                </div>
                <input type="hidden" name="filename[]" value="<?=$filename?>" />
                <input type="hidden" name="seqs[]" value="<?=$row["seq"]?>" />
            </div>
            <?php } ?>
        </div>
    </div>                               
    <div id="clickable_<?=$key?>" class="dzUpBtn"><i></i><div>上傳檔案</div></div>
</div>
<?php }?>

<!-- @ STEP: 3, js-->
<script>
$(function(){    
    defalut_refresh();
    Dropzone.autoDiscover = false;
    

    
    
    $(window).load(function() {        
        //套用 Shapeshift----------------------------/
        if($(".dropSort .dz-preview").length>0){
            var dropSort = $(".dropSort").shapeshift({
                selector: "div.dz-preview",
                align: "left",
                paddingX: 20,
                paddingY: 20,
                gutterX: 20,
                gutterY: 20,
                minHeight: 180,
            });
        
            // @ 拖曳排序後, 觸發資料更新
            dropSort.on("ss-drop-complete", function() {
                index_refresh();
                defalut_refresh();
                //console.log('Hello World');
            });
        }
           
        //檔案刪除功能----------------------------/
        $(document).on('click', '.dz-preview .remove-image', function(){
            var delBtn = $(this);
            hiConfirm('<h3>您確認要刪除？</h3>', '確認對話', function(r) {
                if (!r) return false;                
                var filename = delBtn.parents(".dz-preview").find(".dz-filename span").html();
                $.post(
                    "photo/fileDelete.php",
                    {mainid:cfg_mainid, filename:filename},
                    function(html){
                        if (html==true){
                            delBtn.parents(".dz-preview").remove();
                            $(".dropSort").trigger("ss-rearrange");
                            index_refresh();
                            defalut_refresh();
                        }else{
                          //  hiAlert(html, '刪除失敗');
                        }
                    },"html"
                );
            });
        });   
        
        //@ 檔案編輯功能 (lightbox)----------------------------/
        $(".dz-preview .image-info").fancybox({
            fitToView    : false,
            padding: [0,0,0,0],
            width        : false,//'80%',
            height        : false,
            closeClick    : false,
            openEffect    : 'none',
            closeEffect    : 'none',
            helpers : {
                overlay : {
                    css : {
                        'background' : 'rgba(00, 00, 00, 0.05)'
                    }
                }
            }
        });

        // Save
        $(document).on('click', '.infoSave', function(){
            $.post(
                "photo/fileUpdate.php",
                $('form[name="infoForm"]').serialize(),
                function(html){},"html"
            );
            $.fancybox.close();
        });

        // Cancel
        $(document).on('click', '.infoCancel', function(){
            $.fancybox.close();
        });
               
        //@ 圖片剪裁功能 (popup)----------------------------/
        $(document).on('click', '.dz-preview .resize-image', function(){
            var previewTemp = $(this).parents(".dz-preview");
            var filename = previewTemp.find(".dz-filename span").html(); 
            
            var mobj = $(this).parents(".name-dropzone");
            var _rootpath =mobj.find(".info-fullDir").html();
            var _webpath =mobj.find(".info-webfullDir").html();
            var m_width  =mobj.find(".info-m_width").html();
            var m_height  =mobj.find(".info-m_height").html();
            thumb_Image(_rootpath   ,_webpath ,cfg_mainid+"/"  ,filename,  m_width  ,m_height  ,'N'  ,''   ,'thumb_image.php');       
        });
        $(document).on('click', '.dz-preview .resize-image-s', function(){
            var previewTemp = $(this).parents(".dz-preview");
            var filename = previewTemp.find(".dz-filename span").html();  
            
            var mobj = $(this).parents(".name-dropzone");
            var _rootpath =mobj.find(".info-fullDir").html();
            var _webpath =mobj.find(".info-webfullDir").html();
            var s_width  =mobj.find(".info-s_width").html();
            var s_height  =mobj.find(".info-s_height").html();
            thumb_Image(_rootpath   ,_webpath ,cfg_mainid+"/" ,filename,  s_width  ,s_height  ,'N'  ,''   ,'thumb_image_s.php');      
        });
        
        // 判斷瀏覽器是否支援: N 轉移, Y 顯示
        $(".load_spinner").addClass("fadeOut animated");
        setTimeout(function(){
            if($("body.dz-browser-not-supported").length>0){
                window.location.href = "image.php?id="+cfg_mainid;
            }else{
                $(".fileResponse").addClass("fadeIn animated");
            }
        }, 500);
           
    });
    
   
});
function newdropzone(ini){
    var obj= ini.name.toString();       
    window[obj] =  new Dropzone("div#"+ini.name, { // Make the whole body a dropzone
        url: "photo/fileUpload.php", // Set the url
        previewsContainer: "#previews_"+ini.name, // Define the container to display the previews
        clickable: "#clickable_"+ini.name+",#dzDefault_"+ini.name, // Define the element that should be used as click trigger to select files.
        maxFilesize: ini.maxFile, // in MB
        paramName: 'Filedata', // The name of the file param
        uploadMultiple: false, // send multiple files in one request
        acceptedFiles: 'image/*,',
        forceFallback: false, // fallback debug mode
        dictFallbackMessage: '', // 不使用fallback訊息
        init: function() {
            var $parent = $("div#"+ini.name);               
            this.on("sending", function(file, xhr, formData) {
                // Will send the filesize along with the file as POST data.
                formData.append("mainid", cfg_mainid);
                formData.append("typid", ini.name);
            });  
            this.on("addedfile", function(file) {
                //新增完成, 套用 Shapeshift----------------------------/
                console.log($parent);
                $parent.find(".dropSort").shapeshift({
                    selector: "div.dz-preview",
                    align: "left",
                    paddingX: 20,
                    paddingY: 20,
                    gutterX: 20,
                    gutterY: 20,
                    minHeight: 180,
                });
            });
            
            // Exceed the over maxFiles Event.
            this.on('maxfilesexceeded', function (file) {
                var previewTemplate = $(file.previewTemplate);
                previewTemplate.children('.dz-details').css('display', 'none');
                previewTemplate.children('.sqs-action-overlay').css('display', 'none');
                dropzone_delete(previewTemplate);
            });
            this.on('error', function (file) {
                var previewTemplate = $(file.previewTemplate);
                dropzone_delete(previewTemplate);
            });
            this.on("success", function (file, response) {
                try{
                    var responseJSON = JSON.parse(response);
                }catch(e){
                    var responseJSON = JSON.parse('{"status":"fail", "message":"上傳失敗"}');
                }
                var previewTemplate = $(file.previewTemplate);
                var _this = this;
                
                if (responseJSON.status === 'fail'){
                    if (!responseJSON.file){
                        //console.log(response);
                        previewTemplate.children('.dz-success-mark').css('display', 'none');
                        previewTemplate.children('.dz-error-mark').css('display', 'block');
                        previewTemplate.removeClass('dz-success').addClass('dz-error');
                        previewTemplate.find('.dz-error-message span').html(responseJSON.message);
                        previewTemplate.children('.dz-details').css('display', 'none');
                        previewTemplate.children('.sqs-action-overlay').css('display', 'none');
                    }                        
                    dropzone_delete(previewTemplate);  
                }else if(responseJSON.status === 'success'){
                    var filename = responseJSON.file_name;
                    var fildid = "_"+filename.replace(".", "");
                    previewTemplate.find(".dz-filename span").html(filename);
                    
                    // remove
                    if($("#"+fildid).length>0){
                        $("#"+fildid).remove();
                        setTimeout(function(){
                            $parent.find(".dropSort").trigger("ss-rearrange");
                        }, 500);
                    }
                    
                    // Add id for Relevance
                    previewTemplate.attr('id', fildid);
                    // 
                    // Add the button to the file preview element.
                    previewTemplate.find('.dz-wh span').html(responseJSON.width+' px * '+responseJSON.height+' px');
                                          
                    // 建立傳輸資料---------------/
                    // Add the button to the file preview element.
                    var seq_input = Dropzone.createElement('<input type="hidden" name="filename[]" value="'+file.name+'" />');
                    file.previewElement.appendChild(seq_input);
                    
                    // Add the button to the file preview element.
                    var seq_input = Dropzone.createElement('<input type="hidden" name="seqs[]" value="'+responseJSON.seq+'" />');
                    file.previewElement.appendChild(seq_input);
                    
                    //建立工具列---------------/
                    var creElement="<div class=\"sqs-action-overlay\">";
                    //edit
                    if(ini.action.edit) creElement+="<div class=\"button image-info fancybox.ajax\" title=\"編輯\" href=\"photo/fileEdit.php?mainid="+cfg_mainid+"&filename="+filename+"\"></div>";
                    //crop
                    if(ini.action.cropb) creElement+="<div class=\"button resize-image\" title=\"裁切\"></div>";
                    //crop-s
                    if(ini.action.crops) creElement+="<div class=\"button resize-image-s\" title=\"小圖裁切\"></div>";
                    //del
                    if(ini.action.del) creElement+="<div class=\"button remove-image\" title=\"刪除\"></div>";
                    creElement+="</div>";
                    var tip_overlay = Dropzone.createElement(creElement);
                    file.previewElement.appendChild(tip_overlay);
                }else{

                }
            });            
            this.on("complete", function (file, response) {
                index_refresh();
                defalut_refresh();
                
                // 修正排序跟上傳的衝突 (重複閃現的動畫)
                setTimeout(function(){
                    var previewTemplate = $(file.previewTemplate);
                    previewTemplate.addClass("dz-over");
                }, 2000);
            });
        }
    });        
}

//@ 公用函式: 刷新排序資料庫----------------------------/
function index_refresh(){
    $.post(
        "photo/fileSeq.php",
        $('form').serialize()+"&mainid="+cfg_mainid,
        function(html){},"html"
    );
}
  
//@ 公用函式: 顯示預設的提示訊息----------------------------/
function defalut_refresh(){
    if($(".dropSort .dz-preview").length<=0){
        $(".dropzone").addClass("dzTipShow");
    }else{
        $(".dropzone").removeClass("dzTipShow");
    }
}

// @ Drop刪除: 提示2秒後消失----------------------------/
function dropzone_delete(_item){
    setTimeout(function(){
        _item.fadeOut(1000);
        setTimeout(function(){
            _item.remove();
            $(".dropSort").trigger("ss-rearrange");
        }, 1000);
    }, 2000);
}

// 裁切----------------------------/
function act_thumb(filename,baseRoot,webRoot,m_width,m_height){
    thumb_Image(baseRoot   ,webRoot   ,cfg_mainid  ,filename,  m_width  ,m_height  ,'N'  ,''   ,'thumb_image.php');
}

function act_thumb_s(filename){
    thumb_Image(baseRoot   ,webRoot   ,cfg_mainid  ,filename,  s_width  ,s_height  ,'N'  ,''   ,'thumb_image_s.php');
}

function loadimages(){
    location.href = location.href;
}

$(document).ready(function(){
    $(window).load(function() {
        $("#mtabs").tabs();   
    });
});
</script>