/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.extraPlugins = 'tableresize,codemirror';

   config.filebrowserBrowseUrl = '/admin/class/ckeditor/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/admin/class/ckeditor/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/admin/class/ckeditor/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/admin/class/ckeditor/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/admin/class/ckeditor/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/admin/class/ckeditor/kcfinder/upload.php?type=flash';	


};
