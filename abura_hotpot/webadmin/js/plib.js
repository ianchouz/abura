String.prototype.Blength = function() {
    var arr = this.match(/[^\x00-\xff]/ig);
    return arr == null ? this.length : this.length + arr.length;
}

function thumb_Image(_rootpath, _webpath, _path, _file_name, nw, nh, newfile, _back, target) {
    //v 2.1.5
    $.fancybox({
        type: 'iframe',
        href: fulladmin + 'include/image/' + target + '?fixroot=' + _rootpath + '&fixweb=' + _webpath + '&path=' + _path + '&file_name=' + _file_name + '&nw=' + nw + '&nh=' + nh + '&newfile=' + newfile + '&back=' + _back,
        fitToView: false,
        width: '85%',
        height: '85%',
        minWidth: 900,
        minHeight: 600,
        padding: [0, 0, 0, 0],
        autoSize: false,
        closeClick: false,
        openEffect: 'none',
        closeEffect: 'none',
        onCancel: function(current, previous) {
            if (typeof loadimages == 'function') {
                loadimages();
            }
        },
        helpers: {
            overlay: {
                css: {
                    'background': 'rgba(119, 119, 119, 0.7)'
                }
            }
        }
    });
}

function checkMaxlength(key) {
    try {
        if (document.getElementById(key) && document.getElementById(key).getAttribute("data-maxlength")) {
            var maxlength = parseInt(document.getElementById(key).getAttribute("data-maxlength"));
            if (document.getElementById(key).value.Blength() > maxlength) {
                return false;
            }
            return true;
        }
        return false;
    } catch (ee) {
        return false;
    }
}

function setSel(id) {
    if (!$("#" + id).attr('checked')) {
        $("#" + id).attr('checked', true);
    }
}

function isNumber(val) {
    var re = /^\d+$/;
    return re.test(val);
}

function isPhone(fData) {
    var str;
    var fDatastr = "";
    if (fData == null || fData == "")
        return true
    for (var i = 0; i < fData.length; i++) {
        str = fData.substring(i, i + 1);
        if (str != "(" && str != ")" && str != "（" && str != "）" && str != "+" && str != "-" && str != "#" && str != " ") fDatastr = fDatastr + str;
    }
    if (isNaN(fDatastr))
        return false
    return true
}

function isEmail(email) {
    reEmail = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
    if (email == "") return true;
    myArray = email.split(",");
    for (var i = 0; i < myArray.length; i++) {
        if (!reEmail.test(myArray[i])) {
            return false;
        }
    }
    return true;
}

function wait_msg(text) {
    try {
        $.alerts.iswait = true;
        hiAlert(text, '系統訊息');
    } catch (ee) {
        alert(text);
    }
    return true;
}

function warning_msg(text) {
    try {
        $.alerts.iswait = false;
        hiAlert(text, '警告');
    } catch (ee) {
        alert(text);
    }
    return true;
}

function info_msg(text) {
    try {
        $.alerts.iswait = false;
        hiAlert(text, '訊息');
    } catch (ee) {
        alert(text);
    }
}

function transPageInfo(text, url) {
    if (text == "") {
        wait_msg("轉換頁面中,請稍等...");
        self.location.href = url;
    }
    hiAlert(text, '訊息', function() {
        wait_msg("轉換頁面中,請稍等...");
    });
}

function transPageInfoForm(text, formid) {
    var f = document.getElementById(formid);
    if (text == "") {
        if (f) {
            wait_msg("轉換頁面中,請稍等...");
            f.submit();
        } else {
            warning_msg("無法找到表單");
        }
    } else {
        wait_msg(text + "<br>轉換頁面中,請稍等...");
        setTimeout(function() {
            f.submit();
        }, 200);
    }
}

function runIfElse(chkname) {
    var checkItem = document.getElementsByName(chkname);
    for (var i = 0; i < checkItem.length; i++) {
        checkItem[i].checked = !checkItem[i].checked;
    }
}

