/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.timestamp='2.5';
CKEDITOR.editorConfig = function( config ) {
    config.language = 'zh';
    config.extraPlugins='mybrowser,myflash,autogrow,tableresize';
    config.enterMode = CKEDITOR.ENTER_BR;
    config.allowedContent = true;
    config.forcePasteAsPlainText = false;
    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;
    config.removePlugins = 'resize';
	config.uiColor = '#dfdede';
    config.height = 400;
    //config.skin='Moono_blue';

// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
config.toolbar = [
    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline'] },
    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'] },
    { name: 'paragraph', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor', '-', 'Table' ] },
    '/',
    { name: 'styles', items: [ 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'tools', items: ['Maximize', '-', 'Source', '-', 'Undo', 'Redo', '-', 'mybrowser'] },
//    { name: 'others', items: [ '-', 'myflash' ] },
];
  config.font_names ='Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif;新細明體;標楷體;微軟正黑體' ; // 為加入額外字體  
};
//  config.protectedSource.push( /<i[\s\S]*?\>/g ); //allows beginning <i> tag
//  config.protectedSource.push( /<\/i[\s\S]*?\>/g ); //allows ending </i> tag
CKEDITOR.dtd.$removeEmpty.i = 0;