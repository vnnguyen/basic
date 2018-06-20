<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = Yii::t('x', 'B2C Seller Daily Tasks');

for ($y = date('Y') + 1; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}
for ($m = 1; $m <= 12; $m ++) {
    $monthList[$m] = $m;
}

?>
<style>
#tblTasks th, #tblTasks td {vertical-align:top;}
</style>
<div class="col-md-12">
    <form class="form-inline mb-20">
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Month')]) ?>
        <?= Html::dropdownList('seller', $seller, ArrayHelper::map($sellers, 'id', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'All sellers')]) ?>
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
    </form>
    <div class="panel panel-body no-padding">
        <table class="table table-narrow table-bordered table-striped" id="tblTasks">
            <thead>
                <tr>
                    <th><?= Yii::t('x', 'Date') ?></th>
                    <th><?= Yii::t('x', 'Seller') ?></th>
                    <th><?= Yii::t('x', 'Assigned case') ?></th>
                    <th><?= Yii::t('x', 'Reply from customer') ?></th>
                    <th><?= Yii::t('x', 'Reply to customer') ?></th>
                    <th><?= Yii::t('x', 'Other tasks') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php for ($d = 1; $d <= date('t'); $d ++) { ?>
                    <?php foreach ($sellers as $i=>$s) { if ($seller == 0 || $seller == $s['id']) { ?>
                    <?php
                    $x = ['', '', '', ''];
                    foreach ($metas as $meta) {
                        if ($meta['rid'] == $s['id'] && date('j/n', strtotime($meta['name'])) == $d.'/'.$month) {
                            $x = explode(';|', $meta['value']);
                            break;
                        }
                    }
                    ?>
                <tr class="<?= date('j/n') == $d.'/'.$month ? 'info' : '' ?>">
                    <th><?= $seller != 0 || $i == 0 ? date('D j/n', strtotime('2017-'.$month.'-'.$d)) : '' ?></th>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-edit"></i>', '?action=edit&seller='.$s['id'].'&date='.$year.'-'.$month.'-'.$d, ['class'=>'text-muted']) ?>
                        <?= Html::a($s['name'], '?year='.$year.'&month='.$month.'&seller='.$s['id']) ?>
                    </td>
                    <td><?php
                    foreach ($cases as $case) {
                        if ($case['owner_id'] == $s['id'] && date('j', strtotime($case['ao'])) == $d) {
                        ?><div>
                            <i class="fa fa-briefcase text-muted"></i> <?= Html::a($case['name'], '/cases/r/'.$case['id'], ['target'=>'_blank']) ?>
                            <? if ($case['deal_status'] == 'won' || $case['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-<?= $case['deal_status'] == 'won' ? 'success' : 'danger' ?>"></i><? } ?>
                            <? if ($case['status'] == 'closed' || $case['status'] == 'onhold') { ?><i class="fa fa-<?= $case['status'] == 'closed' ? 'lock text-muted' : 'clock-o text-warning' ?>"></i><? } ?>
                        </div><?
                        }
                    }
                    ?></td>
                    <td><?= $x[1] ?? '' ?></td>
                    <td><?= $x[2] ?? '' ?></td>
                    <td><?= $x[3] ?? '' ?>
                        <?php
                    foreach ($tasks as $task) {
                        if ($task['user_id'] == $s['id'] && date('j', strtotime($task['due_dt'])) == $d) {
                        ?><div>
                            <i class="fa fa-<?= $task['completed_dt'] != ZERO_DT ? 'check-' : '' ?>square-o text-muted"></i> <?= Html::a($task['description'], '/'.$task['rtype'].'s/r/'.$task['rid'], ['target'=>'_blank']) ?>
                            <?php if ($task['related'] && $task['rtype'] == 'case') { ?> (<?= Html::a($task['related']['name'], '/cases/r/'.$task['rid'], ['class'=>'text-muted']) ?>)<?php } ?>
                        </div><?
                        }
                    }
                    ?></td>
                </tr>
                    <?php } } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
