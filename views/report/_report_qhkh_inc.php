<?php
Yii::$app->params['page_icon'] = 'list';

Yii::$app->params['page_breadcrumbs'] = [
    // ['Manager', '@web/manager'],
    ['Reports', '@web/reports'],
    ['QHKH'],
];

$this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'qhkh-01' ? ' active' : '' ?>" href="/reports/qhkh-01"><?= Yii::t('x', 'Report 01') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'qhkh-02' ? ' active' : '' ?>" href="/reports/qhkh-02"><?= Yii::t('x', 'Report 02') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'qhkh-03' ? ' active' : '' ?>" href="/reports/qhkh-03"><?= Yii::t('x', 'Report 03') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'qhkh-04' ? ' active' : '' ?>" href="/reports/qhkh-04"><?= Yii::t('x', 'Report 04') ?></a></li>
</ul><?php
$this->endBlock();