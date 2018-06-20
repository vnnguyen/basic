<?
use yii\helpers\Html;

$this->title = 'Tiềm năng hồ sơ theo nguồn';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Reports', 'manager/reports'],
	['Tiềm năng', 'manager/reports/'.SEG3],
];

$prospectList = [
	'all'=>'Prospect',
	'1'=>'1 +',
	'2'=>'2 ++',
	'3'=>'3 +++',
	'4'=>'4 ++++',
	'5'=>'5 +++++',
];

$siteList = [
	'all'=>'All sites',
	'fr'=>'FR',
	'val'=>'VAL',
	'vac'=>'VAC',
	'vpc'=>'VPC',
	'en'=>'EN',
];

$cnt = 0;

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('prospect', $prospect, $prospectList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('site', $site, $siteList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('source', $source, $sourceList, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>

	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th width="30">#</th>
				<th>Tên hồ sơ</th>
				<th width="50">TN</th>
				<th>Site / form</th>
				<th>Nguồn</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theCases as $case) { ?>
			<tr>
				<td class="text-center text-muted"><?= ++$cnt ?></td>
				<td><?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?></td>
				<td class="text-center"><?= $case['prospect'] ?></td>
				<td><?= $case['form_name'] ?></td>
				<td><?= $case['how_found'] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>