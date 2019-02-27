<?php
use yii\helpers\Html;

$this->title = 'Tỉ trọng độ dài tour (tính theo ngày khởi hành)';
Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Reports'), '@web/reports'],
    [Yii::t('x', 'Tour operation'), '@web/reports?tab=dh'],
    [Yii::t('x', 'Tour length'), '@web/reports/dh-03'],
];

for ($yr = $minYear; $yr <= $maxYear; $yr ++) {
    for ($mo = 1; $mo <= 12; $mo ++) {
        $num['month'][$yr][$mo]['total'] = 0;
        foreach ($theGroups as $group) {
            $num['month'][$yr][$mo][$group[0]] = 0;
        }
    }
}

for ($yr = $minYear; $yr <= $maxYear; $yr ++) {
    $num['year'][$yr]['total'] = 0;
    foreach ($theGroups as $group) {
        $num['year'][$yr][$group[0]] = 0;
    }
}

foreach ($theProducts as $product) {
    $y = (int)substr($product['day_from'], 0, 4);
    if ($y >= $minYear && $y <= $maxYear) {
        // Thang tour
        $m = (int)substr($product['day_from'], 5, 2);
        // So ngay tour
        $d = (int)$product['day_count'];

        $num['year'][$y]['total'] ++;
        $num['month'][$y][$m]['total'] ++;
        
        foreach ($theGroups as $group) {
            if ($d >= $group[1] && $d <= $group[2]) {
                $num['year'][$y][$group[0]] ++;
                $num['month'][$y][$m][$group[0]] ++;
            }
        }
    }
}

?>
<div class="col-md-6">
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><?= Yii::t('x', 'Year view') ?></h6>
        </div>
        <div class="card-body">
            <form method="get" action="" class="form-inline">
                <?= Html::textInput('grouping', $getGrouping, ['class'=>'form-control', 'placeholder'=>'1-7,8-14,15-']) ?>
                <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-narrow">
                    <thead>
                        <tr>
                            <th class="text-center"><?= Yii::t('x', 'Year') ?></th>
                            <?php foreach ($theGroups as $group) { ?>
                            <th class="text-center"><?= Yii::t('x', '{count} days', ['count'=>$group[0]]) ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($yr = $maxYear; $yr >= $minYear; $yr --) { ?>
                        <tr>
                            <th class="text-center"><?= $yr ?></th>
                            <?php foreach ($theGroups as $group) { ?>
                            <td class="text-center">
                                <?php if ($num['year'][$yr]['total'] == 0) { ?>
                                -
                                <?php } else { ?>
                                <strong><?= number_format(100 * $num['year'][$yr][$group[0]] / $num['year'][$yr]['total'], 2) ?></strong>%
                                <?php } ?>
                                <div class="small text-muted"><?= number_format($num['year'][$yr][$group[0]], 0) ?>/<?= number_format($num['year'][$yr]['total'], 0) ?> <?= Html::a(Yii::t('x', 'tours'), '/tours?orderby=startdate&time='.$yr.'&daycount='.$group[0].(substr($group[0], -1) == '-' ? '999' : ''), ['target'=>'_blank']) ?></div>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>  
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><?= Yii::t('x', 'Month view') ?></h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills" data-tabs="tabs">
                <?php for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
                <li class="nav-item"><a class="nav-link<?= $yr == date('Y') ? ' show active' : ''?>" data-toggle="tab" href="#year<?= $yr ?>"><?= $yr ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="table-responsive">
            <div id="tab-content" class="tab-content">
                <?php for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
                <div id="year<?= $yr ?>" class="<?= $yr == date('Y') ? ' active' : '' ?> tab-pane table-responsive">
                    <table class="table table-narrow">
                        <thead>
                            <tr>
                                <th class="text-center"><?= Yii::t('x', 'Month') ?></th>
                                <?php foreach ($theGroups as $group) { ?>
                                <th class="text-center"><?= Yii::t('x', '{count} days', ['count'=>$group[0]]) ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                            <tr>
                                <th class="text-center"><?= $mo ?></th>
                                <?php foreach ($theGroups as $group) { ?>
                                <td class="text-center">
                                    <?php if ($num['month'][$yr][$mo]['total'] == 0) { ?>
                                    -
                                    <?php } else { ?>
                                    <strong><?= number_format(100 * $num['month'][$yr][$mo][$group[0]] / $num['month'][$yr][$mo]['total'], 2) ?></strong>%
                                    <?php } ?>
                                    <div class="small text-muted"><?= $num['month'][$yr][$mo][$group[0]] ?>/<?= $num['month'][$yr][$mo]['total'] ?> <?= Html::a(Yii::t('x', 'tours'), '/tours?orderby=startdate&time='.$yr.'-'.str_pad($mo, 2, '0', STR_PAD_LEFT).'&daycount='.$group[0].(substr($group[0], -1) == '-' ? '999' : ''), ['target'=>'_blank']) ?></div>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
