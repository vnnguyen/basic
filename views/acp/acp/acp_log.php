<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Account CP';
Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('nav', 'Account CP'), SEG2 == '' ? null : 'acp'],
    [Yii::t('nav', 'Activity log'), SEG2 == 'log' ? null : 'acp'],
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('a', 'Hit list') ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th><?= Yii::t('a', 'Access time') ?> (VN)</th>
                        <th><?= Yii::t('a', 'IP address') ?></th>
                        <th><?= Yii::t('a', 'User') ?></th>
                        <th><?= Yii::t('a', 'URI') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theHits as $hit) { ?>
                    <? if (substr($hit['uri'], 0, 10) != '/p/images/') { ?>
                    <tr>
                        <td class="text-nowrap"><?= date('j/n/Y H:i', strtotime('+7 hours', strtotime($hit['hit_dt']))) ?></td>
                        <td><span class="flag-icon flag-icon-<?= $hit['country_code'] ?>"></span> <?= $hit['ip'] ?></td>
                        <td><?= Html::a($hit['user']['name'], '?user_id='.$hit['user']['id']) ?></td>
                        <td><?= $hit['uri'] ?></td>
                    </tr>
                    <? } ?>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pagination->pageSize < $pagination->totalCount) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]) ?>
        </div>
        <? } ?>
    </div>    
</div>
