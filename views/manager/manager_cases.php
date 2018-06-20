<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Cases', '@web/manager/cases'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
	],
];

$this->title = 'Cases ('.number_format($pages->totalCount, 0).')';

?>
<div class="col-md-12">
	<ul>
		<li><a href="/b2b/cases">For B2B cases, click here</a></li>
		<li><a href="/cases">For B2C cases, click here</a></li>
	</ul>
</div>
<? if (0): ?>
<div class="col-lg-12">
	<form method="get" action="" class="form-inline well well-sm">
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
		<select class="form-control" name="language">
			<option value="all">All languages</option>
			<option value="en" <?= $getLanguage == 'en' ? 'selected="selected"' : ''?>>English</option>
			<option value="fr" <?= $getLanguage == 'fr' ? 'selected="selected"' : ''?>>Francais</option>
			<option value="vi" <?= $getLanguage == 'vi' ? 'selected="selected"' : ''?>>Tiếng Việt</option>
		</select>
		<select class="form-control" name="company">
			<option value="all">B2C & B2B</option>
			<option value="no" <?= $getCompany == 'no' ? 'selected="selected"' : ''?>>B2C only</option>
			<option value="yes" <?= $getCompany == 'yes' ? 'selected="selected"' : ''?>>B2B only</option>
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
				<option value="cofr-1769" <?= 'cofr-1769' == $getOwnerId ? 'selected="selected"' : '' ?>>Trân (Cao Lê Trân)</option>
				<option value="cofr-767" <?= 'cofr-767' == $getOwnerId ? 'selected="selected"' : '' ?>>Cô Xuân (Vương Thị Xuân)</option>
				<option value="cofr-688" <?= 'cofr-688' == $getOwnerId ? 'selected="selected"' : '' ?>>Frédéric (Frédéric Hoeckel)</option>
			</optgroup>
		</select>
		<input type="text" class="form-control" name="name" value="<?= $getName ?>" placeholder="Search name" autocomplete="off">

		<br>
		<select class="form-control" name="contacted">
			<option value="all">How customer contacted us</option>
			<option value="web" <?= $getHowContacted == 'web' ? 'selected="selected"' : ''?>>Web inquiry</option>
			<option value="web-direct" <?= $getHowContacted == 'web-direct' ? 'selected="selected"' : ''?>>- Direct web access</option>
			<option value="web-adwords" <?= $getHowContacted == 'web-adwords' ? 'selected="selected"' : ''?>>- Adwords</option>
			<option value="web-adwords-amica" <?= $getHowContacted == 'web-adwords-amica' ? 'selected="selected"' : ''?>>- - Adwords Amica</option>
			<option value="web-search" <?= $getHowContacted == 'web-search' ? 'selected="selected"' : ''?>>- Search</option>
			<option value="web-search-amica" <?= $getHowContacted == 'web-search-amica' ? 'selected="selected"' : ''?>>- - Search Amica</option>
			<option value="email" <?= $getHowContacted == 'email' ? 'selected="selected"' : ''?>>Email</option>
			<option value="phone" <?= $getHowContacted == 'phone' ? 'selected="selected"' : ''?>>Phone</option>
			<option value="direct" <?= $getHowContacted == 'direct' ? 'selected="selected"' : ''?>>In person</option>
			<option value="agent" <?= $getHowContacted == 'agent' ? 'selected="selected"' : ''?>>Via a travel agency</option>
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
		<?= Html::a('Reset', '@web/manager/cases') ?>
	</form>
	<style>
	.form-inline .form-control {margin-bottom:4px;}
	.form-inline input.form-control {margin-bottom:4px!important;}
	</style>
	<? if (empty($theCases)) { ?><p>No cases found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="100"><?= $getCa == 'created' ? 'Created' : 'Assigned' ?></th>
					<th width="100">Case name</th>
					<th width="100">Owner</th>
					<th width="200">How found / contacted us</th>
					<th>Note</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><? } ?>
				<? foreach ($theCases as $case) { ?>
				<tr>
					<td class="text-nowrap"><?=date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'Y-m-d H:i')?></td>
					<td class="text-nowrap">
						<?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['rel'=>'external', 'style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
						<? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
						<? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
						<? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
						<? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
					</td>
					<td class="text-nowrap">
						<img src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px">
						<?=Html::a($case['owner']['name'], '@web/users/r/'.$case['owner']['id'])?>
						<span class="text-muted"><?= $case['ao'] ?></span>
					</td>
					<td class="text-nowrap">
						<?= $case['campaign_id'] != 0 ? '<span class="label label-info">C</span> ' : '' ?>
						<? if ($case['how_found'] == 'word') { ?>
						via <?= Html::a($case['referrer']['name'], '@web/users/r/'.$case['ref'], ['rel'=>'external']) ?>
						<? } else { ?>
						<?= $case['how_found'] ?>
						<? } ?>

						/

						<?
						if ($case['how_contacted'] == 'agent') {
							echo 'via ', Html::a($case['company']['name'], '@web/companies/r/'.$case['company_id'], ['rel'=>'external']);
						} else {
							if ($case['how_contacted'] != '') {
								echo $case['how_contacted'];
							}
						}

						if ($case['how_contacted'] == 'web') {
							echo ' &middot; <span class="text-muted">', $case['web_referral'], '</span>';
							if (substr($case['web_referral'], 0, 6) == 'search' || substr($case['web_referral'], 0, 2) == 'ad') {
								echo ' &middot; <span class="text-danger">', $case['web_keyword'], '</span>';
							}
						}
						?>
					</td>
					<td>
					<?= $case['info'] ?>
					<? if ($case['info'] == '' && $case['status'] == 'closed' && $case['deal_status'] != 'won') { ?>
					<?= $case['closed_note'] ?>
					<? } ?>
					</td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" rel="external" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" rel="external" class="text-muted" href="<?=DIR?>cases/d/<?=$case['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
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
<? endif;// 160613 ?>
