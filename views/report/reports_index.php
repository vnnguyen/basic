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
					<tr><td><strong>Maketing</strong></td><td><?= Html::a('Hồ sơ mở theo tháng', '@web/reports/case_open') ?></td><td>Theo dõi hồ sơ mở theo tháng</td></tr>
					<tr><td></td><td><?= Html::a('Tour theo tháng kết thúc', '@web/reports/tour_end') ?></td><td>Theo dõi Tour theo tháng kết thúc</td></tr>
					<tr><td><strong>Bán hàng</strong></td><td><?= Html::a('Tỉ trọng ngày tour', '@web/reports/tour-length') ?></td><td>Tỉ lệ độ dài tour</td></tr>
					<tr><td></td><td><?= Html::a('Doanh thu tour', '@web/reports/bookings') ?></td><td>Các booking tour, giá thành, chi phí và tỉ lệ lãi dự tính</td></tr>
					<tr><td></td><td><?= Html::a('Hồ sơ không thành công', '@web/reports/lost-cases') ?></td><td>Các hồ sơ đóng và không bán được tour, nguyên nhân</td></tr>
					<tr><td></td><td><?= Html::a('Kết quả bán hàng chung', '@web/manager/sales-results') ?></td><td>Tỉ lệ bán được, bán không được và bán được tuyệt đối của đội bán hàng theo từng tháng</td></tr>
					<tr><td></td><td><?= Html::a('Kết quả bán hàng theo nguồn đến', '@web/manager/sales-results-sources') ?></td><td>Tỉ lệ bán được của đội bán hàng theo nguồn khách theo từng tháng</td></tr>
					<tr><td></td><td><?= Html::a('Hồ sơ bán được / không được theo tháng', '@web/manager/sales-results-changes') ?></td><td>Số lượng hồ sơ bán được và không bán được trong tháng của đội bán hàng theo từng tháng</td></tr>
					<tr><td></td><td><?= Html::a('Hồ sơ giao theo tháng', '@web/manager/sales-results-assignments') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng tháng qua các năm</td></tr>
					<tr><td></td><td><?= Html::a('Hồ sơ giao theo ngày', '@web/manager/sellers-cases') ?></td><td>Số lượng hồ sơ giao cho người bán theo từng ngày trong tháng</td></tr>
					<tr><td></td><td><?= Html::a('Hồ sơ và nhiệm vụ', '@web/manager/sellers-tasks') ?></td><td>Các hồ sơ bán hàng và nhiệm vụ tính theo mỗi người bán</td></tr>

					<tr><td><strong>Điều hành tour</strong></td><td><?= Html::a('Số tour chạy theo tháng', '@web/manager/tours-departures') ?></td><td>Số lượng tour khởi hành trong từng tháng qua các năm chia theo người bán hàng</td></tr>
					<tr><td></td><td><?= Html::a('Tour và khách theo quốc gia đi thăm', '@web/reports/tour-pax-country') ?></td><td>Số tour và số khách đi các nước theo từng năm</td></tr>
					<tr><td></td><td><span class="label label-info">NEW</span> <?= Html::a('Phân công điều hành tour', '@web/reports/tour-operators') ?></td><td>Số lượng, tỉ lệ tour được phân công cho điều hành</td></tr>

					<tr><td><strong>Tiền tour</strong></td><td><?= Html::a('Tiền thanh toán tour theo năm', '@web/reports/kqkdtour') ?></td><td>Số tiền cần thu và đã thu của các tour khởi hành trong năm</td></tr>
					<tr><td></td><td><?= Html::a('Lịch thanh toán tour', '@web/reports/lichtttour') ?></td><td>Số tiền tour dự tính sẽ thu theo từng tuần trong năm</td></tr>
					<tr><td></td><td><?= Html::a('Tiền tour đã thu', '@web/payments') ?></td><td>Số tiền tour đã thu được theo từng tháng</td></tr>

					<tr><td><strong>Khách hàng</strong></td><td><?= Html::a('Khách hàng đi nhiều tour', '@web/reports/pax-tours') ?></td><td>Danh sách khách đã đi nhiều hơn 1 lần và tour tương ứng</td></tr>
					<tr><td></td><td><?= Html::a('Khách hàng ở các nhà dân', '@web/reports/customers-hotel') ?></td><td>Danh sách khách đã ở các nhà dân, tính theo năm</td></tr>
					<tr><td></td><td><?= Html::a('Số lượng khách tour các năm', '@web/reports/customers-tours') ?></td><td>Tổng số khách tour (không tính tour huỷ) theo từng tháng của từng năm</td></tr>
					<tr><td></td><td><?= Html::a('Khách hàng còn credit chưa dùng', '@web/reports/mkt03') ?></td><td>Download</td></tr>
					<tr><td></td><td><?= Html::a('Khách hàng được cập nhật theo tháng', '@web/reports/mkt04') ?></td><td>Dùng để lấy danh sách email mới nhất</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>