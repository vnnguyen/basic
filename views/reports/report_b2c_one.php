<?php
use yii\helpers\Html;

Yii::$app->params['page_icon'] = 'area-chart';
Yii::$app->params['page_title'] = 'B2C - Doanh thu bán hàng theo tháng tour kết thúc';
Yii::$app->params['body_class'] = 'sidebar-xs';

Yii::$app->params['page_breadcrumbs'] = [
    ['Manager', 'manager'],
    ['Reports', 'reports'],
    ['B2C - One'],
];

for ($y = date('Y') + 1; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}
for ($m = 1; $m <= 12; $m ++) {
    $monthList[$m] = $m;
}

$currencyList = [
    'EUR'=>'EUR',
    'USD'=>'USD',
    'VND'=>'VND',
];
?>
<style type="text/css">
.tablesorter-headerAsc {background:url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjI8Bya2wnINUMopZAQA7) right bottom no-repeat;}
.tablesorter-headerDesc {background:url(data:image/gif;base64,R0lGODlhFQAEAIAAACMtMP///yH5BAEAAAEALAAAAAAVAAQAAAINjB+gC+jP2ptn0WskLQA7) right bottom no-repeat;}
</style>
<div class="col-md-12">
    <form class="form-inline mb-20">
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Tour end in')]) ?>
        <?= Html::dropdownList('currency', $currency, $currencyList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'View in')]) ?>
        (<?= Yii::t('x', 'Exchange rates') ?>: 1 <span id="currency"><?= $currency ?></span>
        <?php foreach ($currencyList as $c) { ?>
        =<?= Html::textInput('xrate_'.$c, ${'xrate_'.$c}, ['class'=>'form-control text-right', 'style'=>'width:100px;']) ?><?= $c ?>;
        <?php } ?>
        )
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
    </form>
    <div class="panel panel-body no-padding table-responsive">
        <table class="table table-narrow table-striped" id="myTable">
            <thead>
                <tr>
                    <th><?= Yii::t('x', 'Seller') ?>\<?= Yii::t('x', 'Month') ?></th>
                    <?php foreach ($monthList as $m) { ?>
                    <th class="text-center" colspan="3" style="border-left:1px solid #ddd;"><?= $m ?></th>
                    <?php } ?>
                    <th colspan="3" class="text-center warning" style="border-left:1px solid #ddd;">Year</th>
                    <th><?= Yii::t('x', 'Month') ?>/<?= Yii::t('x', 'Seller') ?></th>
                </tr>
                <tr>
                    <th></th>
                    <?php foreach ($monthList as $m) { ?>
                    <th class="text-right" title="<?= Yii::t('x', 'Number of tours sold') ?>" style="border-left:1px solid #ddd;">ST</th>
                    <th class="text-right" title="<?= Yii::t('x', 'Total revenue') ?>">DT</th>
                    <th class="text-right" title="<?= Yii::t('x', 'Total profit') ?>">LG</th>
                    <?php } ?>
                    <th class="text-right warning" title="<?= Yii::t('x', 'Number of tours sold') ?>" style="border-left:1px solid #ddd;">ST</th>
                    <th class="text-right warning" title="<?= Yii::t('x', 'Total revenue') ?>">DT</th>
                    <th class="text-right warning" title="<?= Yii::t('x', 'Total profit') ?>">LG</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $seller=>$data) { ?>
                <tr>
                    <th class="text-nowrap"><?= $data['name'] ?></th>
                    <?php foreach ($monthList as $m) { ?>
                    <?php $sameYn = $year == date('Y') && $m == date('n'); ?>
                    <?php if ($data[$m]['tours'] == 0) { ?>
                    <td colspan="3" class="<?= $sameYn ? 'info' : '' ?>" style="border-left:1px solid #ddd;"></td>
                    <?php } else { ?>
                    <td class="text-right <?= $sameYn ? 'info' : '' ?>" style="border-left:1px solid #ddd;"><?= Html::a($data[$m]['tours'], '/reports/bookings?viewby=ketthuc&year='.$year.'&month='.$m.'&fg=f&seller='.$seller, ['target'=>'_blank']) ?></td>
                    <td class="text-right <?= $sameYn ? 'info' : '' ?> text-brown"><?= number_format($data[$m]['revenue']) ?></td>
                    <td class="text-right <?= $sameYn ? 'info' : '' ?> text-success"><?= number_format($data[$m]['benefit']) ?></td>
                    <?php } ?>
                    <?php } ?>
                    <td class="text-right warning" style="border-left:1px solid #ddd;"><?= $data[0]['tours'] ?></td>
                    <td class="text-right warning text-brown"><?= number_format($data[0]['revenue']) ?></td>
                    <td class="text-right warning text-success"><?= number_format($data[0]['benefit']) ?></td>
                    <th class="text-nowrap"><?= $data['name'] ?></th>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php

$js = <<<'JS'
$('#myTable').tablesorter();
$('[name="currency"]').on('change', function(){
    $('#currency').html($(this).val())
})
JS;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.29.0/js/jquery.tablesorter.combined.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);