<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Thông tin thanh toán cptour';

Yii::$app->params['page_breadcrumbs'] = [
	['Chi phí tour', 'cpt'],
	['Thực tế TT', 'ketoan/mtt'],
];

?>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Các mục cpt được thanh toán</h6>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>Tour</th>
						<th>Dịch vụ, nhà cung cấp</th>
						<th>Thành tiền</th>
						<th>TKGN</th>
						<th>MP</th>
						<th>TT=</th>
						<th>Tỉ giá</th>
						<th>Số tiền TT (click để xem)</th>
						<th>Ghi chú</th>
					</tr>
				</thead>
				<tbody>
					<? $cnt = 0; foreach ($theMttx as $mtt) { ?>
					<tr>
						<td class="text-center text-muted"><?= date('j/n/Y', strtotime($mtt['payment_dt'])) ?></td>
						<td><?= Html::a($mtt['cpt']['tour']['code'], '@www/tours/r/'.$mtt['cpt']['tour']['id'], ['target'=>'_blank']) ?></td>
						<td>
							<?= Html::a('<i class="fa fa-trash-o text-danger"></i> ', '/ketoan/mtt/d/'.$mtt['id']) ?>
							<?= Html::a($mtt['cpt']['dvtour_name'], '@www/cpt/r/'.$mtt['cpt']['dvtour_id'], ['target'=>'_blank']) ?>
							<? if ($mtt['cpt']['venue_id'] != 0) { ?> 	@<?= $mtt['cpt']['venue']['name'] ?><? } ?>
							<? if ($mtt['cpt']['by_company_id'] != 0) { ?> (<?= $mtt['cpt']['company']['name'] ?>)<? } ?>
							<? if ($mtt['cpt']['via_company_id'] != 0) { ?> (<?= $mtt['cpt']['viaCompany']['name'] ?>)<? } ?>
							<? if ($mtt['cpt']['venue_id'] == 0 && $mtt['cpt']['by_company_id'] == 0 && $mtt['cpt']['via_company_id'] == 0) { ?> <span class="text-muted"><?= $mtt['cpt']['oppr'] ?></span><? } ?>
						</td>
						<td class="text-right"><?= number_format($mtt['cpt']['qty'] * $mtt['cpt']['price'], 2) ?> <span class="text-muted"><?= $mtt['cpt']['unitc'] ?></span></td>
						<td class="text-right"><?= $mtt['tkgn'] ?></td>
						<td class="text-right"><?= $mtt['mp'] ?></td>
						<td class="text-right"><?= $mtt['currency'] ?></td>
						<td class="text-right"><?= $mtt['xrate'] ?></td>
						<td class="text-right"><?= Html::a(number_format($mtt['amount'], 2), '/ketoan/mtt/r/'.$mtt['id']) ?> <span class="text-muted"><?= $mtt['currency'] ?></span></td>
						<td class="text-right"><?= $mtt['note'] ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>