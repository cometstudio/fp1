/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		//{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		//{ name: 'others' },
		'/',
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;h4;h5;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.language = 'ru';

	config.autoGrow_maxHeight = '100%';

	config.allowedContent = true;

    var CKLocation = '/panel/js/ckeditor';

	config.filebrowserBrowseUrl = CKLocation + '/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = CKLocation + '/ckfinder/ckfinder.html?type=Images';
	config.filebrowserFlashBrowseUrl = CKLocation + '/ckfinder/ckfinder.html?type=Flash';
	config.filebrowserUploadUrl = CKLocation + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = CKLocation + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = CKLocation + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

    config.extraPlugins = 'youtube';
};
