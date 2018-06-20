<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = 'Check thông tin khách sạn';
$this->params['icon'] = 'check';
$this->params['breadcrumb'] = [
	['Venues', 'venues'],
];
$this->params['actions'] = [
	['Import inquiries', 'inquiries/c', 'plus'],
];
$this->params['activeMenu'] = 'ref';
$this->params['activeMenuItem'] = 'ref/hotels';
?>
<div class="col-lg-12">
	<div class="alert alert-info">Cách làm: Đi lần lượt từng trang, đối chiếu Địa chỉ với Địa điểm của từng khách sạn xem có khớp không. Nếu không, vào sửa khách sạn, chỉnh lại phần Địa điểm cho khớp.</div>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Address</th>
					<th>Location</th>
					<th>Company</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($models)) { ?><tr><td colspan="7">No hotels found</td></tr><? } ?>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?=$li['id']?></td>
					<td><?=Html::a($li['name'], 'venues/r/'.$li['id'])?></td>
					<td><?
					foreach ($li['metas'] as $li2) {
						if ($li2['k'] == 'address') {
							echo '...'.substr($li2['v'], -50);
							//break;
						}
					}
					?></td>
					<td><?=Html::a($li['destination']['name_vi'], 'destinations/r/'.$li['destination_id'], ['rel'=>'external'])?></td>
					<td><?
					if ($li['ncc_id'] != 0) {
						echo Html::a($li['ncc']['ten'], 'v2ncc/r/'.$li['ncc_id'], ['rel'=>'external']), ' - ';
					} else {
						echo '<span style="color:red">None</span> - ';
					}
					echo Html::a('Change', 'v2ncc/v/'.$li['id']);
					?>
					</td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>venues/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
</div>
