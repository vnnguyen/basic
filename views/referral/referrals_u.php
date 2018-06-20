<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_referrals_inc.php');

if ($theReferral->isNewRecord) {
	$this->title = 'New referral case';
	$this->params['breadcrumb'][] = ['New', '@web/referrals/c'];
} else {
	$this->title = 'Edit: '.$theReferral['case']['name'];
	$this->params['breadcrumb'][] = ['View', '@web/referrals/r/'.$theReferral['id']];
	$this->params['breadcrumb'][] = ['Edit', '@web/referrals/u/'.$theReferral['id']];
}

?>
<div class="col-md-8">
	<div class="alert alert-warning">
		<i class="fa fa-warning"></i>
		Trang này đang được xây dựng. Vui lòng chú ý đọc chỉ dẫn ở bên phải.
	</div>
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6">
			<div class="well well-sm"><i class="fa fa-briefcase"></i> <?= Html::a($theReferral['case']['name'], 'cases/r/'.$theReferral['case_id']) ?></div>
		</div>
		<div class="col-md-6">
			<div class="well well-sm"><i class="fa fa-user"></i> <?= Html::a($theReferral['user']['name'], 'users/r/'.$theReferral['user_id']) ?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_xac_nhan') ?></div>
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_cam_on') ?></div>
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_ban_tour') ?></div>
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_hoi_qua') ?></div>
	</div>
	<div class="row">
		<div class="col-md-2"><?= $form->field($theReferral, 'points') ?></div>
		<div class="col-md-2"><?= $form->field($theReferral, 'points_minus') ?></div>
		<div class="col-md-2"><?= $form->field($theReferral, 'gift')->dropdownList($giftList) ?></div>
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_chon_qua') ?></div>
		<div class="col-md-3"><?= $form->field($theReferral, 'ngay_gui_qua') ?></div>
	</div>
	<?= $form->field($theReferral, 'info')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
	<p><strong>Chỉ dẫn</strong></p>
	<ul>
		<li>Tạm thời, khi cần sửa ngày, hãy nhập tay theo dạng năm-tháng-ngày (sau này sẽ có lịch để chọn nhanh)</li>
		<li>Trường hợp không làm chính sách quà và credit (vd Verot) thì chọn quà là NO</li>
		<li>Để trống nếu không biết thông tin</li>
	</ul>
</div>