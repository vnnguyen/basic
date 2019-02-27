<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\widgets\LinkPager;

include('_kase_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_icon'] = 'briefcase';
Yii::$app->params['page_title'] = 'B2B cases ('.number_format($pages->totalCount, 0).')';

$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
    ],
];

$orderbyList = [
    'created'=>'Order by Created',
    'updated'=>'Order by Updated',
];

?>
<style>
.bootstrap-select.form-control:not([class*=col-]) {width:300px;}
</style>
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        <select class="form-control" name="ca">
            <option value="created">Created in</option>
            <option value="assigned" <?=$getCa == 'assigned' ? 'selected="selected"' : '' ?>>Assigned in</option>
        </select>
        <select class="form-control" name="month">
            <option value="all">All months</option>
            <?php foreach ($monthList as $mo) { ?>
            <option value="<?= $mo['ym'] ?>" <?= $mo['ym'] == $getMonth ? 'selected="selected"' : '' ?>><?= $mo['ym'] ?></option>
            <?php } ?>
        </select>
        <?= Html::dropdownList('ym', $ym, ['m'=>'View month', 'y'=>'View year'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('type', $type, $kaseTypeList, ['class'=>'form-control', 'prompt'=>'Type']) ?>
        <?= Html::dropdownList('language', $language, $kaseLanguageList, ['class'=>'form-control', 'prompt'=>'Language']) ?>
        <select class="form-control" name="company">
            <option value="all">TO / TA</option>
            <?php foreach ($companyList as $company) { ?>
            <option value="<?= $company['id'] ?>" <?= $getCompany == $company['id'] ? 'selected="selected"' : ''?>><?= $company['name'] ?></option>
            <?php } ?>
        </select>
        <select class="form-control" name="is_priority">
            <option value="all">Priority status</option>
            <option value="1" <?= $getPriority == '1' ? 'selected="selected"' : ''?>>1-Priority</option>
            <option value="2" <?= $getPriority == '2' ? 'selected="selected"' : ''?>>2-Priority</option>
            <option value="3" <?= $getPriority == '3' ? 'selected="selected"' : ''?>>3-Priority</option>
            <option value="4" <?= $getPriority == '4' ? 'selected="selected"' : ''?>>4-Priority</option>
            <option value="yes" <?= $getPriority == 'yes' ? 'selected="selected"' : ''?>>Priority</option>
            <option value="no" <?= $getPriority == 'no' ? 'selected="selected"' : ''?>>Non-priority</option>
        </select>
        <select class="form-control" name="status">
            <option value="all">Open status</option>
            <?php foreach (['open'=>'Open', 'onhold'=>'On hold', 'closed'=>'Closed'] as $alias=>$status) { ?>
            <option value="<?= $alias ?>" <?= $alias == $getStatus ? 'selected="selected"' : '' ?>><?= $status ?></option>
            <?php } ?>
        </select>
        <select class="form-control" name="sale_status">
            <option value="all">Sales status</option>
            <?php foreach (['pending'=>'Pending', 'won'=>'Won', 'lost'=>'Lost'] as $alias=>$status) { ?>
            <option value="<?= $alias ?>" <?= $alias == $getSaleStatus ? 'selected="selected"' : '' ?>><?= $status ?></option>
            <?php } ?>
        </select>
        <select class="form-control" name="owner_id">
            <option value="all">All owners</option>
            <?php foreach ($ownerList as $case) { ?>
            <option value="<?= $case['id'] ?>" <?= $case['id'] == $getOwnerId ? 'selected="selected"' : '' ?>><?= $case['name'] ?>, <?= $case['email'] ?></option>
            <?php } ?>
        </select>
        <input type="text" class="form-control" name="name" value="<?= $getName ?>" placeholder="Search name" autocomplete="off">
        <?= Html::dropdownList('orderby', $orderby, $orderbyList, ['class'=>'form-control']) ?>

        <?= Html::textInput('paxcount', $paxcount, ['class'=>'form-control', 'placeholder'=>'Pax, eg. 10-20']) ?>
        <?= Html::textInput('daycount', $daycount, ['class'=>'form-control', 'placeholder'=>'Days, eg. 10-20']) ?>
        <?= Html::textInput('startdate', $startdate, ['class'=>'form-control', 'placeholder'=>'Start, eg. 2017-06']) ?>
        <?= Html::dropdownList('dests[]', $dests, ArrayHelper::map($kaseDestList, 'code', 'name_en'), ['class'=>'form-control select2', 'multiple'=>'multiple', 'style'=>'width:300px;', 'title'=>'Countries']) ?>
        <?= Html::dropdownList('destselect', $destselect, ['all'=>'All selected countries', 'any'=>'Any selected countries', 'only'=>'Only selected countries'], ['class'=>'form-control']) ?>

        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '@web/b2b/cases') ?>
    </form>

    <?php if (empty($theCases)) { ?>
    <div class="alert alert-danger">No cases found.</div>
    <?php } else { ?>
    <div class="card table-responsive">
        <table class="table table-striped table-narrow">
            <thead>
                <tr>
                    <th width="20"></th>
                    <th class="text-center"><?= $getCa == 'created' ? Yii::t('x', 'Created') : Yii::t('x', 'Assigned') ?></th>
                    <th><?= Yii::t('x', 'Type') ?></th>
                    <th><?= Yii::t('x', 'Name of file') ?></th>
                    <th><?= Yii::t('x', 'No. pax, days') ?></th>
                    <th><?= Yii::t('x', 'Dest.') ?></th>
                    <th></th>
                    <th><?= Yii::t('x', 'Owner') ?></th>
                    <th><?= Yii::t('x', 'TO/TA') ?></th>
                    <th><?= Yii::t('x', 'Last active') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($theCases)) { ?>
                <tr><td colspan="10">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><?php } ?>
                <?php foreach ($theCases as $case) { ?>
                <tr>
                    <td>
                        <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
                    </td>
                    <td class="text-nowrap text-center"><?= str_replace('/'.date('Y'), '', date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y')) ?></td>
                    <td>
                        <?php if ($case['stype'] == 'b2b') { ?><span class="">Request</span><?php } ?>
                        <?php if ($case['stype'] == 'b2b-prod') { ?><span class="text-violet">Prod</span><?php } ?>
                        <?php if ($case['stype'] == 'b2b-series') { ?><span class="text-pink">Series</span><?php } ?>
                    </td>
                    <td class="text-nowrap">
                        <?php if (in_array($case['is_priority'], [1,2,3,4])) { ?><span class="text-orange-<?= $case['is_priority'] + 4 ?>00 font-weight-bold" title="<?= Yii::t('x', 'Priority') ?>"><?//= $case['is_priority'] ?><?= str_repeat('<i class="fa fa-caret-right"></i>', $case['is_priority']) ?></span><?php } ?>
                        <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
                        <?php if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><?php } ?>
                        <?php if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><?php } ?>
                        <?php if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><?php } ?>
                        <?php if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><?php } ?>
                    </td>
                    <?php if ($case['stats']) { ?>
                    <td>
                        <span class="text-muted">
                            <?= $case['stats']['pax_count'] == '' ? '' : $case['stats']['pax_count'].'p' ?>
                            <?= $case['stats']['day_count'] == '' ? '' : $case['stats']['day_count'].'d' ?>
                            <?= $case['stats']['start_date'] == '' ? '' : implode('/', array_reverse(explode('-', $case['stats']['start_date']))) ?>
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <?php
                        if ($case['stats']['req_countries'] != '') {
                            foreach (explode('|', $case['stats']['req_countries']) as $reqCountry) {
                                echo '<span title="" class="flag-icon flag-icon-', strtolower($reqCountry), '"></span>';
                            }
                        }
                        ?>
                    </td>
                    <?php } else { ?>
                    <td colspan="2"></td>
                    <?php } ?>
                    <td><?php if ($case['info'] != '') { ?><i title="<?= Html::encode($case['info']) ?>" class="fa fa-comment-o"></i><?php } ?></td>
                    <td class="text-nowrap">
                        <img src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:24px; height:24px" class="rounded-circle">
                        <?=Html::a($case['owner']['name'], '?owner_id='.$case['owner']['id'])?>
                    </td>
                    <td class="text-nowrap">
                        <?= Html::a($case['company']['name'], '?company='.$case['company_id']) ?>
                    </td>
                    <td><?= $case['last_accessed_dt'] == ZERO_DT ? '' : Yii::$app->formatter->asRelativetime($case['last_accessed_dt']) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pages,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]);?>

    <?php } ?>
</div>
<?php

// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs("
    $('.select2').select2();
    // $('.selectpicker').selectpicker();
");