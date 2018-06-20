<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

include('_inquiries_inc.php');

$this->title = 'Web inquiries ('.$pages->totalCount.')';
$this->params['icon'] = 'keyboard-o';
$this->params['breadcrumb'] = [
    ['Communication', '@web/inquiries'],
    ['Inquiries'],
];

?>
<div class="col-md-12">
    <form class="form-inline panel-search">
        <select class="form-control" name="month">
            <option value="all">Month</option>
            <? foreach ($monthList as $li) { ?>
            <option value="<?= $li['ym'] ?>" <?= $li['ym'] == $getMonth ? 'selected="selected"' : '' ?>><?= $li['ym'] ?></option>
            <? } ?>
        </select>
        <select class="form-control" name="form">
            <option value="all">Site / Form</option>
            <? foreach ($formList as $li) { ?>
            <option value="<?= $li['form_name'] ?>" <?= $li['form_name'] == $getForm ? 'selected="selected"' : ''?>><?= $li['form_name'] ?></option>
            <? } ?>
        </select>
        <?= Html::dropdownList('case_id', $getCaseId, ['all'=>'In a case?', 'yes'=>'In a case', 'no'=>'Not in a case'], ['class'=>'form-control']) ?>
        <?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '@web/manager/inquiries') ?>
    </form>
    <? if (empty($models)) { ?>
    <p>No inquiries found. New entries will appear here as soon as someone submits a web form on our site.</p>
    <? } else { ?>
    <div class="table-responsive bg-white">
        <table class="table table-xxs table-striped table-hover">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Name & Email</th>
                    <th>IP, Referrer host & Form</th>
                    <th>Tour & RDV</th>
                    <th>Linked case</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($models as $inquiry) {
                $eData = @unserialize($inquiry['data']);
                if (!isset($eData['country'])) $eData['country'] = '';
                ?>
                <tr>
                    <td class="text-nowrap"><?= DateTimeHelper::convert($inquiry['created_at'], 'j/n/Y H:i', 'UTC', Yii::$app->user->identity->timezone)?></td>
                    <td class="text-nowrap">
                        <span class="flag-icon flag-icon-<?= $eData['country'] ?>"></span>
                        <?=Html::a($inquiry['name'], '@web/inquiries/r/'.$inquiry['id'])?>
                        <span class="text-muted"><?= $inquiry['email']?></span>
                    </td>
                    <td class="text-nowrap">
                        <?= Html::a('<i class="fa fa-globe"></i>', 'http://whatismyipaddress.com/ip/'.$inquiry['ip'], ['target'=>'_blank', 'title'=>$inquiry['ip']]) ?>
                        <?= ltrim(parse_url($inquiry['ref'], PHP_URL_HOST), 'www.') ?>
                        &rarr; <?= $inquiry['form_name']?>
                    </td>
                    <td>
                        <? if (isset($eData['tourName']) && isset($eData['tourUrl']) && strlen($eData['tourName']) > 0) { ?>
                        <?= Html::a($eData['tourName'], $eData['tourUrl'], ['rel'=>'external']) ?>
                        <? } ?>
                        <? if (isset($eData['callback']) && $eData['callback'] == 'Oui') { ?>
                        <?= $eData['callDate'] ?> @<?= $eData['callTime'] ?>
                        <? } ?>
                    </td>
                    <td class="text-nowrap">
                        <? if (isset($inquiry['kase']) && $inquiry['case_id'] != 0) { ?>
                        <i class="fa fa-briefcase text-muted"></i>
                        <?= Html::a($inquiry['kase']['name'], '@web/cases/r/'.$inquiry['case_id'])?>
                        <? if ($inquiry['kase']['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
                        <? if ($inquiry['kase']['status'] == 'onhold') { ?><i class="fa fa-clock-o text-warning"></i><? } ?>
                        <? if ($inquiry['kase']['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
                        <? if ($inquiry['kase']['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
                        <? if (isset($inquiry['kase']['owner'])) { ?><?= $inquiry['kase']['owner']['name'] ?><? } ?>
                        <? } else { ?>
                        <?= Html::a('+Case', '@web/cases/c?from=inquiry&id='.$inquiry['id'], ['style'=>'color:red']) ?>
                        <? } ?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-20">
        <?=LinkPager::widget(array(
        'pagination' => $pages,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
        ));?>
    </div>
    <? } // if empty inquiries ?>
</div>
