<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include_once ('_bookings_inc.php');

$this->title = 'Tour bookings ('.$pages->totalCount.')';
?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
	<?= Html::beginForm(DIR.URI, 'get', ['class'=>'well well-sm form-inline']) ?>
	<?= Html::dropdownList('status', $getStatus, ['all'=>'All status', 'won'=>'Won', 'canceled'=>'Won & Canceled'], ['class'=>'form-control']) ?>
	<?= Html::dropdownList('stype', '', ['privatetour'=>'Private tour', 'vpctour'=>'VPC tour', 'tcgtour'=>'TCG tour'], ['class'=>'form-control', 'prompt'=>'Product type']) ?>
	<?= Html::dropdownList('language', '', ['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tieng Viet'], ['class'=>'form-control', 'prompt'=>'Product language']) ?>
	<?= Html::dropdownList('payment', '', ['all'=>'All payment status', 'none'=>'Payment: none', 'part'=>'Payment: part', 'full'=>'Payment: full'], ['class'=>'form-control']) ?>
	<?= Html::textInput('case', $getCase, ['class'=>'form-control', 'placeholder'=>'Case name or ID']) ?>
	<?= Html::textInput('owner', $getOwner, ['class'=>'form-control', 'placeholder'=>'Owner name or ID']) ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<?= Html::a('Reset', 'bookings') ?>
	<?= Html::endForm() ?>
	<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th width="40">ID</th>
					<th width="40">Status</th>
					<th class="text-center">Lang/Type</th>
					<th>Product</th>
					<th width="100">Start date</th>
					<th width="40" class="text-center">Days</th>
					<th width="40" class="text-center">Pax</th>
					<th>Price</th>
					<th>Case</th>
					<th>Updated by</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theBookings as $booking) { ?>
				<tr>
					<td class="text-center"><?= Html::a($booking['id'], 'bookings/r/'.$booking['id'], ['class'=>'text-muted']) ?></td>
					<td class="text-center" style="white-space:nowrap">
						<?
						$labelColor = 'default';
						if ($booking['status'] == 'won') $labelColor = 'success';
						if ($booking['status'] == 'lost') $labelColor = 'danger';
						?>
						<span class="label label-<?= $labelColor ?>"><?= strtoupper($booking['status']) ?></span>
						<? if ($booking['finish'] == 'canceled') { ?>
						<span class="label label-warning">CXL</span>
						<? } ?>
						<?
						if ($booking['status'] == 'won') {
							echo Html::a('<i class="fa fa-bar-chart"></i>', '@web/bookings/report/'.$booking['id'], ['title'=>'Thống kê']);
						}
						?>
					</td>
					<td class="text-muted text-center text-nowrap"><?= strtoupper($booking['product']['language']) ?> | <?= strtoupper($booking['product']['offer_type']) ?></td>
					<td>
						<? if ($booking['note'] != '') { ?>
							<i class="fa fa-file-text-o pull-left text-muted popovers"
								data-toggle="popover"
								data-trigger="hover"
								data-title="<?= $booking['product']['title'] ?>"
								data-html="true"
								data-content="<?= nl2br($booking['note']) ?>"></i>
						<? } ?>
						<? if ($booking['product']['tour']) { ?>
						<?= Html::a($booking['product']['tour']['code'], '/tours/r/'.$booking['product']['tour']['id'], ['style'=>'background-color:#ffc; color:#060; padding:0 5px;']) ?>
						<? } ?>
						<?= Html::a($booking['product']['title'], '/products/r/'.$booking['product']['id']) ?>						
					</td>
					<td><?= $booking['product']['day_from'] ?></td>
					<td class="text-center"><?= $booking['product']['day_count'] ?></td>
					<td class="text-center"><?= $booking['pax'] ?></td>
					<td class="text-right"><?= number_format($booking['price'], 0) ?> <span class="text-muted"><?= $booking['currency'] ?></span></td>
					<td><?= Html::a($booking['case']['name'], '/cases/r/'.$booking['case']['id']) ?></td>
					<td>
						<?= Html::a($booking['updatedBy']['name'], '/users/r/'.$booking['updatedBy']['id']) ?>
						<span class="text-muted"><?= Yii::$app->formatter->asRelativeTime($booking['updated_at']) ?></span>
					</td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', '/bookings/u/'.$booking['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->limit) { ?>
	<div class="text-center">
	<?=LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);?>
	</div>
	<? } ?>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>
<?
$js = <<<TXT
$('.popovers').popover();
TXT;
$this->registerJs($js);
