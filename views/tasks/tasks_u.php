<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tasks_inc.php');

Yii::$app->params['page_title'] = Yii::t('x', 'Edit task');

if ($theTask['rtype'] == 'tour') {
    $taskTour = \common\models\Tour::find()
        ->select(['id', 'code', 'name'])
        ->where(['id'=>$theTask['rid']])
        ->asArray()
        ->one();
    $taskRelName = $taskTour['code'].' - '.$taskTour['name'];
    $taskRelLink = '/tours/r/'.$taskTour['id'];
} elseif ($theTask['rtype'] == 'case') {
    $taskCase = \common\models\Kase::find()
        ->select(['id', 'name'])
        ->where(['id'=>$theTask['rid']])
        ->asArray()
        ->one();
    $taskRelName = $taskCase['name'];
    $taskRelLink = '/cases/r/'.$taskCase['id'];
} else {
    $taskRelName = $theTask['rtype'];
    $taskRelLink = '#';
}

$userList = \app\models\User::find()
    ->select(['id', 'fname', 'lname', 'name', 'email'])
    ->where(['status'=>'on'])
    ->orderBy('lname, fname')
    ->asArray()
    ->all();

?>
<style type="text/css">
#task-is_all label {display: block; padding-left: 1rem}
</style>
<div class="col-md-8">
    <div class="alert alert-info">
        <?= Yii::t('x', 'This task is related to: ') ?>
        <?= Html::a($taskRelName, $taskRelLink, ['class'=>'alert-link']) ?>
    </div>
    <div class="card card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($theTask, 'description')->textArea(['rows'=>3])->label(Yii::t('x', 'What needs to be done')) ?>
        <div class="row">
            <div class="col-md-3"><?= $form->field($theTask, 'due_dt')->label(Yii::t('x', 'Due date/time')) ?></div>
            <div class="col-md-3"><?= $form->field($theTask, 'mins', ['inputOptions'=>['type'=>'number', 'class'=>'form-control', 'min'=>0, 'max'=>9999]])->label(Yii::t('x', 'Estimated time needed (minutes)')) ?></div>
            <div class="col-md-3 offset-3"><?= $form->field($theTask, 'is_priority')->dropdownList(['yes'=>Yii::t('x', 'Yes'), 'no'=>Yii::t('x', 'No')])->label(Yii::t('x', 'Priority task')) ?></div>
        </div>
        <?= $form->field($theTask, 'id')->dropdownList(ArrayHelper::map($userList, 'id', 'name'), ['class'=>'has-select2', 'multiple'=>'multiple'])->label(Yii::t('x', 'Task is assigned to')) ?>
        <?= $form->field($theTask, 'is_all')->radioList(['yes'=>Yii::t('all', 'Task is marked done only when all people have completed it'), 'no'=>Yii::t('x', 'Task is marked done when at least one of the people has completed it')])->label(Yii::t('x', 'If this task is assigned to multiple people, then')) ?>
        <p class="text-danger">
            <?= Yii::t('x', 'NOTE: After you save it, the task will be marked as NOT DONE!') ?>
        </p>
        <?= Html::submitButton(Yii::t('x', 'Save changes'), ['class'=>'btn btn-primary']) ?>
        <?= Yii::t('x', 'or') ?>
        <?= Html::a(Yii::t('x', 'Cancel'), '#back') ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$js = <<<'TXT'
$('.has-select2').select2();
TXT;

$this->registerJs($js);