<?
use yii\helpers\Html;
use yii\helpers\Markdown;

$this->title = $theUser['fname'].' '.$theUser['lname'];

$this->params['breadcrumb'] = [
	['People', 'at/users'],
	['View', URI],
];

$userMetaList = [];
?>
<div class="col-md-4">
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
					<img class="img-responsive" style="display:block; float:left; padding:0; margin:0;" src="<?= $theUser['image'] ?>" alt="Avatar">
					</td>
				</tr>
				<tr><td><strong>Full name:</strong></td><td><?= $theUser['fname'] ?> / <?= $theUser['lname'] ?></td></tr>
				<tr><td><strong>Gender:</strong></td><td><?= $theUser['gender'] ?></td></tr>
				<tr><td><strong>Date of birth:</strong></td><td><?= $theUser['bday'] ?> / <?= $theUser['bmonth'] ?> / <?= $theUser['byear'] ?></td></tr>
				<tr><td><strong>Nationality:</strong></td><td><?= Html::img(DIR.'assets/img/flags/16x11/'.$theUser['country_code'].'.png') ?> <?= $theUser['country']['name_en'] ?></td></tr>
				<tr><td><strong>Language:</strong></td><td><?= $theUser['language'] ?></td></tr>
				<tr><td><strong>Timezone:</strong></td><td><?= $theUser['timezone'] ?></td></tr>
			</tbody>
		</table>
	</div>
</div>
<div class="col-md-8">
	<div class="tabbable tabbable-custom tabbable-full-width">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs mb-1em">
			<li class="active"><a href="#t-overview" data-toggle="tab">Overview</a></li>
			<? if ($userMemberProfile) { ?>
			<li><a href="#t-member" data-toggle="tab">Amica profile</a></li>
			<? } ?>
			<? if ($theUser['tours'] || $theUser['cases']) { ?>
			<li><a href="#t-customer" data-toggle="tab">Customer profile</a></li>
			<? } ?>
			<li><a href="#dd" data-toggle="tab">Media gallery</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="t-overview">
				<p><strong><?= $theUser['name'] ?></strong>
					<br><?= $theUser['about'] ?>
				</p>
				<p><strong>MORE INFORMATION</strong></p>
				<p><?=fHTML::convertNewLines($theUser['info'])?></p>
				<br>
				<p><strong>CONTACT INFORMATION</strong></p>
				<?
				if (!empty($theUser['metas'])) {
					foreach ($theUser['metas'] as $item) {
				?>
				<div>
					<strong><?=array_key_exists($item['k'], $userMetaList) ? $userMetaList[$item['k']] : $item['k']?>:</strong>
					<?=$item['v']?><? if ($item['x'] != '') { ?><br /><em><?=$item['x']?></em><? } ?>
				</div>
				<?
					} // foreach $theMetas
				} // if not empty
				?>
				<br>
				<p><strong>GROUPS</strong></p>
				<? foreach ($theUser['roles'] as $role) { ?>
				<div><?= $role['name'] ?></div>
				<? } ?>
				<br>
			</div>
			<? if ($userMemberProfile) { ?>
			<div class="tab-pane" id="t-member">
				<?= Html::a('View all members', 'at/users', ['class'=>'pull-right']) ?>
				<p><strong>Đến với Amica từ:</strong> <?= $userMemberProfile['since'] == '0000-00-00' ? 'Chưa có thông tin' : $userMemberProfile['since'] ?>
				<br><strong>Công việc hiện tại:</strong> <?= $userMemberProfile['position'] ?>, <?= $userMemberProfile['unit'] ?>, <?= $userMemberProfile['location'] ?></p>
				<p><strong>QUÁ TRÌNH CÔNG TÁC</strong></p>
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th>Khoảng thời gian</th>
							<th>Vị trí</th>
							<th>Đơn vị</th>
							<th>Văn phòng</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td>x</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

					</tbody>
				</table>
				
				<p><strong>LỜI GIỚI THIỆU</strong></p>
				<blockquote><?= Markdown::process($userMemberProfile['intro']) ?></blockquote>

				<p><strong>TIỂU SỬ TỰ TÓM TẮT</strong></p>
				<p><?= Markdown::process($userMemberProfile['bio']) ?></p>
				<!--
				<p><strong>BLOG POSTS BY PERSON</strong></p>
				<p><strong>KB POSTS BY PERSON</strong></p>
				<p><strong>FORUM POSTS BY PERSON</strong></p>
				<p><strong>COMMENTS BY PERSON</strong></p>
				-->
			</div>
			<? } ?>

			<div class="tab-pane" id="t-customer">
				<? if ($theUser['refCases']) { ?>
				<p><strong>REFERRAL CASES</strong></p>
				<? foreach ($theUser['refCases'] as $case) { ?>
				<div><strong>Case:</strong> <?= Html::a($case['name'], 'at/cases/r/'.$case['id']) ?></div>
				<? } ?>
				<br>
				<? } // if cases ?>
				<? if ($theUser['cases']) { ?>
				<p><strong>USER CASES</strong></p>
				<? foreach ($theUser['cases'] as $case) { ?>
				<div><strong>Case:</strong> <?= Html::a($case['name'], 'at/cases/r/'.$case['id']) ?></div>
				<? } ?>
				<br>
				<? } // if cases ?>
				<? if ($theUser['tours']) { ?>
				<p><strong>USER TOURS</strong></p>
				<? foreach ($theUser['tours'] as $tour) { ?>
				<div><strong>Tour:</strong> <?= Html::a($tour['code'].' - '.$tour['name'], 'at/tours/r/'.$tour['id']) ?></div>
				<? } ?>
				<? } // if cases ?>
			</div>
			
			<div class="tab-pane" id="dd">
				<p><strong>MEDIA GALLERY</strong></p>
				<div class="mb-10 clearfix">
				<?
				$dirPath = '/var/www/my.amicatravel.com/upload/about/members/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'].'/';
				if (file_exists($dirPath)):
				$dir = new fDirectory($dirPath);
				$images = $dir->scan('#\.(jpe?g|gif|png)$#i');
				if (count($images) > 0) {
					foreach ($images as $im) {
						$imgName = $im->getName();

				?>
				<a title="" class="fancybox" rel="gallery" href="<?=DIR.'upload/about/members/'.substr($theUser['created_at'], 0, 7).'/'.$theUser['id'].'/'.$imgName?>"><img src="<?=DIR?>timthumb.php?src=upload/about/members/<?=substr($theUser['created_at'], 0, 7).'/'.$theUser['id'].'/'.$imgName?>&w=100&h=100" style="float:left; margin:0 8px 8px 0;"></a>
				<?
					}
				}
				endif;
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?
$this->registerCssFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.css');
$this->registerJsFile(DIR.'assets/fancyapps/fancybox/lib/jquery.mousewheel-3.0.6.pack.js', ['yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.pack.js', ['yii\web\JqueryAsset']);
$jsCode = <<<TXT
$('a.fancybox').fancybox({titlePosition:'over', centerOnScroll:true});
TXT;
$this->registerJs($jsCode);

