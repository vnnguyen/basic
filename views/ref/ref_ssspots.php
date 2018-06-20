<?
use yii\helpers\Html;
$this->title = 'Các điểm tham quan';
$this->params['icon'] = 'eye';
$this->params['breadcrumb'] = [
	['Tham khảo', 'ref'],
	['Điểm tham quan', 'ref/ssspots'],
];
$this->params['actions'] = [
	['Hotels', '@web/ref/hotels'],
	['Local homes', '@web/ref/homes'],
	['Halong bay cruises', 'ref/halongcruises'],
	['Other tables', 'ref/tables'],
];

$this->params['active'] = '@web/ref';
$this->params['active2'] = '@web/ssspots';

?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="">Name</th>
					<th>Tags</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theVenues)) { ?><tr><td colspan="7">No items found.</td></tr><? } ?>
				<? foreach ($theVenues as $li) { ?>
					<td><?=Html::a($li['name'], '@web/venues/r/'.$li['id'])?></td>
					<td><?=$li['search']?></td>
					<td class="text-muted td-n">
						<a title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>venues/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
