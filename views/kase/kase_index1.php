<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
    ['Sales', '@web'],
    ['Cases', '@web/cases'],
];
$cnt = 0;
if ($cnt > 0) {
    $info_email = [['icon'=>'envelope-o', 'label'=>'Unknown email <span class="badge" id="unk_mail">'.$cnt.'</span>', 'link'=>'cases/unk_email', 'active'=>SEG2 == 'c'],];
} else {
    $info_email = null;
}

$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
    ],
    $info_email
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

$caseHowContactedList = [
    'web'=>'Web',
        'web/adwords'=>'Adwords',
            'web/adwords/google'=>'Google Adwords',
            'web/adwords/bing'=>'Bing Ads',
            'web/adwords/other'=>'Other',
        'web/search'=>'Search',
            'web/search/google'=>'Google search',
            'web/search/bing'=>'Bing search',
            'web/search/yahoo'=>'Yahoo! search',
            'web/search/other'=>'Other',
        'web/link'=>'Referral',
            'web/link/360'=>'Blog 360',
            'web/link/facebook'=>'Facebook',
            'web/link/other'=>'Other',
        'web/ad'=>'Ad online',
            'web/ad/facebook'=>'Facebook',
            'web/ad/voyageforum'=>'VoyageForum',
            'web/ad/routard'=>'Routard',
            'web/ad/sitevietnam'=>'Site-Vietnam',
            'web/ad/other'=>'Other',
        'web/email'=>'Mailing',
        'web/direct'=>'Direct access',

    'nweb'=>'Non-web',
        'nweb/phone'=>'Phone',
        'nweb/email'=>'Email',
            'nweb/email/tripconn'=>'TripConnexion',
            'nweb/email/other'=>'Other',
        'nweb/walk-in'=>'Walk-in',
        'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

        // 'nweb/agent'=>'Via a tour company ?', // OLD?
];

