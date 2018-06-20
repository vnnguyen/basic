<?
use yii\helpers\Html;

$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
];

$newMonthList = [''=>'Tháng này'];
$newMonthList['next30days'] = '30 ngày tới';
$newMonthList['last30days'] = '30 ngày qua';
foreach ($monthList as $mo) {
    $newMonthList[$mo['ym']] = $mo['ym'].' ('.$mo['total'].')';
    $newMonthList[$mo['ym']] = $mo['ym'];
}
?>
<style>
	.remove_fb {color: red; display: none;}
	.fb_score:hover .remove_fb{ display: block; }
</style>
<div class="col-md-9">
	<div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('orderby', $orderby, ['startdate'=>'Start in', 'enddate'=>'End in', 'created'=>'Created in'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('month', $month, $newMonthList, ['class'=>'form-control']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), '@web/tours') ?>
            </form>
        </div>
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Code Tour</th>
					<th>Has Feedback</th>
					<th>Score</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		        <?php if (!empty($theTours)): ?>
					<? foreach ($theTours as $tour) {
						$dt = $tour['day_from'];
						if ($orderby == 'enddate') {
							$dt = $tour['ed'];
						}
					?>
					<tr>
						<td><?= Html::a($tour['op_code'], ''). ' - ' .Html::a($tour['op_name'], '');?> <span class="text-muted"><?= $dt?></span></td>
						<td><?= (!empty($tour['fb']))? 'yes' : '--';?></td>
						<td>
						<div class="fb_score">
						<?= (!empty($tour['fb']) && $tour['fb']['score'] >= 0)? Html::a($tour['fb']['score'], '/tours/feedback?id='.$tour['id'].'&action=view&feedback='.$tour['fb']['id'], ['class' => 'view_fb']) : Html::a('+', '/tours/feedback?id='.$tour['id'].'&action=add', ['class' => 'add_fb']);?>
						<span class="pull-right">
						<?php if (!empty($tour['fb'])): ?>
							<?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', '/tours/feedback?id='.$tour['id'].'&action=remove&feedback='.$tour['fb']['id'], ['class' => 'text-right remove_fb'])?>
						<?php endif ?>
						</span>
						</div>
						</td>
						<td>
						</td>
					</tr>
					<? } ?>
		        <?php endif ?>
			</tbody>
		</table>
	</div>
</div>