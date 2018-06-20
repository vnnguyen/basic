<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

include('_users_inc.php');

$this->title = $theUser['fname'].' '.$theUser['lname'].' - Uploaded files';

?>
<div class="col-lg-3 col-md-4 col-sm-6">
	<?
	if ($theUser['image'] == '') {
		$theUser['image'] = 'https://secure.gravatar.com/avatar/'.md5($theUser['email'] == '' ? $theUser['id'] : $theUser['email']).'?s=300&d=mm';
	} else {
		$theUser['image'] = str_replace('http://', 'https://', $theUser['image']);
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
</div>
<div class="col-lg-9 col-md-8 col-sm-6">
	<ul class="nav nav-tabs mb-1em">
		<li><?= Html::a('Overview', '@web/users/r/'.$theUser['id']) ?></li>
		<? if ($theUser['profileMember']) { ?>
		<li><?= Html::a('Member profile', '@web/members/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileTourguide']) { ?>
		<li><?= Html::a('Tour guide profile', '@web/tourguides/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileDriver']) { ?>
		<li><?= Html::a('Driver profile', '@web/drivers/r/'.$theUser['id']) ?></li>
		<? } ?>
		<li class="active"><?= Html::a('Upload', '@web/users/upload/'.$theUser['id']) ?></li>
	</ul>
	<div class="clearfix">
	<?
	if (!empty($theFiles)) {
		foreach ($theFiles as $file) {
			$fileName = str_replace('/var/www/my.amicatravel.com/www/', 'https://my.amicatravel.com/', $file);
			$fileExt = strtolower(substr($fileName, -4));
			if (in_array($fileExt, ['.jpg', '.png', '.gif', 'jpeg', '.bmp', 'tiff'])) {
				$src = '/timthumb.php?w=150&h=150&src='.$fileName;
			} else {
				$src = 'holder.js?150x150&text='.$fileExt;
			}
			echo Html::a(Html::img($src, ['style'=>'margin:0 16px 16px 0; display:inline-block']), $fileName, ['class'=>'fancybox', 'rel'=>'user-upload']);
		}
	}
	?>
	</div>
</div>
<?
app\assets\FancyboxAsset::register($this);
$js = <<<'TXT'
$('.fancybox').fancybox();
TXT;
$this->registerJs($js);