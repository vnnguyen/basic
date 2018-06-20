<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title']  = $theSpace['name'];
Yii::$app->params['page_breadcrumbs']  = [
    ['Spaces', 'spaces'],
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Name</h6>
            <div class="heading-elements panel-nav">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="active"><a href="#t-activities" data-toggle="tab">Activities</a></li>
                    <li><a href="#t-discuss" data-toggle="tab">Discussion</a></li>
                    <li><a href="#t-files" data-toggle="tab">Files</a></li>
                    <li><a href="#t-people" data-toggle="tab">People</a></li>
                    <li><a href="#t-notes" data-toggle="tab">Notes</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <?= nl2br($theSpace['description']) ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('s', 'Space description') ?></h6>
        </div>
        <div class="panel-body">
            <?= nl2br($theSpace['description']) ?>
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
