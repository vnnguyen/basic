<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;

include('_members_inc.php');

$this->title = $theUser['fname'].' '.$theUser['lname'].' - Amica profile';

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
</div>
<div class="col-lg-9 col-md-8 col-sm-6">
	<ul class="nav nav-tabs mb-1em">
		<li><?= Html::a('Overview', '@web/users/r/'.$theUser['id']) ?></li>
		<li class="active"><?= Html::a('Member profile', '@web/members/r/'.$theUser['id']) ?></li>
		<? if ($theUser['profileTourguide']) { ?>
		<li><?= Html::a('Tour guide profile', '@web/tourguides/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileDriver']) { ?>
		<li><?= Html::a('Driver profile', '@web/drivers/r/'.$theUser['id']) ?></li>
		<? } ?>
		<li><?= Html::a('Upload', '@web/users/upload/'.$theUser['id']) ?></li>
	</ul>
	<p><strong>MEMBER PROFILE</strong></p>
	<ul>
		<li><strong>Là thành viên từ:</strong>
		<?
		if ($theProfile['since'] != '0000-00-00') {
			echo 'tháng ', date('n/Y', strtotime($theProfile['since']));
			echo ' ('.(date('Y') - date('Y', strtotime($theProfile['since']))).' năm)';
		} else {
			echo 'Chưa có thông tin';
		}
		?>
		<li><strong>Vị trí:</strong> <?= $theProfile['position'] ?></li>
		<li><strong>Đơn vị:</strong> <?= $theProfile['unit'] ?></li>
		<li><strong>Nơi làm việc:</strong> <?= $theProfile['location'] ?></li>
	</ul>
	<p><strong>TIỂU SỬ</strong></p>
	<p><?= nl2br($theProfile['bio']) ?></p>
</div>