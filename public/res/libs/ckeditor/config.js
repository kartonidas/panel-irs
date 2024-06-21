/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    config.language = 'pl';
    config.enterMode = CKEDITOR.ENTER_BR;

    config.toolbar_Basic = [
        ['Source'],['SelectAll','RemoveFormat'],
        ['Undo','Redo','-','Bold','Italic','Underline','StrikeThrough'],
        ['Cut','Copy','Paste','PasteText','PasteWord'],
        ['Link','Unlink'],
        ['OrderedList','UnorderedList','-','Outdent','Indent'],
        ['NumberedList', 'BulletedList'],
        ['Table','Image','Flash','Rule','SpecialChar']
    ];

    config.toolbar_Full = [
        ['Source'],['SelectAll','RemoveFormat'],
        ['Undo','Redo','-','Bold','Italic','Underline','StrikeThrough'],
        ['Cut','Copy','Paste','PasteText','PasteWord'],
        ['Link','Unlink','Anchor'],
        ['Style'],
        ['Table','Image','Flash','Rule','SpecialChar'],
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        '/',
        ['OrderedList','UnorderedList','-','Outdent','Indent'],
        ['NumberedList', 'BulletedList'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['TextColor', 'Styles','Format','FontSize']
    ];

    config.width = '100%';
    config.resize_minWidth = 735;
    config.resize_maxWidth = 900;
    config.allowedContent = true;
    config.forcePasteAsPlainText = true;
    
    config.forcePasteAsPlainText = true;
    config.filebrowserBrowseUrl = '/res/libs/kcfinder/browse.php?type=files&cms=laraveladministrator';
    config.filebrowserImageBrowseUrl = '/res/libs/kcfinder/browse.php?type=images&cms=laraveladministrator';
};

CKEDITOR.dtd.$removeEmpty.span = false;
CKEDITOR.dtd.$removeEmpty.i = false;
