<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_issue_inc.php');

$assignToList = \common\models\User::find()
    ->select(['id', 'name'])
    // ->where(['status'=>'on', 'account_id'=>ACCOUNT_ID])
    ->where(['status'=>'on', 'is_member'=>'yes'])
    ->asArray()
    ->orderBy('lname, fname')
    ->all();

?>
<style>
.datepicker>div {display:block}
</style>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <? $form = ActiveForm::begin() ?>
            <?= $form->field($theIssue, 'name') ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theIssue, 'project_id')->dropdownList($projectList)->label('Project') ?></div>
                <div class="col-md-6"><?= $form->field($theIssue, 'milestone')->label('Milestone / Phase')->dropdownList($milestoneList, ['prompt'=>'- Chọn -']) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theIssue, 'category')->dropdownList($categoryList)->label('Category') ?></div>
                <div class="col-md-6"><?= $form->field($theIssue, 'status')->dropdownList($statusList)->label('Status') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theIssue, 'assigned_to')->dropdownList(ArrayHelper::map($assignToList, 'id', 'name'), ['prompt'=>'- Select -'])->label('Assign to') ?></div>
                <div class="col-md-3 col-sm-6"><?= $form->field($theIssue, 'start_date')->label('Start date') ?></div>
                <div class="col-md-3 col-sm-6"><?= $form->field($theIssue, 'due_date')->label('Due date') ?></div>
            </div>
            <?= $form->field($theIssue, 'body')->textArea(['rows'=>10]) ?>
            <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>
<?
\app\assets\CkeditorAsset::register($this);
\app\assets\CkfinderAsset::register($this);
$this->registerJs(\app\assets\CkeditorAsset::ckeditorJs('#issue-body', 'full', 'issue'.$theIssue['id']));
$this->registerJs(\app\assets\CkfinderAsset::ckfinderJs());

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.vi.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.standalone.min.css', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<'TXT'
$('#issue-start_date, #issue-due_date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    autoclose: true

    // firstDay: 1,
    // timepicker: true,
    // todayButton: true,
    // clearButton: true,
    // autoClose: true,
    // language: 'en',
    // dateFormat: 'yyyy-mm-dd'
});
TXT;

$this->registerJs($js);

// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
