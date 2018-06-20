<?
$js = <<<'TXT'
$('.ckeditor').ckeditor({
	uiColor: '#ffffff',
	//customConfig: '/assets/js/ckeditor_config_simple_1.js'
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt iframe embed table thead tbody tfoot tr th td span strong del em s a u ul ol li img blockquote[*]{*}(*);',
	contentsCss: '/assets/css/ckeditor_simple_140408.css',
	entities: false,
	entities_additional: '',
	entities_greek: false,
	entities_latin: false,
	fillEmptyBlocks: false,
	toolbar: [
		['Source','-','Bold','Italic','Underline','Strike','-','TextColor','BGColor','-','RemoveFormat','-','NumberedList','BulletedList','-','Blockquote','-','Link','Unlink','-','Image','Table','HorizontalRule']
	],
	height: 400,
	extraPlugins: 'tableresize'
});
TXT;

$this->registerJsFile(DIR.'assets/ckeditor_4.4.7/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/ckeditor_4.4.7/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);