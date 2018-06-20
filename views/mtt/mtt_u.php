<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

//include('_mtt_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['Chi phí tour', 'cpt'];

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Sửa mục dự định thanh toán cho cpt: '.$theCpt['dvtour_name'];
Yii::$app->params['page_breadcrumbs'][] = ['Thanh toán', 'cpt/thanh-toan'];
Yii::$app->params['page_breadcrumbs'][] = ['Sửa'];


$currencyList = [
	'EUR'=>'EUR',
	'USD'=>'USD',
	'VND'=>'VND',
];
?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">THÔNG TIN VỀ DỊCH VỤ / CHI PHÍ (<?= Html::a('điều hành nhập', '/cpt/r/'.$theCpt['dvtour_id'])?>)</h6>
		</div>
		<table class="table table-condensed table-bordered">
			<tbody>
				<tr><td><strong>Tên DV/CPT</strong>:</td><td><?= $theCpt['status'] == 'k' ? '<span class="label label-success">OK</span> ' : '' ?><?= $theCpt['dvtour_name'] ?></td><td><strong>Tour</strong>:</td><td><?= $theCpt['tour']['code'] ?></td></tr>
				<tr><td><strong>Số lượng / Đơn vị</strong>:</td><td><strong><?= number_format($theCpt['qty'], 2) ?></strong> <?= $theCpt['unit'] ?></td><td><strong>Đơn giá</strong>:</td><td><strong><?= number_format($theCpt['price'], 2) ?></strong> <?= $theCpt['unitc'] ?></td>
				<tr><td><strong>Thành tiền</strong>:</td><td><strong><?= number_format($theCpt['price'] * $theCpt['qty'], 2) ?></strong> <?= $theCpt['unitc'] ?></td><td><strong>Sử dụng</strong>:</td><td><?= date('j/n/Y D', strtotime($theCpt['dvtour_day'])) ?></td></tr>
				<tr><td><strong>Điạ điểm/Nhà cung cấp</strong>:</td><td colspan="3">
					<?= $theCpt['oppr'] ?>
					<?= $theCpt['venue_id'] == 0 ? '' : $theCpt['venue']['name'] ?>
					<?= $theCpt['by_company_id'] == 0 ? '' : $theCpt['company']['name'] ?>
					<?= $theCpt['via_company_id'] == 0 ? '' : $theCpt['viaCompany']['name'] ?>
					</td></tr>
				<tr><td><strong>Cập nhật</strong>:</td><td colspan="3"><?= $theCpt['updatedBy']['name'] ?> @ <?= Yii::$app->formatter->asRelativetime($theCpt['updated_at']) ?></td></tr>
			</tbody>
		</table>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">LƯỢT THANH TOÁN NÀY</h6>
		</div>
		<div class="panel-body">
		<? $form = ActiveForm::begin(); ?>
		<fieldset>
			<legend>Sửa thông tin thanh toán</legend>
			<div class="row">
				<div class="col-md-4"><?= $form->field($theMtt, 'amount')->label('Số tiền TT lần này tính theo '.$theCpt['unitc']) ?></div>
				<div class="col-md-4"><?= $form->field($theMtt, 'currency')->label('TT bằng')->dropdownList(['USD'=>'USD', 'VND'=>'VND'], ['prompt'=>'-Chọn-']) ?></div>
			</div>
			<div class="row">
				<div class="col-md-4"><?= $form->field($theMtt, 'xrate')->label('Với tỉ giá 1 '.$theCpt['unitc'].' =') ?></div>
				<div class="col-md-4"><?= $form->field($theMtt, 'tkgn') ?></div>
				<div class="col-md-4"><?= $form->field($theMtt, 'mp') ?></div>
			</div>
			<div class="row">
				<div class="col-md-4"><?= $form->field($theMtt, 'paid_in_full')->label('Tình trạng sau TT')->dropdownList(['yes'=>'Đã TT toàn bộ', 'no'=>'Mới TT một phần']) ?></div>
				<div class="col-md-8"><?= $form->field($theMtt, 'note') ?></div>
			</div>
			<div>
				<?= Html::a('Xoá?', '/ketoan/mtt/d/'.$theMtt['id'], ['class'=>'text-danger pull-right']) ?>
				<?= Html::submitButton('Ghi thông tin', ['class'=>'btn btn-primary']) ?> hoặc <?= Html::a('Thôi, quay lại', '/cpt/thanh-toan') ?>
			</div>
		</fieldset>			
		<? ActiveForm::end(); ?>
		</div>
	</div>
</div>
