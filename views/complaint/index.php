<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_complaint_inc.php');

Yii::$app->params['page_title'] = Yii::t('complaint', 'Tour complaints').' ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'bomb';
Yii::$app->params['body_class'] = 'sidebar-xs';

for ($y = date('Y'); $y >= 2010; $y --) {
    $yearList[$y] = $y;
}

for ($m = 1; $m <= 12; $m ++) {
    $monthList[$m] = $m;
}

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
        <form class="form-inline">
            <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('complaint', '- Year -')]) ?>
            <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('complaint', '- Month -')]) ?>
            <?= Html::dropdownList('type', $type, $complaintTypeList, ['class'=>'form-control', 'prompt'=>Yii::t('complaint', '- Type -')]) ?>
            <?= Html::dropdownList('status', $status, $complaintStatusList, ['class'=>'form-control', 'prompt'=>Yii::t('complaint', '- Status -')]) ?>
            <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>Yii::t('complaint', 'Name')]) ?>
            <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
        </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><?= Yii::t('complaint', 'Date') ?></th>
                        <th><?= Yii::t('complaint', 'Type') ?></th>
                        <th><?= Yii::t('complaint', 'Incident') ?></th>
                        <th><?= Yii::t('complaint', 'Complaint') ?></th>
                        <th><?= Yii::t('complaint', 'Tour') ?></th>
                        <th><?= Yii::t('complaint', 'In charge') ?></th>
                        <th><?= Yii::t('complaint', 'Status') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theComplaints as $complaint) { ?>
                    <tr>
                        <td class="text-center"><?= date('j/n/Y', strtotime($complaint['complaint_date'])) ?></td>
                        <td><?= $complaintTypeList[$complaint['stype']] ?? $complaint['stype'] ?></td>
                        <td class="text-nowrap">name incident</td>
                        <td><?= Html::a($complaint['name'], '@web/products/op/'.$complaint['tour']['id'], ['title'=>Html::encode($complaint['description'])]) ?></td>
                        <td><?= Html::a($complaint['tour']['op_code'].' - '.$complaint['tour']['op_name'], '/products/op/'.$complaint['tour']['id'], ['target'=>'_blank']) ?></td>
                        <td><?= $complaint['owner']['name'] ?></td>
                        <td><?= $complaintStatusList[$complaint['status']] ?? $complaint['status'] ?></td>
                        <td><?= Html::a(Yii::t('app', 'Edit'), '/complaints/u/'.$complaint['id']) ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

