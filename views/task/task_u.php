<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//include('_task_inc.php'); ?>

<div class="col-md-8">
    <? $form = ActiveForm::begin(); ?>
    <div class="alert alert-info">
        <?= Yii::t('x', 'This task is related to: ') ?>
    </div>
    <?= $form->field($theTask, 'description') ?>
    <div class="row">
        <div class="col-md-3"><?= $form->field($theTask, 'due_dt') ?></div>
        <div class="col-md-3"><?= $form->field($theTask, 'id') ?></div>
        <div class="col-md-3"><?= $form->field($theTask, 'is_priority') ?></div>
        <div class="col-md-3"><?= $form->field($theTask, 'mins') ?></div>
    </div>
    <?= $form->field($theTask, 'id') ?>
    <? ActiveForm::end(); ?>
</div>