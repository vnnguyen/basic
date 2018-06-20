<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$ctLanguages = [
	'en'=>'English',
	'fr'=>'Francais',
	'vi'=>'Tiếng Việt',
];
$ubx = [];
$getName = '';
$this->title = 'Các chương trình tour ('.$pages->totalCount.')';
$this->params['breadcrumb'] = [
	['Tour programs', 'ct'],
];
$getDays = 0;
?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
	<form method="get" action="" class="well well-sm form-inline">
		<select class="form-control w-auto" name="language">
			<? foreach ($ctLanguages as $k => $v) { ?>
			<option value="<?=$k?>" <?=$getLanguage == $k ? 'selected="selected"' : ''?>><?=$v?></option>
			<? } ?>
		</select>
		<select class="form-control w-auto" name="type">
			<option value="private">Private tour</option>
			<option value="agent" <?=$getType == 'agent' ? 'selected="selected"' : ''?>>Tour hãng</option>
			<option value="vpc" <?=$getType == 'vpc' ? 'selected="selected"' : ''?>>VPC tour</option>
			<option value="tcg" <?=$getType == 'tcg' ? 'selected="selected"' : ''?>>TCG tour</option>
		</select>
		<select name="ub" class="form-control w-auto">
			<option value="all">Người làm chương trình</option>
			<option value="<?=Yii::$app->user->id?>" <?=$getUb == Yii::$app->user->id ? 'selected="selected"' : ''?>>Tôi (<?= Yii::$app->user->identity->name ?>)</option>
			<? foreach ($ubList as $ub) { if (Yii::$app->user->id != $ub['id']) { ?>
			<option value="<?= $ub['id'] ?>" <?=$getUb == $ub['id'] ? 'selected="selected"' : ''?>><?=$ub['lname']?>, <?=$ub['email']?></option>
			<? } } ?>
		</select>
		<select class="form-control w-auto" name="month">
			<option value="all">Start date</option>
			<? foreach ($startDateList as $li) { ?>
			<option value="<?= $li['ym'] ?>" <?= $getMonth == $li['ym'] ? 'selected="selected"' : ''?>><?= $li['ym'] ?></option>
			<? } ?>
		</select>
		<select class="form-control w-auto" name="proposal">
			<option value="all">Trạng thái bán</option>
			<option value="yes" <?=$getProposal == 'yes' ? 'selected="selected"' : ''?>>Đang bán</option>
			<option value="no" <?=$getProposal == 'no' ? 'selected="selected"' : ''?>>Chưa bán</option>
		</select>
		<select class="form-control w-auto" name="days">
			<option value="all">Số ngày</option>
			<option value="u10" <?=$getDays == '1-10' ? 'selected="selected"' : ''?>>1-10 ngày</option>
			<option value="10-20" <?=$getDays == '11-20' ? 'selected="selected"' : ''?>>11-20 ngày</option>
			<option value="21-30" <?=$getDays == '21-30' ? 'selected="selected"' : ''?>>21-30 ngày</option>
			<option value="o30" <?=$getDays == '31' ? 'selected="selected"' : ''?>>Trên 30 ngày</option>
		</select>
		<input type="text" class="form-control w-auto" name="name" placeholder="Search name or tag" value="<?=fHTML::encode($getName)?>" />
		<button type="submit" class="btn btn-primary">Go</button>
		
	</form>
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="80" class="text-center">Type</th>
					<th>Name</th>
					<th width="80">Start date</th>
					<th width="40" class="text-center">Days</th>
					<th width="40" class="text-center">Pax</th>
					<th>Price</th>
					<th>Updated by</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= strtoupper($li['offer_type']) ?></td>
					<td>
						<i class="fa fa-file-text-o popovers text-muted"
							data-trigger="hover"
							data-title="<?= $li['title'] ?>"
							data-placement="right"
							data-html="true"
							data-content="
						<?
						$dayIds = explode(',', $li['day_ids']);
						if (count($dayIds) > 0) {
							$cnt = 0;
							foreach ($dayIds as $id) {
								foreach ($li['days'] as $day) {
									if ($day['id'] == $id) {
										$cnt ++;
										echo '<strong>', $cnt, ':</strong> ', $day['name'], ' (', $day['meals'], ')<br>';
									}
								}
							}
						}
						?>
						"></i>
						<? if ($li['offer_count'] == 0) { ?><?= Html::a('+', 'ct/proposal/'.$li['id'], ['title'=>'+ New proposal']) ?><? } else { ?>
						<?= Html::a('<i class="fa fa-briefcase"></i>', 'cases/r/'.$li['cases'][0]['id'], ['class'=>'text-warning', 'title'=>'View case: '.$li['cases'][0]['name']]) ?>
						<?= Html::a($li['tour']['code'], 'tours/r/'.$li['tour']['id'], ['title'=>'View tour: '.$li['tour']['name'], 'style'=>'background-color:#060; color:#fff; padding:0 5px;']) ?>
						<? } ?>
						<?= Html::a($li['title'], 'ct/r/'.$li['id']) ?>
						<span class="text-muted"><?= $li['about'] ?></span>
					</td>
					<td><?= $li['day_from'] ?></td>
					<td class="text-center"><?= count($dayIds) ?></td>
					<td class="text-center"><?= $li['pax'] ?></td>
					<td class="text-right"><?= Html::a(number_format($li['price'], 0), 'ct/gia/'.$li['id'], ['title'=>'Click to edit price']) ?> <span class="text-muted"><?= $li['price_unit'] ?></span></td>
					<td><?= Html::a($li['updatedBy']['name'], 'users/r/'.$li['updatedBy']['id']) ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', 'ct/u/'.$li['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', 'ct/d/'.$li['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->limit) { ?>
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
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>