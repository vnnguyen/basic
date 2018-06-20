<?php

Yii::$app->params['page_title'] = 'B2B Reports';
Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Reports'],
];

?>
<?
use yii\helpers\Html;
$this->title = 'Các báo cáo';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports'],
];

$this->params['icon'] = 'area-chart';

?>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped">
				<thead>
					<tr>
						<th>Phân loại</th>
						<th>Tên báo cáo</th>
						<th>Diễn giải</th>
					</tr>
				</thead>
				<tbody>
					<tr><td><strong>Tour</strong></td><td><?= Html::a('Đếm Tour', 'report/tour') ?></td><td>Số lượng tour bán được</td></tr>
					<tr><td></td><td><?= Html::a('Tour Series', 'report/tour_series') ?></td><td>Doanh thu tour series bán được theo năm</td></tr>
					<tr><td></td><td><?= Html::a('Tour Request', 'report/tour_request') ?></td><td>Doanh thu tour request</td></tr>
					<tr><td></td><td><?= Html::a('Tour khởi hành', 'report/tour_start') ?></td><td>Tour chạy</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>