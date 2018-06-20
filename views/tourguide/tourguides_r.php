<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tourguides_inc.php');

$this->title = $theGuide['fname'].' '.$theGuide['lname'].' - Tour guide data';
yap('page_icon', 'user');

$theUser = $theGuide;

?>
<div class="col-lg-3 col-md-4 col-sm-6">
	<?
	if ($theGuide['image'] == '') {
		$theGuide['image'] = 'https://secure.gravatar.com/avatar/'.md5($theGuide['email'] == '' ? $theGuide['id'] : $theGuide['email']).'?s=300&d=mm';
	} else {
		$theGuide['image'] = str_replace('http://', 'https://', $theGuide['image']);
	} ?>
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
		<li><strong>Email</strong> <?= $theGuide['email'] ?></li>
		<li><strong>Phone</strong> <?= $theGuide['phone'] ?></li>
		<li><strong>About</strong> <?= $theGuide['info'] ?></li>
	</ul>
	<p><strong>GUIDE PROFILE</strong></p>
	<ul>
		<li><strong>Điểm đánh giá:</strong> <span class="badge"><?= $theGuide['profileTourguide']['ratings'] ?> / 10</span></li>
		<li><strong>Vào nghề từ:</strong> <?= substr($theGuide['profileTourguide']['guide_since'], 0, 4) ?></li>
		<li><strong>Làm với Amica từ:</strong> <?= substr($theGuide['profileTourguide']['guide_us_since'], 0, 4) ?></li>
		<li><strong>Ngôn ngữ:</strong> <?= $theGuide['profileTourguide']['languages'] ?></li>
		<li><strong>Loại tour:</strong> <?= $theGuide['profileTourguide']['tour_types'] ?></li>
		<li><strong>Vùng hoạt động:</strong> <?= $theGuide['profileTourguide']['regions'] ?></li>
		<li><strong>Điểm mạnh:</strong> <?= $theGuide['profileTourguide']['pros'] ?></li>
		<li><strong>Điểm yếu:</strong> <?= $theGuide['profileTourguide']['cons'] ?></li>
	</ul>
	<p><strong>NOTE</strong></p>
	<p><?= nl2br($theGuide['profileTourguide']['note']) ?></p>
</div>
<div class="col-lg-9 col-md-8 col-sm-6">
	<ul class="nav nav-tabs mb-1em">
		<li><?= Html::a('Overview', '@web/users/r/'.$theUser['id']) ?></li>
		<? if ($theUser['profileMember']) { ?>
		<li><?= Html::a('Member profile', '@web/members/r/'.$theUser['id']) ?></li>
		<? } ?>
		<li class="active"><?= Html::a('Tour guide profile', '@web/drivers/r/'.$theUser['id']) ?></li>
		<? if ($theUser['profileDriver']) { ?>
		<li><?= Html::a('Driver profile', 'javascript:;') ?></li>
		<? } ?>
		<li><?= Html::a('Upload', '@web/users/upload/'.$theUser['id']) ?></li>
	</ul>
	<p><strong>CÁC TOUR DO GUIDE NÀY HƯỚNG DẪN</strong>: Do thông tin trong phần "Phân tour guide"</p>
	<div class="table-responsive"></div>
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="40"></th>
					<th width="100" colspan="2">Tour</th>
					<th width="100">Ngày</th>
					<th width="40">Điểm</th>
					<th>Ghi chú</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($theTours as $tour) { ?>
				<tr>
					<td class="text-muted text-center"><?= ++$cnt ?></td>
					<td class="text-nowrap"><?= Html::a($tour['op_code'], '@web/products/op/'.$tour['id']) ?></td>
					<td class="text-nowrap"><?= $tour['op_finish'] == 'canceled' ? '(CXL) ' : '' ?><?= Html::a($tour['op_name'], '@web/products/op/'.$tour['id']) ?></td>
					<td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($tour['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($tour['use_until_dt'])) ?></td>
					<td class="text-center">
						<? if ($tour['points'] == '400') { ?>
						<strong><?= $tour['points'] ?></strong>
						<? } else { ?>
						<?= $tour['points'] ?>
						<? } ?>
					</td>
					<td><?= $tour['note'] != '' ? $tour['note'] : '' ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>