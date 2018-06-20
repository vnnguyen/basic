<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tourguides_inc.php');

$this->title = 'Tour guides';
$this->params['icon'] = 'user';
$this->params['breadcrumb'] = [
	['Tour guides', 'tourguides'],
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
	<?= Html::a('Reset', '/tourguides') ?>
	<?= Html::endForm() ?>

	<? if (empty($theTourguides)) { ?>
	<p>No tourguides found. <?=Html::a('Create the first one', 'tourguides/c')?>.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="38"></th>
					<th width="38">Pts</th>
					<th>Name</th>
					<th>Mobile</th>
					<th width="40%">Information</th>
					<th>Since</th>
					<th>Languages</th>
					<th>Regions</th>
					<th>Tour types</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theTourguides as $tourguide) { ?>
				<tr>
					<td>
						<? if ($tourguide['image'] == '') { ?>
						<?= Html::img('https://secure.gravatar.com/avatar/df1426bf5eec7bec99718d9381fde836?s=100&d=mm', ['style'=>'max-width:38px; max-height:38px;']) ?>
						<? } else { ?>
						<?= Html::img($tourguide['image'], ['style'=>'max-width:38px; max-height:38px;']) ?>
						<? } ?>
					</td>
					<td class="text-muted text-center" style="vertical-align:middle; font-size:20px; line-height:38px;"><?= $tourguide['ratings'] != 0 ? $tourguide['ratings'] : '' ?></td>
					<td class="text-nowrap">
						<?= Html::a($tourguide['fname'].' '.$tourguide['lname'], 'tourguides/r/'.$tourguide['id']) ?>
						<br>
						<i class="fa fa-<?= $tourguide['gender'] ?> color-gender-<?= $tourguide['gender'] ?>"></i>
						<?= $tourguide['byear'] == '0000' ? '' : date('Y') - $tourguide['byear'] ?> 
					</td>
					<td><?= $tourguide['phone'] ?></td>
					<td><?= $tourguide['note'] ?></td>
					<td><?= $tourguide['guide_since'] == '0000-00-00' ? '' : substr($tourguide['guide_since'], 0, 4) ?></td>
					<td><?= $tourguide['languages'] ?></td>
					<td><?= $tourguide['regions'] ?></td>
					<td><?= $tourguide['tour_types'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'tourguides/u/'.$tourguide['id'], ['class'=>'text-muted']) ?>
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
<style type="text/css">
.color-gender-male {color:blue;}
.color-gender-female {color:pink;}
</style>