<?

Yii::$app->params['page_icon'] = 'exchange';
Yii::$app->params['page_title'] = 'Thay đổi trên IMS';
Yii::$app->params['page_small_title'] = 'Changelog';
Yii::$app->params['page_breadcrumbs'] = [
	['Help', 'help'],
	['IMS changelog'],
];

?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-3">
			<p><strong>GHI CHÚ KÝ HIỆU:</strong></p>
			<ul class="list-unstyled">
				<li>[A] Added : Mới được thêm vào</li>
				<li>[D] Deprecated : Sẽ không được sử dụng nữa</li>
				<li>[E] Enhancement : Cải thiện</li>
				<li>[F] Fixed : Sửa lỗi</li>
				<li>[R] Removed : Loại bỏ</li>
			</ul>
		</div>
		<div class="col-md-9">
			<p>Trang này liệt kê các thay đổi trên IMS</p>
			<p><strong>2016, tháng 4</strong></p>
			<ul>
				<li>[A] Thêm thông tin trên trang <em>Changelog</em> (14/4)</li>
				<li>[A] [Data] Thêm thông tin một số thành viên mới (14/4)</li>
				<li>[E] Cho phép sửa chủng loại nhà cung cấp dịch vụ (14/4)</li>
				<li>[E] Link từ nhà cung cấp dịch vụ đến bảng thống kê tour và khách sử dụng dịch vụ (14/4)</li>
				<li>[E] Điều chỉnh invoice để in được hoá đơn với logo Secret Indochina (14/4)</li>
				<li>[A] Danh sách khách sử dụng dịch vụ tại nhà dân, để gửi email (marketing) (13/4)</li>
			</ul>
		</div>
	</div>
</div>
