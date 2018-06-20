<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/reports'],
];

// Including old text
$caseWhyClosedListAll = array(
	'won'=>'A tour has been confirmed | Đã bán được tour',
	'lost/duplicate'=>'Doublon | Trùng với hồ sơ khác',

	'lost/nodeal'=>'Client non-potentiel | Khách không có tiềm năng',
	
	'lost/nodeal/01'=>'Client non-potentiel – demande de groupe ou voyageur seul (Khách không tiềm năng – hỏi đi tour ghép hoặc pax đi 1 mình)',
	'lost/nodeal/02'=>'Client non-potentiel – pas de budget (Khách không tiềm năng – không có tiền)',
	'lost/nodeal/03'=>'Client non-potentiel – prestations sèches  (Khách không tiềm năng – chỉ hỏi dịch vụ nhỏ lẻ)',
	'lost/nodeal/04'=>'Client non-potentiel – autre destination  (Khách không tiềm năng – hỏi 1 điểm đến khác)',
	'lost/nodeal/00'=>'Client non-potentiel – autre raison (Khách không tiềm năng – vì lý do khác)',

	'lost/noreply'=>'Sans réponse - demande potentielle mais le client ne répond pas (HS tiềm năng nhưng khách không có hồi âm)',

	'lost/refused'=>'Refus | Khách từ chối mua tour',
	
	'lost/refused/01'=>'Refus – autre agence ou/et prix trop cher (Khách từ chối – chọn 1 công ty khác hoặc/và vì giá Amica quá cao)',
	'lost/refused/02'=>'Refus – report du voyage, annulation, maladie, changement de destination (khách từ chối – hoãn chuyến đi, huỷ chuyến đi, bị ốm, thay đổi điểm đến)',
	'lost/refused/03'=>'Refus – le voyageur se débrouille seul (Khách từ chối – tự đặt dịch vụ, tự đi tour)',

	'lost/other'=>'Autres raisons - Không bán được vì lý do khác',
);

$this->params['actions'] = [
	[
		['icon'=>'briefcase', 'label'=>'All cases', 'link'=>'cases'],
	],
];

$this->title = 'Lost cases ('.number_format($pagination->totalCount, 0).')';

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control']) ?>
		<?= Html::dropdownList('for', $for, ['b2b'=>'B2B', 'b2c'=>'B2C'], ['prompt'=>'B2B/B2C', 'class'=>'form-control']) ?>
		<?= Html::dropdownList('seller', $seller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'All sellers']) ?>
		<?= Html::dropdownList('reason', $reason, $caseWhyClosedListAll, ['class'=>'form-control']) ?>
		<?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Search in note']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/reports/lost-cases') ?>
	</form>

	<? if (empty($theCases)) { ?><p>No cases found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Ngày nhận/đóng, số ngày</th>
					<th>Lý do</th>
					<th>Tên HS</th>
					<th>Người bán</th>
					<th>Nguồn</th>
					<th>Yêu cầu</th>
					<th>Note</th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><? } ?>
				<? foreach ($theCases as $case) { ?>
				<tr>
					<td class="text-nowrap">
						<?= date_format(date_timezone_set(date_create($case['ao']), timezone_open('Asia/Saigon')), 'j/n/Y')?>
						-
						<?= date_format(date_timezone_set(date_create($case['closed']), timezone_open('Asia/Saigon')), 'j/n/Y')?>
						(<?php
$datetime1 = new DateTime($case['ao']);
$datetime2 = new DateTime($case['closed']);
$interval = $datetime1->diff($datetime2);
echo $interval->format('%a');
?>)
					</td>
					<td><?= Html::a(ucfirst(str_replace('lost/', '', $case['why_closed'])), '@web/reports/lost-cases?month='.$month.'&reason='.$case['why_closed'].'&seller='.$seller) ?></td>
					<td class="text-nowrap">
						<?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['rel'=>'external', 'style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
						<? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
						<? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
						<? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
						<? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
					</td>
					<td class="text-nowrap">
						<?= Html::a($case['owner']['name'], '@web/reports/lost-cases?month='.$month.'&reason='.$reason.'&seller='.$case['owner']['id'])?>
					</td>
					<td class="text-nowrap">
						<? if ($case['how_found'] == 'word') { ?>
						via <?= Html::a($case['referrer']['name'], '@web/users/r/'.$case['ref'], ['rel'=>'external']) ?>
						<? } else { ?>
						<?= $case['how_found'] ?>
						<? } ?>
					</td>
					<td>
						<? if (isset($case['stats']['destinations']) && $case['stats']['destinations'] != '') { ?>
						<?= $case['stats']['destinations'] ?>
						<?= $case['stats']['pax_count_min'] ?>p <?= $case['stats']['day_count_min'] ?>d
						<?= $case['stats']['avail_from_date'] ?>
						<? } ?>
					</td>
					<td><?= $case['closed_note'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
	<? } ?>
</div>
