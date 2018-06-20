<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CpTour */

$this->title = Yii::t('app', 'Create Cp Tour');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cp Tours'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cp-tour-create">
    <?= $this->render('_form', [
        'model' => $model,
        'days' => (isset($days))? $days: null,
        'pages' => $pages,
        'dataProvider' => $dataProvider,
        'tour_model' => $tour_model
    ]) ?>

</div>
