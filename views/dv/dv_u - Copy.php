<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

if ($theDv->isNewRecord) {
	Yii::$app->params['page_title'] = 'Thêm dịch vụ mới';
} else {
	Yii::$app->params['page_title'] = 'Sửa dịch vụ: '.$theDv['name'];
}



?>
<div class="col-md-8">
    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h6 class="panel-title">Dịch vụ</h6>
    	</div>
    	<div class="panel-body">
	<? $form = ActiveForm::begin(); ?>
	
	<div class="row">
        <div class="col-md-3"><?= $form->field($theDv, 'stype')->dropdownList($dvTypeList, ['prompt'=>'( Select )']) ?></div>
        <div class="col-md-3"><?= $form->field($theDv, 'is_dependent')->dropdownList(['no'=>'No', 'yes'=>'Yes'], ['prompt'=>'( Select )']) ?></div>
        <div class="col-md-3"><?= $form->field($theDv, 'object_type')->dropdownList(ArrayHelper::map(array_values($dvObjectTypeList), 'id', 'name'), ['prompt'=>'( Select )']) ?></div>
	</div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theDv, 'name') ?></div>
        <div class="col-md-6"><?= $form->field($theDv, 'search') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theDv, 'venue_id')->dropdownList(ArrayHelper::map($venueList, 'id', 'name'), ['prompt'=>'- Select -']) ?></div>
        <div class="col-md-6"><?= $form->field($theDv, 'by_company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Select -']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theDv, 'from_loc') ?></div>
        <div class="col-md-6"><?= $form->field($theDv, 'to_loc') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theDv, 'booking_conds') ?></div>
        <div class="col-md-6"><?= $form->field($theDv, 'use_conds') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theDv, 'valid_from') ?></div>
        <div class="col-md-6"><?= $form->field($theDv, 'valid_until') ?></div>
    </div>
	<?= $form->field($theDv, 'note')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?=Html::submitButton('Save changes', ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
		</div>
	</div>
</div>
