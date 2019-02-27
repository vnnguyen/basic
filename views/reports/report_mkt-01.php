<?php
use yii\helpers\Html;

$s = Yii::$app->request->get('s', '');

Yii::$app->params['page_icon'] = 'cog';
Yii::$app->params['page_title'] = '(170108) Thống kê số tour được yêu cầu từ các form trên website '.($s == '' ? '' : $s).' theo năm, năm '.$y;
Yii::$app->params['page_breadcrumbs'][] = ['Reports', 'reports'];

arsort($results);
?>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">Thống kê năm <?= $y ?> | Chọn <?= Html::a('2015', '?y=2015&s='.$s, ['class'=>'text-danger']) ?> hoặc <?= Html::a('2014', '?y=2014&s='.$s, ['class'=>'text-danger']) ?>. Click cột 1 để lọc theo site. <?= Html::a('Đặt lại', '?y='.$y, ['class'=>'text-danger']) ?></h6>
		</div>
		<table class="table table-narrow table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Site</th>
					<th>Tour</th>
					<th>Link</th>
					<th>Count</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?
				$total = 0;
				$cnt = 0;
				foreach ($results as $url=>$tour) {
					$si = strchr($url, '/', true);
					if ($si == $s || $s == '') {
						$total += $tour['cnt'];
				?>
				<tr>
					<td class="text-muted text-center"><?= ++ $cnt ?></td>
					<td><?= Html::a($si, '?y='.$y.'&s='.$si) ?></td>
					<td><?= Html::a($tour['name'], substr($url, 0, 4) == 'http' ? $url : 'https://'.$url, ['target'=>'_blank']) ?></td>
					<td><?= $url ?></td>
					<td class="text-center"><?= $tour['cnt'] ?></td>
					<td></td>
				</tr>
				<?
					}
				}
				?>
				<tr>
					<th colspan="4" class="text-right">Total:</th>
					<th class="text-center"><?= $total ?></th>
					<th></th>
				</tr>
			</tbody>
		</table>
	</div>
</div>