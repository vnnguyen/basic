<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_title'] = 'Customer Relations tasks, '.$month.' ('.count($theTours).' tours)';
Yii::$app->params['page_breadcrumbs'] = [
    ['Customers', '@web/customers'],
    ['Tasks'],
]
?>
<style>
span.task {background-color:#ccc; color:#fff; padding:0 5px;}
span.task-pc.task-done {background-color:#c60;}
span.task-bv.task-done {background-color:#009;}
span.task-ac.task-done {background-color:#060;}
span.task-ap.task-done {background-color:#600;}
span.task-sv.task-done {background-color:#066;}
</style>
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        Select month
        <?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym', 'yr'), ['class'=>'form-control', 'prompt'=>'- Select a month -']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        Tasks with names starting with PC / BV / FB / AC / A1-A7 / SV
    </form>
    <?php if (empty($theTours)) { ?>
    <?= Yii::t('x', 'No data found.') ?>
    <?php } else { ?>
    <div class="card mb-2">
        <div class="table-responsive">
            <table class="table table-narrow table-striped mb-0">
                <thead>
                    <th width="50">#</th>
                    <th width="50">In</th>
                    <th width="50">Out</th>
                    <th>Tour code & name</th>
                    <th>CR staff</th>
                    <th width="50">Days</th>
                    <th width="50">Pax</th>
                    <th width="50">PC</th>
                    <th width="50">BV</th>
                    <th width="50">AC</th>
                    <th width="250">AP (A1-A6)</th>
                    <th width="50">SV</th>              
                </thead>
                <tbody>
                    <?php $cnt = 0; $dayFrom = 0; foreach ($theTours as $tour) { ?>
                    <tr class="<?= $tour['op_finish'] == 'canceled' ? 'danger' : '' ?>">
                        <td class="text-center text-muted"><?= ++$cnt ?></td>
                        <td class="text-center"><?php
                            if (substr($tour['day_from'], -2) != $dayFrom) {
                                $dayFrom = date('j', strtotime($tour['day_from']));
                                echo $dayFrom;
                            }
                            $tourEnd = date('j', strtotime('+'.($tour['day_count'] - 1).' days', strtotime($tour['day_from'])));
                        ?>
                        </td>
                        <td class="text-center"><?= $tourEnd ?></td>
                        <td>
                            <?= Html::a($tour['op_code'].' - '.$tour['op_name'], '@web/tours/r/'.$tour['tour']['id']) ?>
                            <?php if ($tour['op_finish'] == 'canceled') { ?>(CXL)<?php } ?>
                        </td>

                        <td><?php
                        if ($tour['tour']['cskh']) {
                            foreach ($tour['tour']['cskh'] as $cskh) {
                                echo Html::a($cskh['name'], '@web/users/r/'.$cskh['id']);
                            }
                        }
                            ?></td>
                        <td class="text-center"><?= $tour['day_count'] ?></td>
                        <td class="text-center"><?= $tour['pax'] ?></td>
                        <td class="text-center">
                        <?php
                        foreach ($tour['tour']['tasks'] as $task) {
                            if (substr($task['description'],0,2) == 'PC') {
                        ?><span title="<?= date('j/n/Y', strtotime($task['due_dt'])) ?>" class="task task-pc <?= $task['status'] != 'on' ? 'task-done' : '' ?>">PC</span><?php
                                break;
                            }
                        }
                        ?>
                        </td>
                        <td class="text-center">
                        <?php
                        foreach ($tour['tour']['tasks'] as $task) {
                            if (substr($task['description'],0,2) == 'BV') {
                        ?><span title="<?= date('j/n/Y', strtotime($task['due_dt'])) ?>" class="task task-bv <?= $task['status'] != 'on' ? 'task-done' : '' ?>">BV</span><?php
                                break;
                            }
                        }
                        ?>
                        </td>
                        <td class="text-center">
                        <?php
                        foreach ($tour['tour']['tasks'] as $task) {
                            if (substr($task['description'],0,2) == 'AC') {
                        ?><span title="<?= date('j/n/Y', strtotime($task['due_dt'])) ?>" class="task task-ac <?= $task['status'] != 'on' ? 'task-done' : '' ?>">AC</span><?php
                                break;
                            }
                        }
                        ?>
                        </td>
                        <td>
                        <?php
                        foreach ($tour['tour']['tasks'] as $task) {
                            if (in_array(substr($task['description'],0,2), ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7'])) {
                        ?><span title="<?= date('j/n/Y', strtotime($task['due_dt'])) ?>" class="task task-ap <?= $task['status'] != 'on' ? 'task-done' : '' ?>"><?= substr($task['description'],0,2) ?></span> <?php
                                //break;
                            }
                        }
                        ?>
                        </td>
                        <td>
                        <?php
                        foreach ($tour['tour']['tasks'] as $task) {
                            if (substr($task['description'], 0, 2) == 'SV') {
                        ?><span title="<?= date('j/n/Y', strtotime($task['due_dt'])) ?>" class="task task-sv <?= $task['status'] != 'on' ? 'task-done' : '' ?>">SV</span><?php
                                break;
                            }
                        }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</div>
