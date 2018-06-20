<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = Yii::t('m', 'Access log');
Yii::$app->params['page_breadcrumbs'] = [
	[Yii::t('m', 'Master CP'), 'mcp'],
	[Yii::t('m', 'Access log')],
];
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= Yii::t('m', 'Hit list') ?></h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th><?= Yii::t('m', 'Access time') ?> (VN)</th>
                        <th><?= Yii::t('m', 'IP address') ?></th>
                        <th><?= Yii::t('m', 'Account') ?></th>
                        <th><?= Yii::t('m', 'User') ?></th>
                        <th><?= Yii::t('m', 'URI') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theHits as $hit) { ?>
                    <? if (substr($hit['uri'], 0, 10) != '/p/images/') { ?>
                    <tr>
                        <td class="text-nowrap"><?= date('j/n/Y H:i', strtotime('+7 hours', strtotime($hit['hit_dt']))) ?></td>
                        <td><span class="flag-icon flag-icon-<?= $hit['country_code'] ?>"></span> <?= $hit['ip'] ?></td>
                        <td><?= $hit['account']['name'] ?></td>
                        <td><?= Html::a($hit['user']['name'], '?user_id='.$hit['user']['id']) ?></td>
                        <td><?
                        $uriSegment = explode('/', $hit['uri']);
                        for ($i = 1; $i <= 5; $i ++) {
                            if (!isset($uriSegment[$i])) {
                                $uriSegment[$i] = '';
                            }
                        }
                        if (substr($hit['uri'], 0, 5) == '/p/r/') {
                            echo 'viewed property #'.$uriSegment[3];
                        } elseif (substr($hit['uri'], 0, 5) == '/p/u/') {
                            echo 'updated property #'.$uriSegment[3];
                        } elseif (substr($hit['uri'], 0, 18) == '/p/u-improvements/') {
                            echo 'updated improvements info of property #'.$uriSegment[3];
                        } elseif (substr($hit['uri'], 0, 14) == '/p/u-external/') {
                            echo 'updated external info of property #'.$uriSegment[3];
                        } elseif (substr($hit['uri'], 0, 19) == '/p/c?is_project=yes') {
                            echo 'added a new project';
                        } elseif (substr($hit['uri'], 0, 20) == '/default/ajax-search') {
                            echo 'searched using the top bar searchbox';
                        } elseif (substr($hit['uri'], 0, 8) == '/mcp/log') {
                            echo 'viewed MCP access log';
                        } else {
                            echo $hit['uri'];
                        }                        
                        ?></td>
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

