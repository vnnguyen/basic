<?
use yii\helpers\Html;

$this->title = 'Amica Travel: the people';
$this->params['icon'] = 'group';

$this->params['breadcrumb'] = [
	['Organization', '@web/org'],
	['Members', '@web/org/members'],
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
						<div class="col-lg-3 col-md-6">
							<div class="panel panel-body">
								<div class="media">
									<div class="media-left">
										<a href="assets/images/placeholder.jpg" data-popup="lightbox">
											<img src="assets/images/placeholder.jpg" style="width: 70px; height: 70px;" class="img-circle" alt="">
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

										<ul class="icons-list">
					                    	<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Google Drive"><i class="icon-google-drive"></i></a></li>
					                    	<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Twitter"><i class="icon-twitter"></i></a></li>
					                    	<li><a href="#" data-popup="tooltip" title="" data-container="body" data-original-title="Github"><i class="icon-github"></i></a></li>
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
<div class="clear" style="clear:both"></div>
<div class="col-lg-12">
	<div class="row">
	<h3>Our old staff, who also helped shape the company</h3>
	<?
	$cnt = 0;
	foreach ($theUsers  as $member) {
		if ($member['is_member'] == 'old') {
			$imgUrl = 'http://my.amicatravel.com/upload/user-avatars/user-'.$member['id'].'.jpg';
			if (!file_exists('/var/www/my.amicatravel.com/upload/user-avatars/user-'.$member['id'].'.jpg'))
				$imgUrl = 'http://0.gravatar.com/avatar/'.md5($member['email']).'?d=mm';
			?>
	<a title="<?=$member['fname']?> <?=$member['lname']?>" href="<?=DIR?>users/r/<?=$member['id']?>"><img style="float:left; margin:0 3px 3px 0; width:48px; height:48px;" src="<?=$imgUrl?>"></a>
			<?
		}
	}
	?>
	</div>
</div>
<style>
#mixitup .mix{ opacity: 0; display: none;}
@media(max-width:767px) {.mix{width:100%!important;}}
</style>
<?
$this->registerJsFile(DIR.'assets/barrel/mixitup/jquery.mixitup.min.js', ['depends'=>Yii::$app->params['main_asset']]);
$this->registerJs('$(\'#mixitup\').mixitup();');
