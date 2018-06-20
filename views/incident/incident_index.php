<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_incident_inc.php');

Yii::$app->params['page_title'] = Yii::t('incident', 'Tour incidents').' ('.number_format($pagination->totalCount).')';
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
            <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('incident', '- Year -')]) ?>
            <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('incident', '- Month -')]) ?>
            <?= Html::dropdownList('type', $type, $incidentTypeList, ['class'=>'form-control', 'prompt'=>Yii::t('incident', '- Type -')]) ?>
            <?= Html::dropdownList('severity', $severity, $severityList, ['class'=>'form-control', 'prompt'=>Yii::t('incident', '- Severity -')]) ?>
            <?= Html::dropdownList('status', $status, $incidentStatusList, ['class'=>'form-control', 'prompt'=>Yii::t('incident', '- Status -')]) ?>
            <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>Yii::t('incident', 'Name')]) ?>
            <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
        </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th class="text-center"><?= Yii::t('incident', 'Date') ?></th>
                        <th><?= Yii::t('incident', 'Type') ?></th>
                        <th><?= Yii::t('incident', 'Severity') ?></th>
                        <th><?= Yii::t('incident', 'Incident') ?></th>
                        <th><?= Yii::t('incident', 'Location') ?></th>
                        <th><?= Yii::t('incident', 'Tour') ?></th>
                        <th><?= Yii::t('incident', 'In charge') ?></th>
                        <th><?= Yii::t('incident', 'Status') ?></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theIncidents as $incident) { ?>
                    <tr>
                        <td class="text-center"><?= date('j/n/Y', strtotime($incident['incident_date'])) ?></td>
                        <td><?= $incidentTypeList[$incident['stype']] ?? $incident['stype'] ?></td>
                        <td class="text-nowrap"><?= str_repeat('<i class="fa fa-bomb text-danger"></i>', $incident['severity']) ?><?= str_repeat('<i class="fa fa-bomb" style="color:#eee"></i>', 5 - $incident['severity']) ?></td>
                        <td><?= Html::a($incident['name'], '@web/products/op/'.$incident['tour']['id'], ['title'=>Html::encode($incident['description'])]) ?></td>
                        <td><?= $incident['incident_location'] ?></td>
                        <td><?= Html::a($incident['tour']['op_code'].' - '.$incident['tour']['op_name'], '/products/op/'.$incident['tour']['id'], ['target'=>'_blank']) ?></td>
                        <td><?= $incident['owner']['name'] ?></td>
                        <td><?= $incidentStatusList[$incident['status']] ?? $incident['status'] ?></td>
                        <td><?= Html::a(Yii::t('app', 'Edit'), '/incidents/u/'.$incident['id']) ?></td>
                        <td><?= Html::a(Yii::t('app', 'Add complaint'), '/complaint/c?incident='.$incident['id']) ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

