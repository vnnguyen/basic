<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = Yii::t('a', 'Access log');
Yii::$app->params['page_breadcrumbs'] = [
	[Yii::t('a', 'Account CP'), 'mcp'],
	[Yii::t('a', 'Access log')],
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
                        <td><?= $hit['user']['name'] ?></td>
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

