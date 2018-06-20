<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = Yii::t('x', 'Edit B2C seller daily tasks');

?>
<style>
#tblTasks th, #tblTasks td {vertical-align:top;}
</style>
<div class="col-md-8">
    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($theForm, 'c1')->textArea(['rows'=>10])->label(Yii::t('x', 'Reply from customer')) ?>
    <?= $form->field($theForm, 'c2')->textArea(['rows'=>10])->label(Yii::t('x', 'Reply to customer')) ?>
    <?= $form->field($theForm, 'c3')->textArea(['rows'=>10])->label(Yii::t('x', 'Other tasks')) ?>
    <?= Html::submitButton(Yii::t('app', 'Save changes'), ['class'=>'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Cancel'), '?seller='.$seller.'&year='.$year.'&month='.$month) ?>
    <?= Html::a(Yii::t('app', 'Delete'), '?action=delete&seller='.$seller.'&date='.$date, ['class'=>'btn btn-danger pull-right']) ?>
    <?php ActiveForm::end() ?>
</div>
<?php

$js = <<<'JS'
$('a.btn-danger.pull-right').on('click', function(e){
    if (!confirm('Delete this item?')) {
        return false;
    }
})
$('#b2csellerdailytaskseditform-c1, #b2csellerdailytaskseditform-c2, #b2csellerdailytaskseditform-c3').ckeditor({
    // allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote; a{href};',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    height:200,
    contentsCss: '/assets/css/style_ckeditor.css'
});
JS;
$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.7.3/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.7.3/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
