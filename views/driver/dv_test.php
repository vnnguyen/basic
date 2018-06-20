<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

Yii::$app->params['page_title'] = 'Test form DV/CP';

$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Dịch vụ / Chi phí</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'mua') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'mua_cty') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'mua_hd') ?></div>
            </div>
            <div class="text-right"><?=Html::submitButton('Save changes', ['class' => 'btn btn-primary']); ?></div>
        </div>
    </div>
</div>
<? ActiveForm::end();
