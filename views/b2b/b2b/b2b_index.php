<?php

use yii\helpers\Html;

Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'B2B workspace';
Yii::$app->params['page_breadcrumbs'] = [
    ['B2B']
];

$sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND DATE_ADD(day_from, INTERVAL day_count DAY)>:this AND SUBSTRING(op_code,1,1)="G" ORDER BY day_from, id LIMIT 1000';
$todayTours = \common\models\Product::findBySql($sql, [':this'=>date('Y-m-d'), ':next'=>date('Y-m-d')])
    ->with([
        'days'=>function($q) {
            return $q->select(['id', 'name', 'meals', 'rid']);
        },
    ])
    ->asArray()
    ->all();

$sql = 'SELECT id, op_finish, op_name, op_code, day_from, day_count, pax, day_ids FROM at_ct WHERE op_status="op" AND op_finish!="canceled" AND day_from<:next AND day_from>:this AND SUBSTRING(op_code,1,1)="G" ORDER BY day_from, id LIMIT 1000';
$upcomingTours = \common\models\Product::findBySql($sql, [':this'=>date('Y-m-d'), ':next'=>date('Y-m-d', strtotime('+7 days'))])
    ->with([
        'tour.operators'=>function($q) {
            return $q->select(['id', 'name'=>'nickname']);
        },
        'days'=>function($q) {
            return $q->select(['id', 'name', 'meals', 'rid']);
        },
    ])
    ->asArray()
    ->all();

$recentlyOpenedCases = \common\models\Kase::find()
    ->select(['id', 'name', 'stype', 'created_dt'=>'created_at', 'owner_id'])
    ->where(['stype'=>['b2b', 'b2b-series', 'b2b-prod']])
    ->with(['owner'=>function($q){
        return $q->select(['id', 'name'=>'nickname']);
    }
    ])
    ->orderBy('created_dt DESC')
    ->limit(10)
    ->asArray()
    ->all();

$recentlyOpenedTours = \common\models\Tour::find()
    // ->select(['id', 'name', 'stype', 'created_dt'=>'created_at', 'owner_id'])
    ->where('SUBSTRING(code,1,1)="G"')
    ->with([
        'operators'=>function($q){
            return $q->select(['id', 'name'=>'nickname']);
        },
        'product'=>function($q){
            return $q->select(['id', 'day_from', 'pax', 'day_count']);
        },
    ])
    ->orderBy('id DESC')
    ->limit(10)
    ->asArray()
    ->all();

// Number of tours under operation
// $numberOfToursInOperation = \common\models\Product::find()
//     ->select(['start_date'=>'day_from', 'end_date'=>new \yii\db\Expression('(SELECT IF(day_count=0, day_from, DATE_ADD(day_from, INTERVAL day_count-1 DAY))')])
//     ->where(['op_status'=>'op'])
//     ->andHaving('start_date <= :date2 AND end_date >= :date1', [':date1'=>date('Y-m-d'), ':date2'=>date('Y-m-d')])
//     ->count();
    $numberOfToursInOperation = 9;

?>
<div class="col-md-12">
    <div class="row">
        <div class="col-sm-3 col-xs-6">
            <div class="card card-body">
                <h6 class="text-uppercase text-gray"><?= Yii::t('x', 'Lastest tour') ?></h6>
                <div class="flexbox mt-2">
                    <span class="fa fa-flag text-info fs-30"></span>
                    <span class="fs-30">G1712098</span>
              </div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="card card-body">
                <h6 class="text-uppercase text-gray"><?= Yii::t('x', 'Open cases') ?></h6>
                <div class="flexbox mt-2">
                    <span class="fa fa-briefcase text-primary fs-30"></span>
                    <span class="fs-30"><?= $numberOfToursInOperation ?></span>
              </div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="card card-body">
                <h6 class="text-uppercase text-gray"><?= Yii::t('x', 'Open cases') ?></h6>
                <div class="flexbox mt-2">
                    <span class="fa fa-briefcase text-success fs-30"></span>
                    <span class="fs-30">156</span>
              </div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="card card-body">
                <h6 class="text-uppercase text-gray"><?= Yii::t('x', 'Open cases') ?></h6>
                <div class="flexbox mt-2">
                    <span class="fa fa-briefcase text-warning fs-30"></span>
                    <span class="fs-30">156</span>
              </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="card mb-3">
                <h6 class="card-header">Tours in operation today</h6>
                <table class="table table-narrow table-striped table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Tour code & name, days, pax</th>
                            <th>Today's activities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($todayTours as $tour) { ?>
                        <tr>
                            <td>
                                <?= Html::a($tour['op_code'], '/products/op/'.$tour['id']) ?>
                                <?= $tour['day_count'] ?>d
                                <?= $tour['pax'] ?>p
                                <?= date('j/n', strtotime($tour['day_from'])) ?>
                            </td>
                            <td><?
                            foreach ($tour['days'] as $i=>$day) {
                                if (date('Y-m-d', strtotime('+'.$i.' days '.$tour['day_from'])) == date('Y-m-d')) {
                                    echo $day['name'];
                                    break;
                                }
                            }
                            ?></td>
                        </tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>

            <div class="card mb-3">
                <h6 class="card-header">Upcoming tours</h6>
                <table class="table table-narrow table-striped table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Start</th>
                            <th>Tour</th>
                            <th>Operators</th>
                            <th>First day</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($upcomingTours as $tour) { ?>
                        <tr>
                            <td>
                                <?= date('j/n', strtotime($tour['day_from'])) ?>
                            </td>
                            <td>
                                <?= Html::a($tour['op_code'], '/products/op/'.$tour['id']) ?>
                                <?= $tour['day_count'] ?>d
                                <?= $tour['pax'] ?>p
                            </td>
                            <td><?
                            foreach ($tour['tour']['operators'] as $op) {
                                echo $op['name'];
                                break;
                            }
                            ?></td>
                            <td><?
                            foreach ($tour['days'] as $i=>$day) {
                                if (date('Y-m-d', strtotime('+'.$i.' days '.$tour['day_from'])) == date('Y-m-d', strtotime($tour['day_from']))) {
                                    echo $day['name'];
                                    break;
                                }
                            }
                            ?></td>
                        </tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>

            <p><strong>Recent activities</strong></p>
            <p>(LIST OF ACTIVITIES)</p>
        </div>
        <div class="col-sm-6">
            <p><strong>Recently opened cases</strong></p>
            <table class="table table-narrow table-striped table-bordered mb-20">
                <thead>
                    <tr>
                        <th></th>
                        <th>Case name</th>
                        <th>Category</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentlyOpenedCases as $case) { ?>
                    <tr>
                        <td><?= date('j/n', strtotime($case['created_dt'])) ?></td>
                        <td><?= Html::a($case['name'], '/b2b/cases/r/'.$case['id']) ?></td>
                        <td><?= $case['stype'] ?></td>
                        <td><?= $case['owner']['name'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>

            <p><strong>Recently confirmed tours</strong></p>
            <table class="table table-narrow table-striped table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tour code & name, days, pax</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($recentlyOpenedTours as $tour) { ?>
                    <tr>
                        <td><?= date('j/n', strtotime($tour['created_dt'])) ?></td>
                        <td>
                            <?= Html::a($tour['code'].' - '.$tour['name'], '/b2b/tours/r/'.$tour['id']) ?>
                            <?= $tour['product']['day_count'] ?>d
                            <?= $tour['product']['pax'] ?>p
                            <?= date('j/n/Y', strtotime($tour['product']['day_from'])) ?>
                        </td>
                        <td><?= $tour['operators'][0]['name'] ?? '' ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>