function runSelAllElse(chkname) {
    var checkItem = document.getElementsByName(chkname);
    for (var i = 0; i < checkItem.length; i++) {
        checkItem[i].checked = false;
    }
}

function runSelAll(chkname) {
    var checkItem = document.getElementsByName(chkname);
    for (var i = 0; i < checkItem.length; i++) {
        checkItem[i].checked = true;
    }
}

function getBoxVals(chkname, plus) {
    var checkItem = document.getElementsByName(chkname);
    var vlas = "";
    if (checkItem.length) {
        for (var i = 0; i < checkItem.length; i++) {
            if (checkItem[i].checked) {
                vlas += (vlas == "") ? (checkItem[i].value) : (plus + checkItem[i].value);
            }
        }
        return vlas;
    } else {
        return checkItem.value;
    }
}
String.prototype.Trim = function() {
        return this.replace(/(^\s*)|(\s*$)/g, "");
    }
    /*圖片選擇*/
var nowfocus = "";

function openImageBrowser(prefix, show_w, show_h, resizew, resizeh, fixpath) {
    nowfocus = prefix;
    var swfu = new jSFworld({
        title: "\u5716\u7247\u700F\u89BD",
        showwidth: resizew,
        showheight: resizeh,
        asigndir: fixpath,
        fixwidth: show_w,
        fixheight: show_h,
        callback: "setImageDetailsB"
    });
}

function setImageDetailsB(data) {
    if (data && data != null) {
        var hh = '<img src="' + data.url + '" title="' + data.title + '" style="top:' + data.thumbftop + '; left:' + data.thumbfleft + '; width:' + data.thumbfwidth + '; height:' + data.thumbfheight + ';">';
        $('#' + nowfocus + 'imagearea').html(hh);
        $('#' + nowfocus + 'path').val((data.topdir == "" ? "" : data.topdir) + data.name);
        $('#' + nowfocus).val(data.name);
        $('#' + nowfocus + 'owidth').text(data.width);
        $('#' + nowfocus + 'oheight').text(data.height);
        $('#' + nowfocus + 'opath').text(data.name);
        $('#' + nowfocus + 'width').val(data.width);
        $('#' + nowfocus + 'height').val(data.height);
    } else {
        var hh = '<img src="'+fulladmin+'images/unknow.png">';
        $('#' + nowfocus + 'imagearea').html(hh);
        $('#' + nowfocus + 'path').val("");
        $('#' + nowfocus).val("");
        $('#' + nowfocus + 'opath').text('');
        $('#' + nowfocus + 'owidth').text('0');
        $('#' + nowfocus + 'oheight').text('0');
        $('#' + nowfocus + 'width').val('0');
        $('#' + nowfocus + 'height').val('0');
    }
}

function controlButton(key, act) {
    if ($('#' + key + '-btn')) {
        $('#' + key + '-btn').attr("disabled", act);
    }
    nowfocus = key;
    if (act) {
        setImageDetailsB(null);
    }
}

function isDate(val) {
    try {
        val = val.replace(/-/g, '\/');
        var dd = new Date(Date.parse(val));
        return true;
    } catch (ee) {
        return false;
    }
}

function compareDate(val1, val2) {
    val1 = val1.replace(/-/g, '\/');
    val2 = val2.replace(/-/g, '\/');
    return new Date(Date.parse(val1)) <= new Date(Date.parse(val2));
}
/*日期選擇*/
function datasel(id) {
    $("#" + id).datepicker({
        showOn: 'button',
        buttonImage: fulladmin + 'images/calendar.gif',
        dateFormat: 'yy-mm-dd',
        showOn: 'both',
        buttonText: '...',
        changeYear: true,
        buttonImageOnly: true,
        showButtonPanel: true
    });
}

