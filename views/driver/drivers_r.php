<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;

include('_drivers_inc.php');

$this->title = $theUser['fname'].' '.$theUser['lname'].' - Driver profile';

yap('page_icon', 'user');

?>
<div class="col-lg-3 col-md-4 col-sm-6">
	<?
	if ($theUser['image'] == '') {
		$theUser['image'] = 'https://secure.gravatar.com/avatar/'.md5($theUser['email'] == '' ? $theUser['id'] : $theUser['email']).'?s=300&d=mm';
	} else {
		$theUser['image'] = str_replace('http://', 'https://', $theUser['image']);
	}
	?>
	<div class="panel panel-default">
		<table class="table">
			<tbody>
				<tr>
					<td colspan="2">
					<img class="img-responsive" src="<?= $theUser['image'] ?>" alt="Avatar">
					</td>
				</tr>
				<tr><td><strong>Full name:</strong></td><td><?= $theUser['fname'] ?> / <?= $theUser['lname'] ?></td></tr>
				<tr><td><strong>Gender:</strong></td><td><?= $theUser['gender'] ?></td></tr>
				<tr><td><strong>Date of birth:</strong></td><td><?= $theUser['bday'] ?> / <?= $theUser['bmonth'] ?> / <?= $theUser['byear'] ?> (<?= $theUser['byear'] != 0 ? date('Y') - $theUser['byear'] : '?' ?> tuổi)</td></tr>
			</tbody>
		</table>
	</div>
	<p><strong>CONTACT INFORMATION</strong></p>
	<ul>
		<li><strong>Email</strong> <?= $theUser['email'] ?></li>
		<li><strong>Phone</strong> <?= $theUser['phone'] ?></li>
		<li><strong>About</strong> <?= $theUser['info'] ?></li>
	</ul>
	<p><strong>GUIDE PROFILE</strong></p>
	<ul>
		<li><strong>Điểm đánh giá:</strong> <span class="badge"><?= $theProfile['points'] ?> / 10</span></li>
		<li><strong>Vào nghề từ:</strong> <?= substr($theProfile['since'], 0, 7) ?></li>
		<li><strong>Làm với Amica từ:</strong> <?= substr($theProfile['us_since'], 0, 7) ?></li>
		<li><strong>Ngôn ngữ:</strong> <?= $theProfile['languages'] ?></li>
		<li><strong>Loại tour:</strong> <?= $theProfile['tour_types'] ?></li>
		<li><strong>Vùng hoạt động:</strong> <?= $theProfile['regions'] ?></li>
		<li><strong>Điểm mạnh:</strong> <?= $theProfile['pros'] ?></li>
		<li><strong>Điểm yếu:</strong> <?= $theProfile['cons'] ?></li>
	</ul>
	<p><strong>NOTE</strong></p>
	<p><?= nl2br($theProfile['note']) ?></p>
</div>
<div class="col-lg-9 col-md-8 col-sm-6">
	<ul class="nav nav-tabs mb-1em">
		<li><?= Html::a('Overview', '@web/users/r/'.$theUser['id']) ?></li>
		<? if ($theUser['profileMember']) { ?>
		<li><?= Html::a('Member profile', '@web/members/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileTourguide']) { ?>
		<li><?= Html::a('Tour guide profile', 'javascript:;') ?></li>
		<? } ?>
		<li class="active"><?= Html::a('Driver profile', '@web/drivers/r/'.$theUser['id']) ?></li>
		<li><?= Html::a('Upload', '@web/users/upload/'.$theUser['id']) ?></li>
	</ul>
	<p><strong>CÁC TOUR CÓ LÁI XE NÀY THAM GIA</strong></p>
	<? if (empty($theDiemlx)) { ?>
	<p>No data found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="">Tour</th>
					<th width="">Từ ngày</th>
					<th width="">Đến ngày</th>
					<th width="">Điểm</th>
					<th>Ghi chú</th>
					<th width="">Update</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theDiemlx as $entry) { ?>
				<tr>
					<td class="text-nowrap"><?= Html::a($entry['op_code'], '@web/tours/drivers/'.$entry['tour_id']) ?> <?= $entry['op_name'] ?></td>
					<td class="text-nowrap"><?= date('j/n/Y', strtotime($entry['use_from_dt'])) ?></td>
					<td class="text-nowrap"><?= date('j/n/Y', strtotime($entry['use_until_dt'])) ?></td>
					<td class="text-nowrap text-center"><?= $entry['points'] ?></td>
					<td><?= $entry['note'] ?></td>
					<td class="text-nowrap"><?= $entry['updated_by_name'] ?>, <?= DateTimeHelper::convert($entry['updated_dt'], 'j/n/Y H:i', 'UTC', 'Asia/Ho_Chi_Minh') ?></td>
					<td>
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>tools/diemlx?action=u&id=<?= $entry['id'] ?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>tools/diemlx?action=d&id=<?= $entry['id'] ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } // if empty ?>
</div>