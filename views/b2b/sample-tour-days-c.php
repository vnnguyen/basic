<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\CkeditorAsset;

Yii::$app->params['page_title'] = $theDay->isNewRecord ? 'New sample tour day' : 'Edit sample tour day';

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour days', 'b2b/sample-tour-days'],
	[$theDay->isNewRecord ? 'New' : 'Edit'],
];

$mealList = [
	'---'=>'---',
	'B--'=>'B--',
	'-L-'=>'-L-',
	'--D'=>'--D',
	'BL-'=>'BL-',
	'B-D'=>'B-D',
	'-LD'=>'-LD',
	'BLD'=>'BLD',
];

$languageList = [
	'en'=>'English',
	'fr'=>'Français',
];

if (!$theDay->isNewRecord) {
	Yii::$app->params['page_actions'] = [
		[
			['icon'=>'trash-o', 'label'=>'Delete', 'class'=>'btn-danger', 'link'=>'b2b/sample-tour-days-d/'.$theDay['id']],
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
				<div class="col-md-3"><?= $form->field($theDay, 'language')->dropdownList($languageList, ['prompt'=>Yii::t('app', '- Select -')]) ?></div>
				<div class="col-md-6"><?= $form->field($theDay, 'title') ?></div>
				<div class="col-md-3"><?= $form->field($theDay, 'meals')->dropdownList($mealList, ['prompt'=>Yii::t('app', '- Select -')]) ?></div>
			</div>
			<?= $form->field($theDay, 'body')->textArea(['rows'=>10, 'class'=>'ckeditor form-comtrol']) ?>
			<?= $form->field($theDay, 'tags') ?>
			<?= $form->field($theDay, 'note')->textArea(['rows'=>5]) ?>
			<div class="text-right"><?= Html::submitButton(Yii::t('app', 'Save change'), ['class' => 'btn btn-primary']) ?></div>
		</div>
	</div>
</div>
<?
ActiveForm::end();
$this->registerJs(CkeditorAsset::ckeditorJs('.ckeditor', 'basic'));