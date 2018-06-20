<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

include('_cpt_inc.php');

Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Thanh toán các cpt đã chọn';
Yii::$app->params['page_breadcrumbs'] = [
	['Chi phí tour', 'cpt'],
	['Thanh toán', 'cpt/thanh-toan'],
];

?>

<div class="col-md-12">
	<!--
	<ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="/cpt/thanh-toan">Giỏ cpt</a></li>
        <li class=""><a href="/cpt/de-nghi-thanh-toan">Đề nghị TT</a></li>
        <li class=""><a href="/cpt/da-thanh-toan">Đã TT</a></li>
    </ul>
    -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Các mục được thanh toán trong lượt này. Sửa các mục này trước nếu cần.</h6>
		</div>
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Sửa</th>
					<th>Xoá</th>
					<th>Tour</th>
					<th>Dịch vụ, nhà cung cấp</th>
					<th>Thành tiền</th>
					<th>Số tiền TT</th>
					<th>100%</th>
					<th>TT=</th>
					<th>Tỉ giá</th>
					<th>TKGN</th>
					<th>MP</th>
					<th>Ghi chú</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$totalPayable = 0;
				$cnt = 0;
				foreach ($theMttx as $mtt) {
					if ($mtt['cpt']['plusminus'] == 'minus') {
						$totalPayable -= $mtt['amount'];
					} else {
						$totalPayable += $mtt['amount'];
					}
				?>
				<tr>
					<td class="text-center"><?= Html::a('Sửa', '/ketoan/mtt/u/'.$mtt['id'], ['class'=>'text-muted']) ?></td>
					<td class="text-center"><?= Html::a('Xoá', '/ketoan/mtt/d/'.$mtt['id'], ['class'=>'text-danger']) ?></td>
					<td><?= Html::a($mtt['cpt']['tour']['code'], '@www/tours/r/'.$mtt['cpt']['tour']['id'], ['target'=>'_blank']) ?></td>
					<td>
						<?= Html::a($mtt['cpt']['dvtour_name'], '@www/cpt/r/'.$mtt['cpt']['dvtour_id'], ['target'=>'_blank']) ?>
						<? if ($mtt['cpt']['venue_id'] != 0) { ?> 	@<?= $mtt['cpt']['venue']['name'] ?><? } ?>
						<? if ($mtt['cpt']['by_company_id'] != 0) { ?> (<?= $mtt['cpt']['company']['name'] ?>)<? } ?>
						<? if ($mtt['cpt']['via_company_id'] != 0) { ?> (<?= $mtt['cpt']['viaCompany']['name'] ?>)<? } ?>
						<? if ($mtt['cpt']['venue_id'] == 0 && $mtt['cpt']['by_company_id'] == 0 && $mtt['cpt']['via_company_id'] == 0) { ?> <span class="text-muted"><?= $mtt['cpt']['oppr'] ?></span><? } ?>
					</td>
					<td class="text-right text-info"><?= $mtt['cpt']['plusminus'] == 'plus' ? '' : '-' ?><?= number_format($mtt['cpt']['qty'] * $mtt['cpt']['price'], 2) ?> <span class="text-muted"><?= $mtt['cpt']['unitc'] ?></span></td>
					<td class="text-right text-warning"><?= $mtt['cpt']['plusminus'] == 'plus' ? '' : '-' ?><?= number_format($mtt['amount'], 2) ?> <span class="text-muted"><?= $mtt['cpt']['unitc'] ?></span></td>
					<td class="text-center"><?= $mtt['paid_in_full'] ?></td>
					<td class="text-center"><?= $mtt['currency'] ?></td>
					<td class="text-center"><?= + $mtt['xrate'] ?></td>
					<td class="text-center"><?= $mtt['tkgn'] ?></td>
					<td class="text-center"><?= $mtt['mp'] ?></td>
					<td class="text-right"><?= $mtt['note'] ?></td>
				</tr>
				<? } ?>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th>TOTAL</th>
					<th class="text-right text-info"></th>
					<th class="text-right text-warning"><?= number_format($totalPayable, 2) ?><?= !empty($theMttx) ? ' '.$mtt['cpt']['unitc'] : '' ?></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tbody>
		</table>
	</div>


	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Các mục không được sửa ở trên sẽ lấy theo thông tin ở đây. Chú ý: Loại tiền và tỉ giá dưới đây luôn được update cho mọi mục ở trên.</h6>
		</div>
		<div class="panel-body">
			<div class="alert alert-info">NEW: Có thể chọn số tiền TT ít hơn 100% trong trường hợp TT một phần (sẽ nhân % đó với tất cả số tiền cpt cần trả ở trên)</div>
			<? $form = ActiveForm::begin(); ?>
			<div class="row">
				<div class="col-md-2"><?= $form->field($theMtt, 'payment_dt')->label('Ngày TT') ?></div>
				<div class="col-md-2"><?= $form->field($theMtt, 'amount')->label('% số tiền TT lần này')->dropdownList(['100'=>'100% (TT hết)', '90'=>'90%', '75'=>'75%', '70'=>'70%', '50'=>'50%', '30'=>'30%', '25'=>'25%', '10'=>'10%'], ['prompt'=>'- Chọn -']) ?></div>
				<div class="col-md-2"><?= $form->field($theMtt, 'currency')->dropdownList(['USD'=>'USD', 'VND'=>'VND', 'EUR'=>'EUR', 'LAK'=>'LAK', 'KHR'=>'KHR', 'THB'=>'THB'], ['prompt'=>'- Chọn -']) ?></div>
				<div class="col-md-2"><?= $form->field($theMtt, 'xrate') ?></div>
				<div class="col-md-2"><?= $form->field($theMtt, 'tkgn') ?></div>
				<div class="col-md-2"><?= $form->field($theMtt, 'mp') ?></div>
			</div>
			<div class="row">
				<div class="col-md-4"><?= $form->field($theMtt, 'paid_in_full')->label('Tình trạng CPT sau TT (cộng cả các lần TT trước nếu có)')->dropdownList(['yes'=>'Đã TT toàn bộ', 'no'=>'Mới TT một phần']) ?></div>
				<div class="col-md-8"><?= $form->field($theMtt, 'note') ?></div>
			</div>
			<div class="text-right"><?=  Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</div>
<?
$js = <<<'TXT'
TXT;
$this->registerJs($js);