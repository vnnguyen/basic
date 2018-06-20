<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

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
    'created'=>'Created',
    'updated'=>'Updated',
];

?>
<div class="col-md-12">
    <form method="get" action="" class="form-inline panel-search">
        <select class="form-control" name="ca">
            <option value="created">Created in</option>
            <option value="assigned" <?=$getCa == 'assigned' ? 'selected="selected"' : '' ?>>Assigned in</option>
        </select>
        <select class="form-control" name="month">
            <option value="all">All months</option>
            <? foreach ($monthList as $mo) { ?>
            <option value="<?= $mo['ym'] ?>" <?= $mo['ym'] == $getMonth ? 'selected="selected"' : '' ?>><?= $mo['ym'] ?></option>
            <? } ?>
        </select>
        <?= Html::dropdownList('ym', $ym, ['m'=>'View month', 'y'=>'View year'], ['class'=>'form-control']) ?>
        <select class="form-control" name="language">
            <option value="all">All languages</option>
            <option value="en" <?= $getLanguage == 'en' ? 'selected="selected"' : ''?>>English</option>
            <option value="fr" <?= $getLanguage == 'fr' ? 'selected="selected"' : ''?>>Francais</option>
            <option value="vi" <?= $getLanguage == 'vi' ? 'selected="selected"' : ''?>>Tiếng Việt</option>
        </select>
        <select class="form-control" name="company">
            <option value="all">TO / TA</option>
            <? foreach ($companyList as $company) { ?>
            <option value="<?= $company['id'] ?>" <?= $getCompany == $company['id'] ? 'selected="selected"' : ''?>><?= $company['name'] ?></option>
            <? } ?>
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
            <? foreach ($ownerList as $case) { ?>
            <option value="<?= $case['id'] ?>" <?= $case['id'] == $getOwnerId ? 'selected="selected"' : '' ?>><?= $case['name'] ?>, <?= $case['email'] ?></option>
            <? } ?>
        </select>
        <input type="text" class="form-control" name="name" value="<?= $getName ?>" placeholder="Search name" autocomplete="off">
        <?= Html::dropdownList('type', $type, $kaseTypeList, ['class'=>'form-control', 'prompt'=>'Type']) ?>
        <?= Html::dropdownList('orderby', $orderby, $orderbyList, ['class'=>'form-control']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '@web/b2b/cases') ?>
    </form>

    <div class="panel panel-default">
        <? if (empty($theCases)) { ?>
        <div class="alert alert-danger">No cases found.</div>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-striped table-narrow">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= $getCa == 'created' ? 'Created' : 'Assigned' ?></th>
                        <th>Case name</th>
                        <th></th>
                        <th>Owner</th>
                        <th>Category</th>
                        <th>TO/TA</th>
                        <th>Last update</th>
                        <th>Count Provides</th>
                    </tr>
                </thead>
                <tbody>
                    <? if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><? } ?>
                    <? foreach ($theCases as $case) { ?>
                    <tr>
                        <td>
                            <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-nowrap text-center"><?= str_replace('/'.date('Y'), '', date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y')) ?></td>
                        <td class="text-nowrap">
                            <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
                            <? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
                            <? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
                            <? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
                            <? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
                            <? if ($case['stats']) { ?>
                            <span class="text-muted">
                                <?= $case['stats']['pa_pax'] == '' ? '' : $case['stats']['pa_pax'].'p' ?>
                                <?= $case['stats']['pa_days'] == '' ? '' : $case['stats']['pa_days'].'d' ?>
                                <?= $case['stats']['pa_start_date'] == '' ? '' : $case['stats']['pa_start_date'] ?>
                                <?= strtoupper($case['stats']['pa_destinations']) ?></span>
                            <? } ?>
                        </td>
                        <td><? if ($case['info'] != '') { ?><i title="<?= Html::encode($case['info']) ?>" class="fa fa-comment-o"></i><? } ?></td>
                        <td class="text-nowrap">
                            <img src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px" class="img-circle">
                            <?=Html::a($case['owner']['name'], '?owner_id='.$case['owner']['id'])?>
                        </td>
                        <td>
                            <? if ($case['stype'] == 'b2b') { ?><span class="">Request</span><? } ?>
                            <? if ($case['stype'] == 'b2b-prod') { ?><span class="text-violet">Prod</span><? } ?>
                            <? if ($case['stype'] == 'b2b-series') { ?><span class="text-pink">Series</span><? } ?>
                        </td>
                        <td class="text-nowrap">
                            <?= Html::a($case['company']['name'], '@web/companies/r/'.$case['company_id'], ['rel'=>'external']) ?>
                        </td>
                        <td><?= $case['last_accessed_dt'] == ZERO_DT ? '' : Yii::$app->formatter->asRelativetime($case['last_accessed_dt']) ?></td>
                        <td>
                            <?php foreach ($cnt_comp as $com): ?>
                                <?php if ($com['company_id'] == $case['company_id']): ?>
                                    <?= $com['cnt']?>
                                <?php endif ?>
                            <?php endforeach ?>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pages->pageSize < $pages->totalCount) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]);?>
        </div>
        <? } ?>

    </div>
    <? } ?>
</div>
