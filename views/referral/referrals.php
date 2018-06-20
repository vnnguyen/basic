<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\DateTimeHelper;
use yii\widgets\LinkPager;

include('_referrals_inc.php');

$this->title = 'Referred cases ('.$pages->totalCount.')';

?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::dropdownList('created', $getCreated, ArrayHelper::map($createdMonthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Created date']) ?>
		<?= Html::dropdownList('gift', $getGift, $giftSelectList, ['class'=>'form-control']) ?>
		<?= Html::textInput('case', $getCase, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Case name']) ?>
		<?= Html::dropdownList('casestatus', $getCaseStatus, ['all'=>'Case status', 'pending'=>'Pending', 'won'=>'Won', 'lost'=>'Lost'], ['class'=>'form-control']) ?>
		<?= Html::textInput('user', $getUser, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Referrer\'s name']) ?>
		<?= Html::dropdownList('thank', $getThank, ArrayHelper::map($thankMonthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Thank-you']) ?>
		<?= Html::dropdownList('ask', $getAsk, ArrayHelper::map($askMonthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Gift proposed']) ?>
		<?= Html::dropdownList('select', $getSelect, ArrayHelper::map($selectMonthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Gift selected']) ?>
		<?= Html::dropdownList('order', $getOrder, ['created'=>'Order by Created', 'updated'=>'Order by Updated'], ['class'=>'form-control']) ?>
		<?= Html::dropdownList('limit', $getLimit, [25=>'25 per page', 50=>'50 per page', 100=>'100 per page'], ['class'=>'form-control']) ?>
		<?= Html::submitButton(Yii::t('mn', 'Go'), ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/referrals') ?>
	</form>

	<? if (empty($theReferrals)) { ?><p>No data found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="30"></th>
					<th width="80">Xác nhận</th>
					<th>HS được giới thiệu</th>
					<th>Người giới thiệu</th>
					<!--th width="100">Bán tour</th-->
					<th width="80">Cảm ơn</th>
					<th width="80">Hỏi quà</th>
					<th width="80">Chọn quà</th>
					<th class="text-center">Quà</th>
					<th class="text-center" width="30">+</th>
					<th class="text-center" width="30">-</th>
					<th>Note</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($theReferrals as $li) { $cnt ++; ?>
				<tr>
					<td class="text-muted text-center"><?= $cnt ?></td>
					<td class="text-nowrap"><?= DateTimeHelper::convert($li['created_at'], 'Y-m-d', 'UTC', 'Asia/Ho_Chi_Minh') ?></td>
					<td class="text-nowrap">
						<?= Html::a('<i class="fa fa-edit"></i>', '@web/referrals/u/'.$li['id'], ['class'=>'text-muted pull-right', 'title'=>'Edit']) ?>
						<?= Html::a('<i class="fa fa-briefcase"></i>', '@web/cases/r/'.$li['case_id'], ['rel'=>'external', 'class'=>'text-muted', 'title'=>'View case']) ?>
						<?
						if ($li['case']['bookings']) {
							foreach ($li['case']['bookings'] as $kb) {
								if ($kb['product']['tour']) {
									echo Html::a($kb['product']['tour']['code'], '@web/tours/r/'.$kb['product']['tour']['id'], ['style'=>'background-color:#ffc; padding:0 5px; color:#060;']);
								}
							}
						}
						?>
						<?= Html::a($li['case']['name'], '@web/referrals/r/'.$li['id'], ['title'=>$li['case']['owner']['name']]) ?>
						<? if ($li['case']['status'] == 'closed') { ?>
						<i class="fa fa-lock text-muted"></i>
						<? } ?>
						<? if ($li['case']['deal_status'] == 'won') { ?>
						WON
						<!--i class="fa fa-dollar text-success"></i-->
						<? } else { ?>
							<? if ($li['case']['deal_status'] == 'lost' || $li['case']['status'] == 'closed') { ?>
						<!--i class="fa fa-dollar text-danger"></i -->
						LOST
							<? } ?>
						<? } ?>
					</td>
					<td class="text-nowrap">
						<? if ($li['user']) { ?>
						<?= Html::a('<i class="fa fa-user"></i>', '@web/users/r/'.$li['user_id'], ['rel'=>'external', 'class'=>'text-muted', 'title'=>'View user info']) ?>
						<?= Html::a($li['user']['name'], '@web/referrals?user='.$li['user_id'], ['title'=>'View referrals by user']) ?>
							<? /* Linh didnt want this 140401 if ($li['user']['tours']) { ?>
								<? foreach ($li['user']['tours'] as $tour) { if ($tour['status'] != 'deleted') { ?>
						<?= Html::a($tour['code'], 'tours/r/'.$tour['id'], ['rel'=>'external', 'title'=>'View tour: '.$tour['name'], 'class'=>'text-success']) ?>
								<? } } ?>
							<? } */ ?>
						<? } ?>
					</td>
					<!--td class="text-nowrap text-center"><?= $li['ngay_ban_tour'] == '0000-00-00' ? '' : $li['ngay_ban_tour'] ?></td-->
					<td class="text-nowrap text-center"><?= $li['ngay_cam_on'] == '0000-00-00' ? '' : $li['ngay_cam_on'] ?></td>
					<? if ($li['gift'] == 'no') { ?>
					<td colspan="3" class="text-center">NO</td>
					<? } else { ?>
					<td class="text-nowrap text-center"><?= $li['ngay_hoi_qua'] == '0000-00-00' ? '' : $li['ngay_hoi_qua'] ?></td>
					<td class="text-nowrap text-center"><?= $li['ngay_chon_qua'] == '0000-00-00' ? '' : $li['ngay_chon_qua'] ?></td>
					<td class="text-nowrap text-center"><?= strtoupper($li['gift']) ?></td>
					<? } ?>
					<td class="text-center text-success"><strong><?= $li['points'] == 0 ? '' : $li['points'] ?></strong></td>
					<td class="text-center text-danger"><?= $li['points_minus'] == 0 ? '' : '-'.$li['points_minus'] ?></td>
					<td><?= $li['info'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel'=>'<<',
		'prevPageLabel'=>'<',
		'nextPageLabel'=>'>',
		'lastPageLabel'=>'>>',
		]
	) ?>
	</div>
	<? } ?>
</div>
