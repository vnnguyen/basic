<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Cp Tours');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cp-tour-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cp Tour'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tour_id',
            'ncc',
            'dv',
            'sl',
            // 'dem',
            // 'ngay_sd',
            // 'gia',
            // 'unit',
            // 'vp_dat',
            // 'vp_tra',
            // 'status_book',
            // 'ngay_tt',
            // 'nguoi_tra',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
