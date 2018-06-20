<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_kbposts_inc.php');

$this->title  = 'Edit: '.$theEntry['title'];
$this->params['icon'] = 'edit';

$catList = [];
foreach (Yii::$app->params['amica/kb/cats'] as $cat) {
	$catList[$cat['id']] = str_repeat('---', $cat['depth'] - 1).' '.$cat['name'];
}

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<?= $form->field($theEntry, 'title') ?>
	<?= $form->field($theEntry, 'body')->textArea(['rows'=>10, 'class'=>'form-control ckeditor']) ?>
</div>
<div class="col-md-4">
	<?= $form->field($theEntry, 'author_id')->dropdownList(ArrayHelper::map($authorList, 'id', 'name'), ['prompt'=>'( No change )']); ?>
	<?= $form->field($theEntry, 'online_from') ?>
	<?= $form->field($theEntry, 'cats')->dropdownList($catList) ?>
	<?= $form->field($theEntry, 'tags') ?>
	<?= $form->field($theEntry, 'status')->dropdownList(['on'=>'On', 'off'=>'Off', 'draft'=>'Draft', 'deleted'=>'Deleted']); ?>
	<div><?= Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary btn-block']) ?></div>
</div>
<? ActiveForm::end();
$js = <<<'TXT'
$('textarea.ckeditor').ckeditor({
	allowedContent: 'h1 h2 h3 h4 h5 h6 p hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
	contentsCss: '/assets/css/style_ckeditor.css',
	entities: false,
	entities_greek: false,
	entities_latin: false,
	extraPlugins: 'magicline,tableresize',
	filebrowserBrowseUrl: '/app/ckfinder',
	//filebrowserUploadUrl: '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Image'
	height: 500,
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
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	],
});

// ckfinder
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
			fileUrl = 'https://placehold.it/300x100&text=NO+IMAGE'
		ckfinderUpdate = $(this).data('ckfinder-update')
		$('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
	});
})

TXT;
$this->registerJsFile('https://cdn.ckeditor.com/4.5.3/full-all/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/ckeditor_4.5.3/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/ckfinder/ckfinder.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
