<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

?>
<div class="col-md-8">
    <?
    $p = \Yii::$app->authManager->getPermissionsByUser(USER_ID);
    \fCore::expose($p);
    ?>
</div>