<?
$this->title  = 'Thông tin về các điểm tham quan';
$this->params['icon'] = 'map-marker';
$this->params['breadcrumb'] = [
	['Community', 'community'],
	['Knowledge base', 'kb'],
	['Lists', 'kb/lists'],
	['Bons plans', 'kb/lists/ssspots'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Bài mới', 'link'=>'kb/lists/ssspots/c', 'active'=>SEG3 == 'c'],
	]
];
?>
<div class="col-lg-12">
	<form method="get" action="" class="form-inline mb-1em">
		<select name="destination" class="form-control" style="width:auto;">
			<? foreach ($destinations as $li) { ?>
			<option value="<?= $li['id'] ?>" <?= $getDestination == $li['id'] ? 'selected="selected"' : '' ?>><?= $li['name_en'] ?> (<?= $li['total'] ?>)</option>
			<? } ?>
		</select>
		<button type="submit" class="btn btn-primary">GO</button>
	</form>
	<br>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<tr>
					<th>Điểm du lịch & Nhận xét</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td>
						<? if ($li['status'] != 'on') { ?><span class="label label-default status-<?=$li['status']?>"><?=$li['status']?></span><? } ?>
						<strong class="text-danger"><?= $li->name ?></strong>
						<? if ($li->url != '') { ?><a rel="external" class="text-muted" href="<?= $li->url ?>"><i class="fa fa-external-link"></i></a><? } ?>
						<br>
						<?=$li['summary']?>
					</td>
					<td>
						<a title="Edit" class="muted td-n" href="<?=DIR?>kb/lists/ssspots/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="Delete" class="muted td-n" href="<?=DIR?>kb/lists/ssspots/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>