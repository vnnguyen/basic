<?php
namespace common\models;

use yii\db\ActiveRecord;

class MyActiveRecord extends ActiveRecord
{
	public static $statusList = [
		''=>'-',
		'on'=>'On',
		'off'=>'Off',
		'draft'=>'Draft',
		'deleted'=>'Deleted',
	];
	public static $yesNoList = [
		''=>'-',
		'yes'=>'Yes',
		'no'=>'No',
	];

	public function insertCKEditor()
	{
		$text = <<<TXT
<script src="{DIR}assets/ckeditor/ckeditor.js"></script>
<script src="{DIR}assets/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
$('textarea.ckeditor').ckeditor({
	//language: 'vi',
	contentsCss: '{DIR}assets/css/style_ckeditor.css',
	toolbar: 'Full',
	toolbarGroups: [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'tools' },
		{ name: 'about' },

		{ name: 'colors' },
		{ name: 'insert' },
		{ name: 'links' },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },

		{ name: 'styles' },
		{ name: 'forms' },
		{ name: 'others' }
	],
	extraPlugins: 'magicline,tableresize',
	removePlugins: 'newpage,save,font,specialchar‎',
	entities: false,
	entities_greek: false,
	entities_latin: false,
	entities_processNumerical: false,
	height: 500,
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt iframe embed table thead tbody tfoot tr th td span strong em s a ul ol li img blockquote[*]{*}(*);',
	filebrowserBrowseUrl: '{DIR}app/ckfinder',
	//filebrowserUploadUrl: '{DIR}assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Image'
});
</script>
TXT;
		echo str_replace(['{DIR}'], [DIR], $text);
	}

	public function insertCKEditor2()
	{
		$text = <<<TXT
<script src="{DIR}assets/ckeditor/ckeditor.js"></script>
<script src="{DIR}assets/ckeditor/adapters/jquery.js"></script>
<script src="{DIR}assets/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
$('textarea.ckeditor').ckeditor({
	//language: 'vi',
	contentsCss: '{DIR}assets/css/style_ckeditor.css',
	toolbar: 'Full',
	toolbarGroups: [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'tools' },
		{ name: 'about' },

		{ name: 'colors' },
		{ name: 'insert' },
		{ name: 'links' },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },

		{ name: 'styles' },
		{ name: 'forms' },
		{ name: 'others' }
	],
	extraPlugins: 'magicline,tableresize',
	removePlugins: 'newpage,save,font,specialchar‎',
	entities: false,
	entities_greek: false,
	entities_latin: false,
	entities_processNumerical: false,
	height: 500,
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt iframe embed table thead tbody tfoot tr th td span strong em s a ul ol li img blockquote[*]{*}(*);',
	filebrowserBrowseUrl: '{DIR}app/ckfinder',
	//filebrowserUploadUrl: '{DIR}assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Image'
});

var ckfinderUpdate = '';

function BrowseServer()
{
	var finder = new CKFinder();
	finder.basePath = '/assets/ckfinder/';
	finder.selectActionFunction = SetFileField;
	finder.popup();
}

function SetFileField( fileUrl )
{
	//document.getElementById( 'xFilePath' ).value = fileUrl;
	$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	$('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

$(function(){
	$('.ckfinder').dblclick(function(){
		ckfinderUpdate = $(this).data('ckfinder-update')
		BrowseServer();
	});
})
</script>
TXT;
		echo str_replace(['{DIR}'], [DIR], $text);
	}

	public function insertCKFinder()
	{
		$text = <<<TXT
<script src="{DIR}assets/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
var ckfinderUpdate = '';

function BrowseServer()
{
	var finder = new CKFinder();
	finder.basePath = '{DIR}assets/ckfinder/';
	finder.selectActionFunction = SetFileField;
	finder.popup();
}

function SetFileField( fileUrl )
{
	$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	$('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

$(function(){
	$('.ckfinder').dblclick(function(){
		ckfinderUpdate = $(this).data('ckfinder-update')
		BrowseServer();
	});
	$('input.ckfinder').change(function(){
		fileUrl = $(this).val();
		if (fileUrl == '')
			fileUrl = 'http://placehold.it/300x300&text=NO+IMAGE'
		ckfinderUpdate = $(this).data('ckfinder-update')
		$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	});
})
</script>
TXT;
		echo str_replace(['{DIR}'], [DIR], $text);
	}

}
