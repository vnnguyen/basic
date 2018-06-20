<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_notes_inc.php');

$this->title = 'Edit note: '.$theNote['title'];
//$this->params['breadcrumb'][] = [$theName, $theLink];
?>
<div class="col-md-8">
	<div class="alert alert-warning">
		<i class="fa fa-fw fa-warning"></i>
		IMPORTANT: You're justing adding a record of an email you sent or received. This program will NOT send your email.
	</div>

	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"></div>
		<div class="col-md-6"></div>
	</div>
	<?= $form->field($theNote, 'title') ?>
	<?= $form->field($theNote, 'body')->textArea(['rows'=>30, 'class'=>'form-control ckeditor xredactor']) ?>
	<div class="text-right"><?= Html::submitButton('Save note', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<?
$js = <<<TXT
$('.redactor').redactor({
	minHeight: 300,
	cleanFontTag: true,
	cleanSpaces: true,
	convertImageLinks: true,
	convertLinks: true,
	convertVideoLinks: true,
	tidyHtml: false,
	plugins: ['fontcolor', 'fullscreen']
});
TXT;
$js = <<<TXT
$('.ckeditor').ckeditor({
	customConfig: '/assets/js/ckeditor_config_simple_1.js'
});
TXT;
$this->registerJsFile(DIR.'assets/cksource/ckeditor_4.3.4/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/cksource/ckeditor_4.3.4/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
