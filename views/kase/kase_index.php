<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_kase_inc.php');
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
    ['Sales', '@web'],
    ['Cases', '@web/cases'],
];

$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
    ],
];

$this->title = 'B2C cases ('.number_format($pages->totalCount, 0).')';

$caseHowFoundList = [
    'returning'=>'Returning',
        'returning/customer'=>'Returning customer',
        'returning/contact'=>'Returning contact (not a customer)',
    'new'=>'New',
        'new/nref'=>'Not referred',
            'new/nref/web'=>'Web',
            'new/nref/print'=>'Book/Print',
            'new/nref/event'=>'Event/Seminar',
            'new/nref/other'=>'Other', // travel agent, by chance
        'new/ref'=>'Referred',
            'new/ref/customer'=>'Referred by one of Amica\'s customer',
            'new/ref/amica'=>'Referred by one of Amica\'s staff',
            'new/ref/org'=>'Referred by an organization or one of its members', // Ca nhan, to chuc
            'new/ref/other'=>'Referred from other source',
];

?>
<style type="text/css">
.bg-prospect-5 {background-color:#930;}
.bg-prospect-4 {background-color:#e60;}
.bg-prospect-3 {background-color:#f80;}
.bg-prospect-2 {background-color:#fb8;}
.bg-prospect-1 {background-color:#fdb;}
.bg-prospect-0 {background-color:#fff;}
</style>
<div class="col-md-12">
    <form method="get" action="" class="form-inline panel-search">
        <select class="form-control" name="ca">
            <option value="created">Created in</option>
            <option value="assigned" <?=$getCa == 'assigned' ? 'selected="selected"' : '' ?>>Assigned in</option>
            <option value="closed" <?=$getCa == 'closed' ? 'selected="selected"' : '' ?>>Closed in</option>
        </select>
        <select class="form-control" name="month">
            <option value="all">All months</option>
            <? foreach ($monthList as $mo) { ?>
            <option value="<?= $mo['ym'] ?>" <?= $mo['ym'] == $getMonth ? 'selected="selected"' : '' ?>><?= $mo['ym'] ?></option>
            <? } ?>
        </select>
        <select class="form-control" name="language">
            <option value="all">All languages</option>
            <option value="en" <?= $getLanguage == 'en' ? 'selected="selected"' : ''?>>English</option>
            <option value="fr" <?= $getLanguage == 'fr' ? 'selected="selected"' : ''?>>Francais</option>
            <option value="vi" <?= $getLanguage == 'vi' ? 'selected="selected"' : ''?>>Tiếng Việt</option>
        </select>
        <select class="form-control" name="is_priority">
            <option value="all">Priority status</option>
            <option value="yes" <?= $getPriority == 'yes' ? 'selected="selected"' : ''?>>Priority</option>
            <option value="no" <?= $getPriority == 'no' ? 'selected="selected"' : ''?>>Non-priority</option>
        </select>
        <select class="form-control" name="status">
            <option value="all">Open status</option>
            <? foreach (['open'=>'Open', 'onhold'=>'On hold', 'closed'=>'Closed'] as $alias=>$status) { ?>
            <option value="<?= $alias ?>" <?= $alias == $getStatus ? 'selected="selected"' : '' ?>><?= $status ?></option>
            <? } ?>
        </select>
        <select class="form-control" name="sale_status">
            <option value="all">Sales status</option>
            <? foreach (['pending'=>'Pending', 'won'=>'Won', 'lost'=>'Lost'] as $alias=>$status) { ?>
            <option value="<?= $alias ?>" <?= $alias == $getSaleStatus ? 'selected="selected"' : '' ?>><?= $status ?></option>
            <? } ?>
        </select>
        <select class="form-control" name="owner_id">
            <option value="all">All owners</option>
            <optgroup label="Sellers in Vietnam">
                <? foreach ($ownerList as $case) { ?>
                <option value="<?= $case['id'] ?>" <?= $case['id'] == $getOwnerId ? 'selected="selected"' : '' ?>><?= $case['lname'] ?>, <?= $case['email'] ?></option>
                <? } ?>
            </optgroup>
            <optgroup label="Sellers in France">
                <option value="cofr-13" <?= 'cofr-13' == $getOwnerId ? 'selected="selected"' : '' ?>>Hoa (Hoa Bearez)</option>
                <option value="cofr-5246" <?= 'cofr-5246' == $getOwnerId ? 'selected="selected"' : '' ?>>Arnaud (Arnaud Levallet)</option>
                <option value="cofr-1769" <?= 'cofr-1769' == $getOwnerId ? 'selected="selected"' : '' ?>>Trân (Cao Lê Trân)</option>
                <option value="cofr-767" <?= 'cofr-767' == $getOwnerId ? 'selected="selected"' : '' ?>>Cô Xuân (Vương Thị Xuân)</option>
                <option value="cofr-688" <?= 'cofr-688' == $getOwnerId ? 'selected="selected"' : '' ?>>Frédéric (Frédéric Hoeckel)</option>
            </optgroup>
        </select>
        <input type="text" class="form-control" name="name" value="<?= $getName ?>" placeholder="Search name" autocomplete="off">
        <?= Html::dropdownList('prospect', $getProspect, [
            'all'=>'Prospect',
            '1'=>'1 star',
            '2'=>'2 stars',
            '3'=>'3 stars',
            '4'=>'4 stars',
            '5'=>'5 stars',
        ], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('device', $getDevice, [
            'all'=>'Device',
            'desktop'=>'desktop',
            'tablet'=>'tablet',
            'mobile'=>'mobile',
            'none'=>'none',
        ], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('site', $getSite, [
            'all'=>'Contact via site',
            'fr'=>'FR',
            'vac'=>'VAC',
            'val'=>'VAL',
            'vpc'=>'VPC',
            'ami'=>'AMI',
            'en'=>'EN',
        ], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('contacted', $contacted, $caseHowContactedListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('k', 'How customer contacted us')]) ?>
        <?= Html::dropdownList('found', $found, $caseHowFoundListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('k', 'How customer found us')]) ?>
        <select class="form-control" name="campaign_id">
            <option value="all">Campaigns</option>
            <option value="0"  <?= $getCampaignId == '0' ? 'selected="selected"' : '' ?>>No campaign</option>
            <option value="yes"  <?= $getCampaignId == 'yes' ? 'selected="selected"' : '' ?>>Any campaign</option>
            <? foreach ($campaignList as $case) { ?>
            <option value="<?= $case['id'] ?>" <?= $case['id'] == $getCampaignId ? 'selected="selected"' : '' ?>><?= date('d/m/Y', strtotime($case['start_dt'])) ?>: <?= $case['name'] ?></option>
            <? } ?>
        </select>
        <input type="text" class="form-control" name="destination" value="<?= $getReq_countries ?>" placeholder="Search countries visit" autocomplete="off">
        <input type="text" class="form-control" name="from_date" value="<?= $getFromDate ?>" placeholder="Search Avail. time" autocomplete="off">
        <input type="text" class="form-control" name="number_day" value="<?= $getNumberDay ?>" placeholder="Search number days" autocomplete="off">
        <input type="text" class="form-control" name="number_pax" value="<?= $getNumberPax ?>" placeholder="Search number paxs" autocomplete="off">
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '@web/cases') ?>
    </form>
    <style>
    .form-inline .form-control {margin-bottom:4px;}
    .form-inline input.form-control {margin-bottom:4px!important;}
    </style>
    <? if (empty($theCases)) { ?><p>No cases found.</p><? } else { ?>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= $getCa == 'created' ? 'Created' : 'Assigned' ?></th>
                        <th>Case name</th>
                        <th>Owner & assign date</th>
                        <th>Source</th>
                        <th>req_countries</th>
                        <th>Avail. time</th>
                        <th>Days</th>
                        <th>Pax</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                <? if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><? } ?>
                <? foreach ($theCases as $case) { ?>
                    <tr>
                        <td>
                            <a title="<?=Yii::t('mn', 'Edit')?>" rel="external" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-nowrap"><?= str_replace('/'.date('Y'), '', date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y H:i')) ?></td>
                        <td class="text-nowrap">
                            <? if ($case['stats']['prospect'] != 0 && $case['stats']['prospect'] != '') { ?>
                            <a href="?prospect=<?= $case['stats']['prospect'] ?>" class="badge bg-prospect-<?= $case['stats']['prospect'] ?>"><?= $case['stats']['prospect'] ?></a>
                            <? } ?>
                            <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
                            <? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
                            <? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
                            <? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
                            <? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
                        </td>
                        <td class="text-nowrap">
                            <img class="img-circle" src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px">
                            <?=Html::a($case['owner']['nickname'], '?owner_id='.$case['owner']['id'])?>
                            <span class="text-muted"><?= str_replace('/'.date('Y'), '', date('j/n/Y', strtotime($case['ao']))) ?></span>
                        </td>
                        <td class="text-nowrap">
                            <?= $case['campaign_id'] != 0 ? '<span class="label label-info">C</span> ' : '' ?>
                            <span title="Contacted: <?= $caseHowContactedList[$case['how_contacted']] ?? $case['how_contacted'] ?>"><?= strtoupper(substr(strrchr($case['how_contacted'], '/'), 1, 1)) ?></span>
                            <?php if ($case['web_keyword'] != '') { ?>
                            <span class="text-pink"><?= $case['web_keyword'] ?></span>
                            <?php } ?>
                            &middot;
                            <span title="Found: <?= $caseHowFoundList[$case['how_found']] ?? $case['how_found'] ?>"><?= strtoupper(substr(strrchr($case['how_found'], '/'), 1, 1)) ?></span>
                            <? if (substr($case['how_found'], 0, 7) == 'new/ref') { ?>
                            <?= Html::a($case['referrer']['name'], '@web/persons/r/'.$case['ref'], ['rel'=>'external']) ?>
                            <? } ?>
                        </td>
                        <td><?= $case['stats']['req_countries'] ?></td>
                        <? if ($case['stats']['req_countries'] != '') { ?>
                        <td class="text-center"><?= $case['stats']['pa_start_date'] ?></td>
                        <td class="text-center"><?= $case['stats']['day_count'] ?></td>
                        <td class="text-center"><?= $case['stats']['pax_count'] ?></td>
                        <? } else { ?>
                        <td colspan="3"  class="text-center"><?= Html::a('Edit request', '@web/cases/request/'.$case['id']) ?></td>
                        <? } ?>
                        <td>
                            <? if ($case['info'] != '') { ?>
                            <i title="<?= Html::encode($case['info']) ?>" class="fa fa-info-circle"></i>
                            <? } ?>
                            <? if ($case['status'] == 'closed' && $case['deal_status'] != 'won') { ?>
                            <i title="<?= Html::encode($case['closed_note']) ?>" class="fa fa-exclamation-circle text-danger"></i>
                            <? } ?>
                        </td>
                    </tr>

                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pages->pageSize < $pages->totalCount) { ?>
        <div class="panel-footer text-center">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]);?>
        </div>

    </div>
    <? } ?>
    <? } ?>
</div>
