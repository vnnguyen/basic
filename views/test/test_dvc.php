<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title'] = 'Thêm dịch vụ tour';
Yii::$app->params['page_breadcrumbs'] = [
	['Dữ liệu', '@web/test'],
	['Dịch vụ tour', '@web/test/dv'],
];

$dvTypeList = [
	['id'=>1, 'name'=>'Đi lại, vận chuyển'],
	['id'=>2, 'name'=>'Ngủ nghỉ'],
	['id'=>3, 'name'=>'Ăn uống'],
	['id'=>4, 'name'=>'Tham quan, mua sắm, xem'],
	['id'=>5, 'name'=>'Giấy tờ thủ tục'],
	['id'=>6, 'name'=>'Guide, porter, dịch'],
	['id'=>7, 'name'=>'Chăm sóc sức khoẻ'],
	['id'=>8, 'name'=>'Học tập, hội họp'],
	['id'=>9, 'name'=>'Loại khác'],
];

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theDv, 'stype')->dropdownList(ArrayHelper::map($dvTypeList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?></div>
		<div class="col-md-3"><?= $form->field($theDv, 'dest_id')->dropdownList(ArrayHelper::map($destList, 'id', 'name_vi'), ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?></div>
		<div class="col-md-6"><?= $form->field($theDv, 'name') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theDv, 'venue_id')->dropdownList(ArrayHelper::map($venueList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?></div>
		<div class="col-md-6"><?= $form->field($theDv, 'provider_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?></div>
	</div>
	<?= $form->field($theDv, 'conditions')->textArea(['rows'=>5]) ?>
	<?= $form->field($theDv, 'note')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