function datasel2(id) {
    $(id).datepicker({
        showOn: 'button',
        buttonImage: fulladmin + 'images/calendar.gif',
        dateFormat: 'yy-mm-dd',
        showOn: 'both',
        buttonText: '...',
        changeYear: true,
        buttonImageOnly: true,
        showButtonPanel: true
    });
}
/*選擇圖片與FLASH*/
function openMultiBrowser(prefix, show_w, show_h, resizew, resizeh, fixpath) {
    nowfocus = prefix;
    var swfu = new jSFmulitworld({
        title: "\u5716\u7247\u8207FLASH\u9078\u64C7",
        showwidth: resizew,
        showheight: resizeh,
        asigndir: fixpath,
        fixwidth: show_w,
        fixheight: show_h,
        callback: "setMultiDetails"
    });
}

function setMultiDetails(data) {
    if (data && data != null) {
        if (data.name.indexOf(".swf") != -1) {
            var hh = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="' + data.thumbfwidth + '" height="' + data.thumbfheight + '"><param name="movie" value="' + data.url + '"><param name="quality" value="high"><param name="wmode" value="transparent"><embed src="' + data.url + '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="' + data.thumbfwidth + '" height="' + (data.thumbfheight) + '"></embed></object>';
            $('#' + nowfocus + 'imagearea').html(hh);
            $('#' + nowfocus + 'path').val((data.topdir == "" ? "" : data.topdir) + data.name);
            $('#' + nowfocus).val(data.name);
            $('#' + nowfocus + 'owidth').text('');
            $('#' + nowfocus + 'oheight').text('');
            $('#' + nowfocus + 'width').val('');
            $('#' + nowfocus + 'height').val('');
        } else {

            var hh = '<img src="' + data.url + '" title="' + data.title + '" style="top:' + data.thumbftop + '; left:' + data.thumbfleft + '; width:' + data.thumbfwidth + '; height:' + data.thumbfheight + ';">';
            $('#' + nowfocus + 'imagearea').html(hh);
            $('#' + nowfocus + 'path').val((data.topdir == "" ? "" : data.topdir) + data.name);
            $('#' + nowfocus).val(data.name);
            $('#' + nowfocus + 'owidth').text(data.width);
            $('#' + nowfocus + 'oheight').text(data.height);
            $('#' + nowfocus + 'opath').text(data.name);
            $('#' + nowfocus + 'width').val(data.width);
            $('#' + nowfocus + 'height').val(data.height);
        }
    } else {

        var hh = '<img src="' + webroot + 'images/unknow.png" title="">';
        $('#' + nowfocus + 'imagearea').html(hh);
        $('#' + nowfocus + 'path').val("");
        $('#' + nowfocus).val("");
        $('#' + nowfocus + 'owidth').text('0');
        $('#' + nowfocus + 'oheight').text('0');
        $('#' + nowfocus + 'width').val('0');
        $('#' + nowfocus + 'height').val('0');
    }
}

function changeIMG(fix) {
    nowfocus = fix;
    $("#" + fix + "useimageY").attr("checked", true);
    var fixdir = $("#" + fix + "fixdir").val();
    var w = $("#" + fix + "width").val();
    var h = $("#" + fix + "height").val();
    var noneHeight = $("#" + fix + "noneHeight").val();
    var noneWidth = $("#" + fix + "noneWidth").val();
    var openurl = fulladmin + "include/image/choice_single.php?pre=" + fix + "&path=" + fixdir + "&nw=" + w + "&nh=" + h + "&noneHeight=" + noneHeight + "&noneWidth=" + noneWidth;
    winopen(800, 0, openurl, "選擇圖片");
}

function uploadIMG(fix) {
    nowfocus = fix;
    $("#" + fix + "useimageY").attr("checked", true);
    var fixdir = $("#" + fix + "fixdir").val();
    var w = $("#" + fix + "width").val();
    var h = $("#" + fix + "height").val();
    var noneHeight = $("#" + fix + "noneHeight").val();
    var noneWidth = $("#" + fix + "noneWidth").val();
    var openurl = fulladmin + "include/image/newUpload.php?pre=" + fix + "&path=" + fixdir + "&nw=" + w + "&nh=" + h + "&noneHeight=" + noneHeight + "&noneWidth=" + noneWidth;
    winopen(800, 0, openurl, "上傳新圖");
    //var winopenobj = window.open ("include/image/newUpload.php?pre="+fix+"&path="+fixdir+"&nw="+w+"&nh="+h, "上傳新圖", "width=800, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no");
    //reWindow($(winopenobj));
}

