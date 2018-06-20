<?
use yii\helpers\Html;

include('_members_inc.php');

$this->title = 'Amica Travel: the people';

$this->params['breadcrumb'] = [
	['Organization', 'org'],
	['Members', 'org/members'],
];

?>
<div class="col-md-12">
	<p>
	<div class="btn-group">
		<button class="btn btn-default filter" data-filter="male">Male</button>
		<button class="btn btn-default filter" data-filter="female">Female</button>
		<button class="btn btn-default filter active" data-filter="all">All</button>
	</div>
	<div class="btn-group">
		<button class="btn btn-default sort" data-sort="data-name">Sort name</button>
		<button class="btn btn-default sort" data-sort="data-byear">Sort age</button>
		<button class="btn btn-default sort active" data-sort="default">Default sorting</button>
	</div>
	</p>
</div>
<div class="col-md-12">
	<div class="row clearfix" id="mixitup">
	<?
	$cnt = 0;
	foreach ($theUsers  as $member) {
		if ($member['is_member'] == 'yes') {
			$cnt ++;
			//if ($cnt == 1) echo '<div class="row">';
	?>
						<div class="col-sm-6 col-md-4 col-lg-3 mix <?= $member['gender'] ?>" data-name="<?= $member['lname'] ?>" data-byear="<?= $member['byear'] ?>">
							<div class="border-<?= $member['gender'] == 'male' ? 'blue' : 'pink' ?>-300 panel panel-body">
								<div class="media">
									<div class="media-left">
										<a href="<?= $member['image'] ?>" data-popup="lightbox">
											<img src="/timthumb.php?w=100&h=100&src=<?= $member['image'] ?>" style="width: 70px; height: 70px;" class="img-circle" alt="">
										</a>
									</div>

									<div class="media-body">
										<h6 class="media-heading">
			<? if (in_array($member['country_code'], ['vn', 'la', 'kh'])) { ?>
			<?= Html::a($member['fname'].' '.$member['lname'], '/members/r/'.$member['id']) ?>
			<? } else { ?>
			<?= Html::a($member['lname'].' '.$member['fname'], '/members/r/'.$member['id']) ?>
			<? } ?>
										</h6>
										<p class="text-muted"><?= $member['about'] ?></p>

										<!--ul class="icons-list">
											<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Google Drive"><i class="icon-google-drive"></i></a></li>
											<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Twitter"><i class="icon-twitter"></i></a></li>
											<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Github"><i class="icon-github"></i></a></li>
										</ul-->
									</div>
									<div class="media-right media-middle">
										<ul class="icons-list text-nowrap">
											<li><a data-toggle="collapse" data-target="#user<?= $member['id'] ?>"><i class="icon-menu7"></i></a></li>
										</ul>
									</div>
								</div>
									<div class="collapse" id="user<?= $member['id'] ?>">
										<div class="contact-details">
											<ul class="list-extended list-unstyled list-icons">
												<li><i class="icon-user-tie position-left"></i> <?= $member['profileMember']['position'] ?> <?= $member['profileMember']['unit'] ?></li>
												<li><i class="icon-pin position-left"></i> <?= $member['profileMember']['location'] ?></li>
												<li><i class="icon-phone position-left"></i> <?= $member['phone'] ?></li>
												<li><i class="icon-mail5 position-left"></i> <a href="#"><?= $member['email'] ?></a></li>
											</ul>
										</div>
									</div>
							</div>
						</div>

	<?/*div class="col-sm-6 col-md-4 col-lg-3 mix <?= $member['gender'] ?>" data-name="<?= $member['lname'] ?>" data-byear="<?= $member['byear'] ?>">
		<div class="clearfix thumbnail" style="overflow:hidden; background-color:<?=$member['gender'] == 'male' ? '#eef' : '#fee'?>; margin-bottom:10px;">
			<a title="" href="<?=DIR?>users/r/<?=$member['id']?>"><img src="http://my.amicatravel.com/timthumb.php?src=<?= $member['image'] ?>&w=300&h=300&zc=1" style="float:left; width:100px; height:100px; margin:0 10px 0 0;"></a>
			<? if (in_array($member['country_code'], ['vn', 'la', 'kh'])) { ?>
			<div style=""><strong title="<?=$member['fname'].' '.$member['lname']?>"><?=Html::a($member['fname'].' '.$member['lname'], 'users/r/'.$member['id'])?></strong></div>
			<? } else { ?>
			<div style=""><strong title="<?=$member['fname'].' '.$member['lname']?>"><?=Html::a($member['lname'].' '.$member['fname'], 'users/r/'.$member['id'])?></strong></div>
			<? } ?>
			<div style="height:16px; overflow:hidden;"><small><?=$member['about']?></small></div>
			<div style="height:16px; overflow:hidden;"><small><?=$member['email']?></small></div>
			<div style="height:16px; overflow:hidden;"><small><?=$member['phone']?></small></div>
			<!--div>
				<i class="fa fa-fw fa-facebook"></i>
				<i class="fa fa-fw fa-twitter"></i>
				<i class="fa fa-fw fa-google-plus"></i>
				<i class="fa fa-fw fa-youtube"></i>
				<i class="fa fa-fw fa-skype"></i>
			</div-->
		</div>
	</div*/?>
	<?
			if ($cnt == 4) {
				//echo '</div>';
				$cnt = 0;
			}
		}
	}
	if ($cnt > 0) echo '</div>';
	?>
</div>
<div class="clear" style="clear:both"></div>
<div class="col-md-12">
	<h3>Our old members, who also helped shape the company</h3>
	<ol class="list-inline">
	<?
	$cnt = 0;
	foreach ($theOldMembers  as $user) {
		if ($user['country_code'] == 'fr') {
?><li><?= Html::a($user['lname'].' '.$user['fname'], '@web/users/r/'.$user['id']) ?> (<?= $user['byear'] ?>)</li><?
		} else {
?><li><?= Html::a($user['fname'].' '.$user['lname'], '@web/users/r/'.$user['id']) ?> (<?= $user['byear'] ?>)</li><?
		}
	}
	?>
	</ol>
</div>
<style>
#mixitup .mix{ opacity: 0; display: none;}
@media(max-width:767px) {.mix{width:100%!important;}}
</style>
<?
$this->registerJsFile(DIR.'assets/barrel/mixitup/jquery.mixitup.min.js', ['depends'=>Yii::$app->params['active_asset']]);
$this->registerJs('$(\'#mixitup\').mixitup();');
