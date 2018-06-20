<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title']  = $theNode['name'];
Yii::$app->params['page_breadcrumbs']  = [
    ['Nodes', 'nodes'],
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= $theNode['search'] ?></h6>
        </div>
        <div class="panel-body">
            <?= nl2br($theNode['body']) ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('s', 'Node description') ?></h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('s', 'Who can access this space') ?></h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
</div>
