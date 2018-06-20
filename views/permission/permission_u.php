<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_permission_inc.php');

$this->title = 'New permission';
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"></h6>
        </div>
        <div class="panel-body">
			<? $form = ActiveForm::begin(); ?>
			<div class="row">
				<div class="col-md-5"><?= $form->field($thePermission, 'name') ?></div>
				<div class="col-md-5"><?= $form->field($thePermission, 'alias') ?></div>
				<div class="col-md-2"><?= $form->field($thePermission, 'status')->dropdownList(['on'=>'On', 'off'=>'Off']) ?></div>
			</div>
			<?= $form->field($thePermission, 'info')->textArea(['rows'=>3]) ?>
			<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
			<? ActiveForm::end(); ?>        	
        </div>
	</div>
</div>