?>
<style type="text/css">
#unk_mail {background: red; color: #fff; font-weight: bold; opacity: 0.8}
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
        <select class="form-control" name="contacted">
            <option value="all">How customer contacted us</option>
            <option value="link" <?= $getHowContacted == 'link' ? 'selected="selected"' : ''?>>Link</option>
            <option value="web" <?= $getHowContacted == 'web' ? 'selected="selected"' : ''?>>Web inquiry</option>
            <option value="web-direct" <?= $getHowContacted == 'web-direct' ? 'selected="selected"' : ''?>>- Direct web access</option>
            <option value="web-bingad" <?= $getHowContacted == 'web-bingad' ? 'selected="selected"' : ''?>>- Bing Ad</option>
            <option value="web-adsense" <?= $getHowContacted == 'web-adsense' ? 'selected="selected"' : ''?>>- Adsense</option>
            <option value="web-adwords" <?= $getHowContacted == 'web-adwords' ? 'selected="selected"' : ''?>>- Adwords</option>
            <option value="web-adwords-amica" <?= $getHowContacted == 'web-adwords-amica' ? 'selected="selected"' : ''?>>- - Adwords Amica</option>
            <option value="web-otherad" <?= $getHowContacted == 'web-otherad' ? 'selected="selected"' : ''?>>- Other Ad</option>
            <option value="web-search" <?= $getHowContacted == 'web-search' ? 'selected="selected"' : ''?>>- Search</option>
            <option value="web-search-amica" <?= $getHowContacted == 'web-search-amica' ? 'selected="selected"' : ''?>>- - Search Amica</option>
            <option value="web-trip-connexion" <?= $getHowContacted == 'web-trip-connexion' ? 'selected="selected"' : ''?>>- Via TripConnexion</option>
            <option value="email" <?= $getHowContacted == 'email' ? 'selected="selected"' : ''?>>Email</option>
            <option value="phone" <?= $getHowContacted == 'phone' ? 'selected="selected"' : ''?>>Phone</option>
            <option value="direct" <?= $getHowContacted == 'direct' ? 'selected="selected"' : ''?>>In person</option>
            <option value="agent" <?= $getHowContacted == 'agent' ? 'selected="selected"' : ''?>>Via a travel agency</option>
            <option value="social" <?= $getHowContacted == 'social' ? 'selected="selected"' : ''?>>Social media</option>
            <option value="other" <?= $getHowContacted == 'other' ? 'selected="selected"' : ''?>>Other</option>
            <option value="unknown" <?= $getHowContacted == 'unknown' ? 'selected="selected"' : ''?>>Not known / Not recorded</option>
        </select>

        <select class="form-control" name="found">
            <option value="all">How customer knew about us</option>
            <option value="web" <?= $getHowFound == 'web' ? 'selected="selected"' : ''?>>Web search/ad</option>
            <option value="print" <?= $getHowFound == 'print' ? 'selected="selected"' : ''?>>Press / print</option>
            <option value="event" <?= $getHowFound == 'event' ? 'selected="selected"' : ''?>>Event / Seminar</option>
            <option value="word" <?= $getHowFound == 'word' ? 'selected="selected"' : ''?>>Word of mouth</option>
            <option value="returning" <?= $getHowFound == 'returning' ? 'selected="selected"' : ''?>>Returning customer</option>
            <option value="other" <?= $getHowFound == 'other' ? 'selected="selected"' : ''?>>Other</option>
        </select>
        <select class="form-control" name="campaign_id">
            <option value="all">Campaigns</option>
            <option value="0"  <?= $getCampaignId == '0' ? 'selected="selected"' : '' ?>>No campaign</option>
            <option value="yes"  <?= $getCampaignId == 'yes' ? 'selected="selected"' : '' ?>>Any campaign</option>
            <? foreach ($campaignList as $case) { ?>
            <option value="<?= $case['id'] ?>" <?= $case['id'] == $getCampaignId ? 'selected="selected"' : '' ?>><?= date('d/m/Y', strtotime($case['start_dt'])) ?>: <?= $case['name'] ?></option>
            <? } ?>
        </select>
        <!--
        <select class="form-control" name="pb">
            <option value="all">(TESTING) Proposals & Bookings</option>
            <option value="none">No proposals</option>
            <optgroup label="Proposal">
                <option>Private tour</option>
                <option>GIT tour</option>
                <option>VPC tour</option>
                <option>TCG tour</option>
                <option>Amica Travel tour</option>
            </optgroup>
            <optgroup label="Booking">
                <option>Private tour</option>
                <option>GIT tour</option>
                <option>VPC tour</option>
                <option>TCG tour</option>
                <option>Amica Travel tour</option>
            </optgroup>
        </select>
        <select class="form-control" name="pbstatus">
            <option value="all">(TESTING) Sale status</option>
            <option value="none">No proposals / bookings</option>
            <optgroup label="Proposal">
                <option value="p-pending">Pending</option>
                <option value="p-won">Won (ie. sold)</option>
                <option value="p-lost">Lost (ie. not sold)</option>
            </optgroup>
            <optgroup label="Booking">
                <option value="b-pending">Pending</option>
                <option value="b-finished">Finished</option>
                <option value="b-modified">Finished with modifications</option>
                <option value="b-canceled">Canceled</option>
            </optgroup>
        </select>
        -->
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
                        <th>Destinations</th>
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

                            <span title="Found: <?= $caseHowFoundList[$case['how_found']] ?? $case['how_found'] ?>"><?= strtoupper(substr(strrchr($case['how_found'], '/'), 1, 1)) ?></span>
                            /
                            <span title="Contacted: <?= $caseHowContactedList[$case['how_contacted']] ?? $case['how_contacted'] ?>"><?= strtoupper(substr(strrchr($case['how_contacted'], '/'), 1, 1)) ?></span>




                            <? /* if (substr($case['how_found'], 0, 7) == 'new/ref') { ?>
                            via <?= Html::a($case['referrer']['name'], '@web/users/r/'.$case['ref'], ['rel'=>'external']) ?>
                            <? } else { ?>
                            <?= $case['how_found'] ?>
                            <? } */ ?>


                            <?/*
                            if ($case['how_contacted'] == 'agent') {
                                echo 'via ', Html::a($case['company']['name'], '@web/companies/r/'.$case['company_id'], ['rel'=>'external']);
                            } else {
                                if ($case['how_contacted'] != '') {
                                    echo $case['how_contacted'];
                                }
                            }

                            if (substr($case['how_contacted'], 0, 3) == 'web') {
                                echo ' &middot; <span class="text-muted">', $case['web_referral'], '</span>';
                                if (substr($case['web_referral'], 0, 6) == 'search' || substr($case['web_referral'], 0, 2) == 'ad') {
                                    echo ' &middot; <span class="text-danger">', $case['web_keyword'], '</span>';
                                }
                            }*/
                            ?>
                        </td>
                        <td><?= $case['stats']['pa_destinations'] ?></td>
                        <? if ($case['stats']['pa_destinations'] != '') { ?>
                        <td class="text-center"><?= $case['stats']['pa_start_date'] ?></td>
                        <td class="text-center"><?= $case['stats']['pa_days'] ?></td>
                        <td class="text-center"><?= $case['stats']['pa_pax'] ?></td>
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
