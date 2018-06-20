<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_products_inc.php');

$this->title = 'Edit client code: '.$theProduct['title'];
$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProduct['id']];
$this->params['breadcrumb'][] = ['Edit client code', 'products/ref/'.$theProduct['id']];

$form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-4"><?= $form->field($theProduct, 'client_ref')->label('Client tour code') ?></div>
	</div>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theProduct, 'op_code')->label('Amica tour code') ?></div>
		<div class="col-md-8"><?= $form->field($theProduct, 'op_name')->label('Amica tour code') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end(); 