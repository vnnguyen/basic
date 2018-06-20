<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reset your password';

$form = ActiveForm::begin() ?>
<p>Please choose a new password.</p>
<?= $form->field($theForm, 'password')->passwordInput() ?>
<?= $form->field($theForm, 'password2')->passwordInput() ?>
<?= Html::submitButton('Save password', ['class' => 'btn btn-default btn-block']) ?>
<? ActiveForm::end(); ?>
