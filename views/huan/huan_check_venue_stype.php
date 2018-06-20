<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


$this->title = 'Check Venue Stype';
$this->params['breadcrumb'] = [
	['Special', 'huan'],
	['Check venue stype', 'huan/check-venue-stype'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Mới', 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
	],
];

$typeList = [
	''=>'(?)',
	'hotel'=>'Hotels',
	'home'=>'Local homes',
	'cruise'=>'Cruise vessel',
	'restaurant'=>'Restaurants',
	'sightseeing'=>'Sightseeing spot',
	'table'=>'Bảng tổng hợp',
	'office'=>'Văn phòng cty',
	'train'=>'Tàu hoả',
	'other'=>'Other',
];

$statusList = [
	'all'=>'All status',
	'on'=>'On',
	'off'=>'Off',
	'draft'=>'Draft',
	'deleted'=>'Deleted',
];
?>
<div class="col-md-12">
	<? if (empty($theVenues)) { ?><p>No data found</p><? } else { ?>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);
	?>
	</div>
	<br>
	<? } ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th width="150">Địa điểm</th>
					<th width="150">Stype</th>
					<th width="">Name</th>
					<th width="">About</th>
					<th width="">Abbr</th>
					<th width="40">Edit</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theVenues as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= $li['id'] ?></td>
					<td><?= $li['destination']['name_vi'] ?></td>
					<td class="text-center"><?= $typeList[$li['stype']] ?></td>
					<td>
						<?= Html::a($li['name'], 'venues/r/'.$li['id'], ['rel'=>'external']) ?>
					</td>
					<td><?= $li['about'] ?></td>
					<td><?= $li['abbr'] ?></td>
					<td class="muted td-n">
						<a class="text-danger" rel="external" href="<?= DIR ?>huan/venue-u?id=<?=$li['id']?>">Edit</a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);
	?>
	</div>
	<? } ?>
	<? } ?>
</div>
