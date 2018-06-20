<?
use yii\helpers\Html;

$this->title = 'Amica Travel: the people';
$this->params['icon'] = 'group';

$this->params['breadcrumb'] = [
	['Community', 'community'],
	['About us', 'about'],
	['Our people', 'about/members'],
];
$this->params['active'] = 'about';
$this->params['active2'] = 'members';
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
	foreach ($models  as $li) {
		if ($li['is_member'] == 'yes') {
			$cnt ++;
			//if ($cnt == 1) echo '<div class="row">';
	?>
	<div class="col-sm-6 col-md-4 col-lg-3 mix <?= $li['gender'] ?>" data-name="<?= $li['lname'] ?>" data-byear="<?= $li['byear'] ?>">
		<div class="clearfix thumbnail" style="overflow:hidden; background-color:<?=$li['gender'] == 'male' ? '#eef' : '#fee'?>; margin-bottom:10px;">
			<a title="" href="<?=DIR?>users/r/<?=$li['id']?>"><img src="http://my.amicatravel.com/timthumb.php?src=<?= $li['image'] ?>&w=300&h=300&zc=1" style="float:left; width:100px; height:100px; margin:0 10px 0 0;"></a>
			<? if (in_array($li['country_code'], ['vn', 'la', 'kh'])) { ?>
			<div style=""><strong title="<?=$li['fname'].' '.$li['lname']?>"><?=Html::a($li['fname'].' '.$li['lname'], 'users/r/'.$li['id'])?></strong></div>
			<? } else { ?>
			<div style=""><strong title="<?=$li['fname'].' '.$li['lname']?>"><?=Html::a($li['lname'].' '.$li['fname'], 'users/r/'.$li['id'])?></strong></div>
			<? } ?>
			<div style="height:16px; overflow:hidden;"><small><?=$li['about']?></small></div>
			<div style="height:16px; overflow:hidden;"><small><?=$li['email']?></small></div>
			<div style="height:16px; overflow:hidden;"><small><?=$li['phone']?></small></div>
			<!--div>
				<i class="fa fa-fw fa-facebook"></i>
				<i class="fa fa-fw fa-twitter"></i>
				<i class="fa fa-fw fa-google-plus"></i>
				<i class="fa fa-fw fa-youtube"></i>
				<i class="fa fa-fw fa-skype"></i>
			</div-->
		</div>
	</div>
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
	foreach ($models  as $li) {
		if ($li['is_member'] == 'old') {
			$imgUrl = 'http://my.amicatravel.com/upload/user-avatars/user-'.$li['id'].'.jpg';
			if (!file_exists('/var/www/my.amicatravel.com/upload/user-avatars/user-'.$li['id'].'.jpg'))
				$imgUrl = 'http://0.gravatar.com/avatar/'.md5($li['email']).'?d=mm';
			?>
	<a title="<?=$li['fname']?> <?=$li['lname']?>" href="<?=DIR?>users/r/<?=$li['id']?>"><img style="float:left; margin:0 3px 3px 0; width:48px; height:48px;" src="<?=$imgUrl?>"></a>
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
$this->registerJsFile(DIR.'assets/barrel/mixitup/jquery.mixitup.min.js', ['app\assets\MainAsset']);
$this->registerJs('$(\'#mixitup\').mixitup();');
