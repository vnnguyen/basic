<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_drivers_inc.php');

$this->title = 'Drivers';
$this->params['icon'] = 'truck';
$this->params['breadcrumb'] = [
	['Drivers', 'drivers'],
];

?>
<div class="col-md-12">
	<?= Html::beginForm(DIR.URI, 'get', ['class'=>'well well-sm form-inline']) ?>
	<?= Html::dropdownList('orderby', $getOrderby, ['name'=>'Order by name', 'pts'=>'Order by points', 'since'=>'Order by experience', 'age'=>'Order by age'], ['class'=>'form-control']) ?>
	<?= Html::dropdownList('gender', $getGender, ['all'=>'Gender', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
	<?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
	<?= Html::textInput('phone', $getPhone, ['class'=>'form-control', 'placeholder'=>'Search phone']) ?>
	<?= Html::textInput('language', $getLanguage, ['class'=>'form-control', 'placeholder'=>'Language']) ?>
	<?= Html::textInput('region', $getRegion, ['class'=>'form-control', 'placeholder'=>'Region']) ?>
	<?= Html::textInput('tourtype', $getTourtype, ['class'=>'form-control', 'placeholder'=>'Tour type']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', 'drivers') ?>
	<?= Html::endForm() ?>

	<? if (empty($theDrivers)) { ?>
	<p>No drivers found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="38"></th>
					<th width="38">Pts</th>
					<th>Name</th>
					<th>Mobile</th>
					<th>Vehicles</th>
					<th width="40%">Information</th>
					<th>Since</th>
					<th>Languages</th>
					<th>Regions</th>
					<th>Tour types</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theDrivers as $driver) { ?>
				<tr>
					<td>
						<? if ($driver['image'] == '') { ?>
						<?= Html::img('https://secure.gravatar.com/avatar/df1426bf5eec7bec99718d9381fde836?s=100&d=mm', ['style'=>'max-width:38px; max-height:38px;']) ?>
						<? } else { ?>
						<?= Html::img($driver['image'], ['style'=>'max-width:38px; max-height:38px;']) ?>
						<? } ?>
					</td>
					<td class="text-muted text-center" style="vertical-align:middle; font-size:20px; line-height:38px;"><?//= $driver['points'] != 0 ? $driver['points'] : '' ?></td>
					<td class="text-nowrap">
						<?= Html::a($driver['fname'].' '.$driver['lname'], 'drivers/r/'.$driver['id']) ?>
						<br><?= $driver['byear'] == '0000' ? '' : date('Y') - $driver['byear'] ?> <?= substr($driver['gender'], 0, 1) ?> 
					</td>
					<td><?= $driver['phone'] ?></td>
					<td><?= $driver['vehicle_types'] ?></td>
					<td><?= $driver['info'] ?></td>
					<td><?//= $driver['since'] == '0000-00-00' ? '' : substr($driver['since'], 0, 4) ?></td>
					<td><?= $driver['languages'] ?></td>
					<td><?= $driver['regions'] ?></td>
					<td><?= $driver['tour_types'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'drivers/u/'.$driver['id'], ['class'=>'text-muted']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'drivers/u/'.$driver['id'], ['class'=>'text-muted']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
</div>