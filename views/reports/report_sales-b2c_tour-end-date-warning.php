<?php
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'exclamation-triangle';
Yii::$app->params['page_title'] = 'WARNING: Open cases with tour start date in the past or less than 7 days away';

include('_report_sales-b2c_inc.php');


$refBy = [
    'referred/customer'=>'Bởi khách cũ',
    'referred/amica'=>'Bởi người Amica',
    'referred/org'=>'Bởi tổ chức liên quan',
    'referred/other'=>'Bởi nguồn khác',
    'referred/expat'=>'Bởi expat ở VN',
];

function rtrim0($text) {
    return rtrim(rtrim($text, '0'), '.');
}
?>
<div class="col-md-12">
    <form class="form-inline mb-20">
        <?= Html::textInput('year', $year, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Year')]) ?>
        <?= Html::textInput('month', $month, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Month')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="panel panel-default">
        <div class="panel-body table-responsive no-padding">
            <table class="table table-narrow">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Sale status</th>
                        <th>Owner</th>
                        <th>Tour start date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $case) { ?>
                    <tr>
                        <td class="text-center text-muted"><?= $case['id'] ?></td>
                        <td><?= Html::a($case['name'], '/cases/r/'.$case['id']) ?></td>
                        <td><?= $case['status'] ?></td>
                        <td><?= $case['deal_status'] ?></td>
                        <td><?= $case['owner']['name'] ?></td>
                        <td><?= $case['tour_start_date'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>