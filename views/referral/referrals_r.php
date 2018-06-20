<?
use yii\helpers\Html;
use app\helpers\DateTimeHelper;

include('_referrals_inc.php');

$this->title = 'Referred case: '.$theReferral['case']['name'];


?>
<div class="col-md-8">
	<table class="table table-condensed table-bordered table-striped">
		<thead>
			<tr>
				<th width="20%">ID</th>
				<th width="80%"><?= $theReferral['id'] ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><th>Case</th><td><?= Html::a($theReferral['case']['name'], '@web/cases/r/'.$theReferral['case_id'], ['rel'=>'external']) ?></td></tr>
			<tr><th>Referrer</th><td><?= Html::a($theReferral['user']['name'], '@web/users/r/'.$theReferral['user_id'], ['rel'=>'external']) ?></td></tr>
			<tr><th>Created at</th><td><?= $theReferral['created_at'] ?> by <?= $theReferral['createdBy']['name'] ?></td></tr>
			<tr><th>Updated at</th><td><?= $theReferral['updated_at'] ?> by <?= $theReferral['updatedBy']['name'] ?></td></tr>
			<tr><th>Confirmed at</th><td><?= $theReferral['ngay_xac_nhan'] ?></td></tr>
			<tr><th>Points</th><td><?= $theReferral['points'] ?></td></tr>
			<tr><th>Points used</th><td><?= $theReferral['points_minus'] ?></td></tr>
			<tr><th>Thank-you date</th><td><?= $theReferral['ngay_cam_on'] ?></td></tr>
			<tr><th>Tour confirmed date</th><td><?= $theReferral['ngay_ban_tour'] ?></td></tr>
			<tr><th>Gift asking date</th><td><?= $theReferral['ngay_hoi_qua'] ?></td></tr>
			<tr><th>Gift</th><td><?= $theReferral['gift'] ?></td></tr>
			<tr><th>Gift confirmed date</th><td><?= $theReferral['ngay_chon_qua'] ?></td></tr>
			<tr><th>Gift sent</th><td><?= $theReferral['ngay_gui_qua'] ?></td></tr>
			<tr><th>Note</th><td><?= nl2br($theReferral['info']) ?></td></tr>
		</tbody>
	</table>
</div>
