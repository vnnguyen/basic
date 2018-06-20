<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Logged out';
?>

<p><?= Yii::t('login', 'You have been successfully logged out. You will need to log in again to continue.') ?></p>
<p class="text-center"><a href="<?= DIR ?>login"><?= Yii::t('login', 'Return to login') ?></a></p>