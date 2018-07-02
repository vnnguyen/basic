<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


$this->title = 'Venues';
$this->params['breadcrumb'] = [
	['Venues', 'venues'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Mới', 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
	],
];

$typeList = [
	'all'=>'All types',
	'hotel'=>'Hotels',
	'home'=>'Local homes',
	'cruise'=>'Cruise vessels',
	'restaurant'=>'Restaurants',
	'sightseeing'=>'Sightseeing spots',
	'train'=>'Night trains',
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
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::dropdownList('type', $getType, $typeList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('destination_id', $getDestinationId, ArrayHelper::map($allDestinations, 'id', 'name_en'), ['class'=>'form-control', 'prompt'=>'All destinations']) ?>
		<?= Html::dropdownList('status', $getStatus, $statusList, ['class'=>'form-control']) ?>
		<?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', 'venues') ?>
	</form>
	<? if (empty($theVenues)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th width="">Name</th>
					<th width="">Địa điểm</th>
					<th width="">Info</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theVenues as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= $li['id'] ?></td>
					<td>
						<? if ($li['stype'] == 'home') { ?><i class="text-danger fa fa-home"></i><? } ?>
						<? if ($li['stype'] == 'hotel') { ?><i class="fa fa-buiding-o"></i><? } ?>
						<?=Html::a($li['name'], 'venues/r/'.$li['id'])?>
					</td>
					<td><?= $li['destination']['name_vi'] ?></td>
					<td><?= substr($li['info'], 0, 200) ?></td>
					<td class="muted td-n">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>venues/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
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
