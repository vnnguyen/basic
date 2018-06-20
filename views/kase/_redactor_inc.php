
<?
$css = '.atwho-view{z-index:9999!important;} .redactor-editor {padding:8px; border-color:#ccc!important;} .redactor_toolbar {box-shadow:none!important; border:1px solid #ccc;}';
$js = <<<'TXT'
$('#redactor').redactor({
	focus: true,
	formatting: ['p', 'blockquote', 'pre'],
	initCallback: function(){
		//this.$editor.atwho(at_config).atwho(tag_config);
		this.code.set('');
	},
	minHeight: 200,
	convertImageLinks: true,
	convertVideoLinks: true,
	cleanOnPaste: false,
	removeComments: true,
	plugins: ['fontcolor', 'table']
});
TXT;

$this->registerCssFile(DIR.'assets/redactor_10.0.7/redactor.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/redactor_10.0.7/redactor.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/redactor_10.0.7/plugins/fontcolor/fontcolor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/redactor_10.0.7/plugins/table/table.js', ['depends'=>'app\assets\MainAsset']);

$this->registerCss($css);
$this->registerJs($js);
