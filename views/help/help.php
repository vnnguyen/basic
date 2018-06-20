<?
use yii\helpers\Html;

$this->title = 'Hướng dẫn sử dụng IMS';

?>
<div class="col-md-4">
	<h3>Giới thiệu chung về IMS</h3>
	<p>
		<?= Html::a('Chức năng', '/help/intro/functions') ?> |
		<?= Html::a('Giao diện', '/help/intro/design') ?> |
		<?= Html::a('Mở rộng', '/help/intro/extend') ?>
	</p>
	<h3>Trang thông tin cá nhân</h3>
	<p>
		<?= Html::a('Đổi mật khẩu', '/help/me/change-password') ?> |
		
	</p>
</div>
<div class="col-md-4">
	<h3>Trang tác nghiệp</h3>
</div>
<div class="col-md-4">
	<h3>Trang cộng đồng</h3>
	<p><strong>TIN TỨC (BLOG)</strong></p>
	<p><strong>SỰ KIỆN</strong></p>
	<p><strong>CƠ SỞ DỮ LIỆU KIẾN THỨC</strong></p>
	<p><strong>DIỄN ĐÀN TRAO ĐỔI</strong></p>
	<p><strong>QUẢN LÝ DỰ ÁN</strong></p>
	<p><?= Html::a('A link', 'x') ?></p>
	<p><strong>KHÔNG GIAN NHÓM</strong></p>
</div>
