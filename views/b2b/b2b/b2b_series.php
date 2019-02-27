<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\DateTimeHelper;

Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'Dashboard of series tours ('.count($theTours).')';
Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'B2B'), 'b2b'],
    [Yii::t('x', 'Series tours').' ('.count($theTours).')', 'b2b/series'],
];
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'car';

?>
<div class="col-md-12">
    <form class="form-inline mb-1em">
        <?= Html::dropdownList('client', $client, ArrayHelper::map($clientList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Client')]) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Month')]) ?>
        <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Status')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <?php if (empty($theTours)) { ?>
    <div class="text-danger"><?= Yii::t('x', 'No data found.') ?></div>
    <?php } else { ?>
    <div class="panel panel-default table-responsive">
        <table class="table table-narrow table-bordered">
            <thead>
                <tr>
                    <th width="15"></th>
                    <th><?= Yii::t('x', 'Client') ?></th>
                    <th><?= Yii::t('x', 'Series name') ?></th>
                    <th><?= Yii::t('x', 'Start date') ?></th>
                    <th><?= Yii::t('x', 'Cut-off date') ?></th>
                    <th><?= Yii::t('x', 'Tour code / name') ?></th>
                    <th><?= Yii::t('x', 'Pax') ?></th>
                    <th><?= Yii::t('x', 'Days') ?></th>
                    <th><?= Yii::t('x', 'Status') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 0;
                foreach ($theTours as $tour) {
                    $cnt ++; ?>
                <tr>
                    <td class="text-muted text-right"><?= $cnt ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>?</td>
                    <td><?= Html::a($tour['code'], '/tours/r/'.$tour['id']) ?> - <?= $tour['name'] ?></td>
                    <td></td>
                    <td></td>
                    <td><?= $tour['status'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>