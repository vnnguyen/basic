<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

app\assets\DatetimePickerAsset::register($this);

if ($model->isNewRecord) {
	$this->title = 'New exchange rate';
	$this->params['breadcrumb'] = [
		['Exchange rates', 'xrates'],
		['Add', 'xrates/c'],
	];
} else {
	$this->title = 'Edit exchange rate';
	$this->params['breadcrumb'] = [
		['Exchange rates', 'xrates'],
		['View', 'xrates/r/'.$model['id']],
		['Edit', URI],
	];
}
include('xrates__inc.php');
?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin();?>	
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'rate_dt', ['inputOptions'=>['class'=>'form-control datepicker']]); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'currency1')->dropdownList(ArrayHelper::map(Yii::$app->params['systemCurrencyList'], 'code', 'code')); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'rate'); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'currency2')->dropdownList(ArrayHelper::map(Yii::$app->params['systemCurrencyList'], 'code', 'code')); ?>
		</div>
	</div>
	<?=$form->field($model, 'note')->textArea(['rows'=>4]); ?>
	<?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-4">
	<p><strong>Chỉ dẫn</strong></p>
	<p>Thông tin tỉ giá có ý nghĩa như sau: Tại (Thời điểm) thì 1 đơn vị (Tiền 1) quy đổi được (Tỉ giá) đơn vị (Tiền 2)</p>
	<p>Dùng dấu chấm (chứ không phải dấu phẩy) cho ngăn cách phần lẻ thập phân, vd 1.023 có nghĩa là một phẩy không hai ba chứ không phải một ngàn hai mươi ba</p>
</div>
<?
$js = <<<TXT
$('#xrate-rate_dt').datetimepicker({
	format:'YYYY-MM-DD HH:mm'
});
TXT;
$this->registerJs($js);