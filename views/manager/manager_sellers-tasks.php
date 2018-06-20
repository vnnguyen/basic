<?
use yii\helpers\Html;

$this->title = 'Hồ sơ bán hàng và nhiệm vụ ('.count($theCases).')';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['Sellers\' tasks', 'manager/sellers-tasks'],
];

?>
<div class="col-lg-12">
	<form method="get" action="" class="form-inline well well-sm">
		Người bán hàng:
		<select id="sellerId" class="form-control" style="width:auto;" name="seller_id">
			<option value="0">- Hãy chọn -</option>
			<? foreach ($allSellers as $u) { ?>
			<option value="<?=$u['id']?>" <?=$u['id'] == $getSeller ? 'selected="selected"' : ''?>><?=$u['lname']?><?=$u['id'] == Yii::$app->user->id ? ' (Tôi) ' : ''?>, <?=$u['email']?></option>
			<? } ?>
		</select>
		<button type="submit" class="btn btn-primary">GO</button>
	</form>
	<div class="table-responsive">
		<table id="tbl-cases" class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th width="5%">ID</th>
					<th width="40%">Ngày nhận & Hồ sơ</th>
					<th width="40%">Các công việc liên quan</th>
				</tr>
			</thead>
			<tbody>
			<? foreach ($theCases as $c) { ?>
			<tr>
				<td class="ta-r"><?=$c['id']?></td>
				<td>
					<?=$c['ao']?>
					<?= Html::a($c['name'], '@web/cases/r/'.$c['id']) ?> 
					<? if ($c['deal_status'] == 'won') { ?><col-lg- style="background:#090; color:#fff; font-size:9px; padding:0 3px;">WON</col-lg-><? } ?>
					<? if ($c['deal_status'] == 'lost') { ?><col-lg- style="background:#900; color:#fff; font-size:9px; padding:0 3px;">LOST</col-lg-><? } ?>
				</td>
				<td><?
				foreach ($theTasks as $t) {
					if ($t['rid'] == $c['id']) {
						echo '<div>'.substr($t['due_dt'], 0, 10).' '.Html::a($t['description'], '@web/tasks/u/'.$t['id'], []).'</div>';
					}
				}
				?></td>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
</div>
