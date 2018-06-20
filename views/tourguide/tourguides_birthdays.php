<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


$this->title = 'Birthdates of tour guides ('.$pages->totalCount.')';
$this->params['icon'] = 'group';
$this->params['breadcrumb'] = [
	['Tour guides', '@web/tourguides'],
	['Birthdates', '@web/tourguides/birthdays'],
];

?>
<div class="col-md-12">
	<div>
		<div class="btn-group">
			<? for ($mo = 0; $mo <= 12; $mo ++) { ?>
			<a class="btn btn-<?= $getMonth == $mo ? 'primary' : 'default' ?>" href="<?= DIR.URI.'?month='.$mo ?>"><?= $mo == 0 ? 'Unknown month' : $mo ?></a>
			<? } ?>
		</div>
	</div><br>

	<? if (empty($theUsers)) { ?><p>No data found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<td width="40">ID</td>
					<th width="50"></th>
					<th colspan="2">Name</th>
					<th width="80">DOB</th>
					<th width="30">Age</th>
					<th width="">Email</th>
					<th width="">Phone</th>
					<th width="">Address</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theUsers as $li) { ?>
				<tr>
					<td class="text-center"><?= Html::a($li['id'], '@web/tourguides/u/'.$li['id'], ['rel'=>'external']) ?></td>
					<td>
						<? if ($li['gender'] == 'male') { ?><i class="fa fa-male" style="color:blue"></i><? } ?>
						<? if ($li['gender'] == 'female') { ?><i class="fa fa-female" style="color:brown"></i><? } ?>
						<? if ($li['country_code'] != '--') { ?><img src="<?=DIR?>images/flags/16x11/<?=$li['country_code']?>.png"><? } ?>
					</td>
					<td><?=Html::a($li['fname'], '@web/tourguides/r/'.$li['id'])?></td>
					<td><?=Html::a($li['lname'], '@web/tourguides/r/'.$li['id'])?>
					<?
					if (Yii::$app->user->id == 1 && $li['lname'] == '' && $li['fname'] != '') {
						$names = explode(' ', $li['fname']);
						if (count($names) == 2) {
							echo Html::a($names[0].'/'.$names[1], '@web/tourguides/d/'.$li['id'].'?action=name&option=12');
							echo ' - ';
							echo Html::a($names[1].'/'.$names[0], 'users/d/'.$li['id'].'?action=name&option=21');
						}
					}
					?>
					</td>
					<td class="text-center"><?= $li['bday'] ?>/<?= $li['bmonth'] ?>/<?= $li['byear'] ?></td>
					<td class="text-center"><?= $li['byear'] == 0 ? '' : (date('Y') - $li['byear']) ?></td>
					<td>
						<?
						foreach ($li['metas'] as $meta) {
							if ($meta['k'] == 'email') {
								echo $meta['v'];
								break;
							}
						}
						?>
					</td>
					<td>
						<?
						foreach ($li['metas'] as $meta) {
							if ($meta['k'] == 'tel' || $meta['k'] == 'mobile') {
								echo $meta['v'];
								break;
							}
						}
						?>
					</td>
					<td>
						<?
						foreach ($li['metas'] as $meta) {
							if ($meta['k'] == 'address') {
								echo $meta['v'];
								break;
							}
						}
						?>
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
			'firstPageLabel'=>'<<',
			'prevPageLabel'=>'<',
			'nextPageLabel'=>'>',
			'lastPageLabel'=>'>>',
		]) ?>
	</div>
	<? } // if pages ?>
	<? } // if theUsers ?>
</div>