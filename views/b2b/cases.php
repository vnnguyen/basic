<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['B2B sales', '@web/b2b'],
	['Cases', '@web/b2b/cases'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
	],
];

Yii::$app->params['page_title'] = 'B2B cases ('.number_format($pages->totalCount, 0).')';

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
		<?/*= Html::dropdownList('prospect', $getProspect, [
			'all'=>'Prospect',
			'1'=>'1 star',
			'2'=>'2 stars',
			'3'=>'3 stars',
			'4'=>'4 stars',
			'5'=>'5 stars',
		], ['class'=>'form-control']) */?>
		<?/*= Html::dropdownList('site', $getSite, [
			'all'=>'Contact via site',
			'fr'=>'FR',
			'vac'=>'VAC',
			'val'=>'VAL',
			'vpc'=>'VPC',
			'ami'=>'AMI',
			'en'=>'EN',
		], ['class'=>'form-control']) */?>
		<? /*
		<select class="form-control" name="campaign_id">
			<option value="all">Campaigns</option>
			<option value="0"  <?= $getCampaignId == '0' ? 'selected="selected"' : '' ?>>No campaign</option>
			<option value="yes"  <?= $getCampaignId == 'yes' ? 'selected="selected"' : '' ?>>Any campaign</option>
			<? foreach ($campaignList as $case) { ?>
			<option value="<?= $case['id'] ?>" <?= $case['id'] == $getCampaignId ? 'selected="selected"' : '' ?>><?= date('d/m/Y', strtotime($case['start_dt'])) ?>: <?= $case['name'] ?></option>
			<? } ?>
		</select>
		*/ ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/b2b/cases') ?>
	</form>

	<div class="panel panel-default">
		<? if (empty($theCases)) { ?>
		<div class="alert alert-danger">No cases found.</div>
		<? } else { ?>
		<div class="table-responsive">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th width="100"><?= $getCa == 'created' ? 'Created' : 'Assigned' ?></th>
						<th width="100">Case name</th>
						<th width="100">Owner & assign date</th>
						<th width="200">TO/TA</th>
						<th>Destinations</th>
						<th>Avail. time</th>
						<th>Days</th>
						<th>Pax</th>
						<th>Note</th>
						<th width="40"></th>
					</tr>
				</thead>
				<tbody>
					<? if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><? } ?>
					<? foreach ($theCases as $case) { ?>
					<tr>
						<td class="text-nowrap"><?= date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y H:i')?></td>
						<td class="text-nowrap">
							<? if ($case['stats']['prospect'] != 0 && $case['stats']['prospect'] != '') { ?>
							<span class="badge bg-prospect-<?= $case['stats']['prospect'] ?>"><?= $case['stats']['prospect'] ?></span>
							<? } ?>
							<?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
							<? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
							<? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
							<? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
							<? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
						</td>
						<td class="text-nowrap">
							<img src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px">
							<?=Html::a($case['owner']['name'], '@web/users/r/'.$case['owner']['id'])?>
							<span class="text-muted"><?= date('j/n/Y', strtotime($case['ao'])) ?></span>
						</td>
						<td class="text-nowrap">
							<?= Html::a($case['company']['name'], '@web/companies/r/'.$case['company_id'], ['rel'=>'external']) ?>
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
						<?= $case['info'] ?>
						<? if ($case['info'] == '' && $case['status'] == 'closed' && $case['deal_status'] != 'won') { ?>
						<?= $case['closed_note'] ?>
						<? } ?>
						</td>
						<td>
							<a title="<?=Yii::t('mn', 'Edit')?>" rel="external" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
						</td>
					</tr>

					<? } ?>
				</tbody>
			</table>
		</div>
	</div>
	<? if ($pages->pageSize < $pages->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
	<? } ?>
</div>
