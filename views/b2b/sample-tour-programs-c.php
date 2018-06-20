<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\CkeditorAsset;

Yii::$app->params['page_title'] = $theProgram->isNewRecord ? 'New sample tour program' : 'Edit sample tour program';

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour programs', 'b2b/sample-tour-programs'],
	[$theProgram->isNewRecord ? 'New' : 'Edit'],
];

$languageList = [
	'en'=>'English',
	'fr'=>'Français',
];

if (!$theProgram->isNewRecord) {
	Yii::$app->params['page_actions'] = [
		[
			['icon'=>'trash-o', 'label'=>'Delete', 'class'=>'btn-danger', 'link'=>'b2b/sample-tour-programs-d/'.$theProgram['id']],
		]
	];

}

CkeditorAsset::register($this);

$form = ActiveForm::begin();

?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3"><?= $form->field($theProgram, 'language')->dropdownList($languageList, ['prompt'=>Yii::t('app', '- Select -')]) ?></div>
				<div class="col-md-6"><?= $form->field($theProgram, 'title') ?></div>
			</div>
			<?= $form->field($theProgram, 'body')->textArea(['rows'=>10, 'class'=>'ckeditor form-comtrol']) ?>
			<?= $form->field($theProgram, 'tags') ?>
			<?= $form->field($theProgram, 'note')->textArea(['rows'=>5]) ?>
			<div class="text-right"><?= Html::submitButton(Yii::t('app', 'Save change'), ['class' => 'btn btn-primary']) ?></div>
		</div>
	</div>
</div>
<?
ActiveForm::end();
$this->registerJs(CkeditorAsset::ckeditorJs('.ckeditor', 'basic'));