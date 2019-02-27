<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'qhkh/service-plus?action=add', 'active'=>Yii::$app->request->get('action') == 'add'],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'qhkh/service-plus?action=edit&id='.$theService['id'], 'active'=>Yii::$app->request->get('action') == 'edit'],
        ['icon'=>'trash-o', 'title'=>Yii::t('app', 'Delete'), 'class'=>'text-danger', 'link'=>'qhkh/service-plus?action=delete&id='.$theService['id'], 'active'=>Yii::$app->request->get('action') == 'delete'],
    ],
];

?>
<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-sm-4"><?= $form->field($theService, 'code')->label(Yii::t('x', 'Tour code')) ?></div>
                <div class="col-sm-4"><?= $form->field($theService, 'svc_date')->label(Yii::t('x', 'Service date')) ?></div>
                <div class="col-sm-4"><?= $form->field($theService, 'svc_success')->dropdownList([
                    ''=>Yii::t('x', 'Result not available'),
                    'yes'=>Yii::t('x', 'Result: OK'),
                    'no'=>Yii::t('x', 'Result: Not OK'),
                    ])->label(Yii::t('x', 'Result')) ?></div>
            </div>
            
            <?= $form->field($theService, 'context')->textArea(['rows'=>5])->label(Yii::t('x', 'Reason for Service Plus')) ?>
            <?= $form->field($theService, 'sv')->textArea(['rows'=>5])->label(Yii::t('x', 'Service Plus')) ?>
            <?= $form->field($theService, 'cp')->label(Yii::t('x', 'Cost'))->hint('This is under testing.') ?>
            <?= $form->field($theService, 'result')->textArea(['rows'=>5])->label(Yii::t('x', 'Result')) ?>
            <?= Html::submitButton(Yii::t('app', 'Save changes'), ['class'=>'btn btn-primary']) ?>    
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php 

$js = <<<'JS'
$('#serviceplus-svc_date').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    language: 'en',
    dateFormat: 'yyyy-mm-dd'
});
JS;
$this->registerJs($js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);