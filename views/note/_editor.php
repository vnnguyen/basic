<?
use yii\helpers\Html;

$js = <<<TXT
$('.ckeditor').ckeditor({
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
	uiColor:'#ffffff',
	contentsCss: '/assets/css/style_ckeditor.css',
	entities: false,
	entities_greek: false,
	entities_latin: false,
	extraPlugins: 'magicline,tableresize',
	filebrowserBrowseUrl: '/app/ckfinder',
	//filebrowserUploadUrl: '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Image'
	height: 300,
	removeButtons: 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,NewPage,Preview,Print,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,BidiLtr,BidiRtl,Language,Font,FontSize,Smiley,SpecialChar,PageBreak,Flash,Templates',
	toolbar: 'Full',
	toolbarGroups: [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'links', groups: [ 'links' ] },
		'/',
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		// { name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		// { name: 'about', groups: [ 'about' ] }
	],
});

TXT;

$this->registerJsFile('https://cdn.ckeditor.com/4.5.3/full-all/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.5.3/full-all/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);

?>
				<div class="clearfix">
					<form method="post" action="">
						<div id="files-list"></div>
						<p id="files-container">
							<a id="files-browse" href="javascript:;">Upload files</a>
							<span id="files-console" class="text-danger"></span>
						</p>
						<p><textarea id="editor" class="ckeditor form-control" name="body" rows="8"></textarea></p>
						<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>

					</form>
				</div>