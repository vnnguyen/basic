<?php
use yii\helpers\Html;

/**
 * @var yii\base\View $this
 * @var common\models\User $user;
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl('login/reset', ['token' => $user->password_reset_token]);
?>
<p>Hello <?= Html::encode($user->name)?>,</p>
<p>Follow the link below to reset your password:</p>
<p><?= Html::encode($resetLink) ?></p>
<p style="color:#999">--<br>Amica Travel IMS</p>