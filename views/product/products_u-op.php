<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_products_inc.php');

$this->title = 'Edit: '.$theProduct['title'];
$this->params['breadcrumb'][] = ['View', 'products/r/'.$theProduct['id']];
$this->params['breadcrumb'][] = ['Edit operation data', 'products/u-op/'.$theProduct['id']];

$form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-4"><?= $form->field($theProduct, 'op_code') ?></div>
		<div class="col-md-8"><?= $form->field($theProduct, 'op_name') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end(); 