function changeFLASH(fix) {
    nowfocus = fix;
    $("#" + fix + "useFLASHY").attr("checked", true);
    var fixdir = $("#" + fix + "fixdir").val();
    var w = $("#" + fix + "width").val();
    var h = $("#" + fix + "height").val();
    var noneHeight = $("#" + fix + "noneHeight").val();
    var openurl = fulladmin + "include/flash/choice_single.php?pre=" + fix + "&path=" + fixdir + "&nw=" + w + "&nh=" + h + "&noneHeight=" + noneHeight;
    winopen(800, 0, openurl, "選擇FLASH");
}

function uploadFLASH(fix) {
    nowfocus = fix;
    $("#" + fix + "useFLASHY").attr("checked", true);
    var fixdir = $("#" + fix + "fixdir").val();
    var w = $("#" + fix + "width").val();
    var h = $("#" + fix + "height").val();
    var noneHeight = $("#" + fix + "noneHeight").val();
    var openurl = fulladmin + "include/flash/newUpload.php?pre=" + fix + "&path=" + fixdir + "&nw=" + w + "&nh=" + h + "&noneHeight=" + noneHeight;
    winopen(800, 0, openurl, "上傳新FLASH");
}
var newwin = null;

function winopen(width, height, url, name) {
    newwin = window.open(url, name, 'toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
    if (document && document.all) {
        newwin.moveTo(0, 0);
        newwin.resizeTo(width == 0 ? screen.width : width, height == 0 ? screen.height - 50 : height);
        newwin.focus();
    }
}

function reWindow(win) {
    var pos = _center(win.width(), win.height());
    var left = pos[0];
    var top = pos[1];
    if (top < 0) {
        top = 5;
    }
    if (left < 0) {
        left = 5;
    }
}
var _center = function(w, h) {
    _doc = jQuery(document);
    _win = jQuery(window);
    _docHeight = _doc.height();
    _winHeight = _win.height();
    _winWidth = _win.width();
    return [(_docHeight > _winHeight) ? _winWidth / 2 - w / 2 - 18 : _winWidth / 2 - w / 2,
        _doc.scrollTop() + _winHeight / 2 - h / 2
    ];
}

function go_download(vv) {
    if (vv != "") {
        window.open('../include/download.php?dl=' + vv, '下載', config = 'height=100,width=300')
    }
}

function go_download_stand(vv) {
    if (vv != "") {
        window.open('../../include/download.php?dl=' + vv, '下載', config = 'height=100,width=300')
    }
}

function GetSubCate(type, serial) {
    var data = "";
    $.ajax({
        url: './process.php?method=GetSubCate,' + type + ',' + serial,
        async: false,
        success: function(response) {
            if (response != "error") {
                if (response) {
                    var tmp = response.split("||");
                    m = tmp.length;
                    for (var i = 0; i < m; i++) {
                        var txt = tmp[i].split(",");
                        data += "<option value=\"" + txt[0] + "\">" + txt[1] + "</option>";
                    }
                }
                return data;
            } else {
                alert("發生錯誤");
            }
        }
    });
    return data;
}


function isBrowserSupported() {
    var capableBrowser, regex, _i, _len, _ref;
    capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
        if (!("classList" in document.createElement("a"))) {
            capableBrowser = false;
        } else {
            _ref = [/opera.*Macintosh.*version\/12/i];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                regex = _ref[_i];
                if (regex.test(navigator.userAgent)) {
                    capableBrowser = false;
                    continue;
                }
            }
        }
    } else {
        capableBrowser = false;
    }
    return capableBrowser;
};