<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\widgets\LinkPager;

include('_qhkh_inc.php');

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>Yii::t('x', '+New'), 'link'=>'qhkh/service-plus?action=add', 'active'=>Yii::$app->request->get('action') == 'c'],
    ],
];

Yii::$app->params['page_title'] = Yii::t('x', 'Services Plus');
Yii::$app->params['page_icon'] = 'heart';

?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control'.($view == 'tour' ? ' d-none' : ''), 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control'.($view == 'tour' ? ' d-none' : ''), 'prompt'=>Yii::t('x', 'Month')]) ?>
        <?= Html::textInput('tour', $tour, ['class'=>'form-control'.($view == 'tour' ? '' : ' d-none'), 'placeholder'=>Yii::t('x', 'Tour code/name')]) ?>
        <?= Html::dropdownList('success', $success, $successList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Success')]) ?>
        <?= Html::dropdownList('qhkh', $qhkh, $qhkhList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Staff')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="16"></th>
                        <th><?= Yii::t('x', 'Date')?></th>
                        <th><?= Yii::t('x', 'Service Plus')?></th>
                        <th><?= Yii::t('x', 'Cost')?></th>
                        <th class="text-nowrap"><?= Yii::t('x', 'Updated')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theServices as $cnt=>$service) { ?>
                    <tr>
                        <td style="vertical-align:top;" class="text-muted text-center"><?= ++ $cnt ?></td>
                        <td style="vertical-align:top;" class="text-center">
                            <?= $service['svc_date'] == '0000-00-00' ? date('j/n/Y', strtotime($service['end_date'])).'<sup style="cursor:help" title="Calculated date">(?)</sup>' : date('j/n/Y', strtotime($service['svc_date'])) ?>
                            <div>
                                <?php if ($service['svc_success'] == '') { ?><i class="fa fa-meh-o text-muted fa-3x"></i><?php } ?>
                                <?php if ($service['svc_success'] == 'yes') { ?><i class="fa fa-smile-o text-success fa-3x"></i><?php } ?>
                                <?php if ($service['svc_success'] == 'no') { ?><i class="fa fa-frown-o text-danger fa-3x"></i><?php } ?>
                            </div>
                            <?php if (in_array(USER_ID, [1, 29123, $service['created_by'], $service['updated_by']])) { ?>
                            <div class="dropdown">
                                <a href="#" class="text-muted" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu">
                                    <?php if ($service['svc_success'] == '' || $service['svc_success'] == 'no') { ?><?= Html::a('<i class="fa fa-smile-o"></i> Result: OK', '?action=ok&id='.$service['id'], ['class'=>'dropdown-item text-success']) ?><?php } ?>
                                    <?php if ($service['svc_success'] == '' || $service['svc_success'] == 'yes') { ?><?= Html::a('<i class="fa fa-frown-o"></i> Result: Not OK', '?action=nok&id='.$service['id'], ['class'=>'dropdown-item text-danger']) ?><?php } ?>
                                    <div class="dropdown-divider"></div>
                                    <?= Html::a('<i class="fa fa-edit"></i> '.Yii::t('x', 'Edit'), '/qhkh/service-plus?action=edit&id='. $service['id'], ['class'=>'dropdown-item']) ?>
                                </div>
                            </div>
                            <?php } ?>
                        </td>
                        <td>
                            <strong><?= Yii::t('x', 'Tour') ?>:</strong> <?= Html::a($service['tour']['op_code'].' - '.$service['tour']['op_name'], '/products/op/'.$service['tour']['id'], ['target'=>'_blank']) ?> <span class="text-muted"><?= $service['tour']['day_count'] ?>d <?= date('j/n/Y', strtotime($service['day_from'])) ?>-<?= date('j/n/Y', strtotime($service['end_date'])) ?></span>
                            <br><i class="fa fa-question-circle-o text-info position-left"></i> <strong><?= Yii::t('x', 'Reason') ?>:</strong> <?= $service['context']?>
                            <br><i class="fa fa-heart-o text-pink position-left"></i> <strong><?= Yii::t('x', 'Service') ?>:</strong> <?= $service['sv']?>
                            <br><i class="fa fa-smile-o text-violet position-left"></i> <strong><?= Yii::t('x', 'Result') ?>:</strong> <?= $service['result']?>
                        </td>
                        <td style="vertical-align:top;"><?= $service['cp']?></td>
                        <td style="vertical-align:top;" title="<?= Yii::$app->formatter->asRelativetime($service['updated_dt']) ?>"><?= $service['updatedBy']['name'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>

</div>
<?php

$js = <<<'TXT'
$('select[name="view"]').on('change', function(){
    var val = $(this).val()
    if (val == 'tour') {
        $('select[name="year"], select[name="month"]').addClass('d-none')
        $('input[name="tour"]').removeClass('d-none')
    } else {
        $('select[name="year"], select[name="month"]').removeClass('d-none')
        $('input[name="tour"]').addClass('d-none')
    }
})
TXT;

$this->registerJs($js);