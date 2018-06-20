<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_users_inc.php');

yap('page_icon', 'user');
if (in_array($theUser['country_code'], ['vn', 'la', 'kh'])) {
	$this->title = $theUser['fname'].' '.$theUser['lname'];
	$userName = $theUser['fname'].' '.$theUser['lname'];
} else {
	$this->title = $theUser['lname'].' '.$theUser['fname'];
	$userName = $theUser['lname'].' '.$theUser['fname'];
}
yap('page_small_title', $theUser['about']);


/*
$this->params['breadcrumb'] = [
	['People', '@web/users'],
	['View', DIR.URI],
];*/

$userMetaList = [];

$sql = 'SELECT t.name, t.id FROM at_terms t, at_term_rel r WHERE t.taxonomy_id=2 AND r.term_id=t.id AND rtype="user" AND rid=:id';
$userTags = Yii::$app->db->createCommand($sql, [':id'=>$theUser['id']])->queryAll();

//require('/var/www/my.amicatravel.com/lib/flourish/hTaxonomyManager.php');
//$tm = new hTaxonomyManager();
//$userTags = $tm->getTerms($tm->getTaxonomyId('user-tag'), 'user', $theUser['id']);
/*
$rType = 'user';
$rId = (int)seg3;

$tm = new hTaxonomyManager();

$q = $db->query('SELECT *, (SELECT name_en FROM at_countries WHERE code=country_code LIMIT 1) AS country_name FROM persons WHERE id=%i LIMIT 1', $rId);
$theUser = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404, 'User not found: '.$rId);
$theUser['avatar'] = img('http://www.gravatar.com/avatar/'.md5(strtolower($theUser['email'])).'?s=48&d=retro', '', 'class="avatar"');

$q = $db->query('SELECT * FROM at_meta WHERE rtype=%s AND rid=%i', $rType, $rId);
$theMetas = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$theUser['tags'] = $tm->getTerms($tm->getTaxonomyId('user-tag'), 'user', $theUser['id']);

$q = $db->query('select count(*) from at_messages where status="on" AND from_id=%i OR m_to=%i OR (rtype=%s AND rid=%i)', $rId, $rId, $rType, $rId);
$pg = new hxPagination($q->fetchScalar(), '?page=', fRequest::get('page', 'integer', 1), 20, 3);

$q = $db->query('SELECT id, priority, title, rtype, rid, co, uo, ub, from_id, m_to, via, n_id, file_count, comment_count,
  IF (ub=0, "Auto", (SELECT name FROM persons u WHERE u.id=at_messages.ub LIMIT 1)) AS ub_name,
  (SELECT name FROM persons u WHERE u.id=from_id LIMIT 1) AS from_name,
  (SELECT name FROM persons u WHERE u.id=m_to LIMIT 1) AS m_to_name
  FROM at_messages WHERE from_id=%i OR m_to=%i OR (rtype=%s AND rid=%i) ORDER BY co DESC, uo DESC LIMIT '.$pg->limitFrom.', '.$pg->perPage, $rId, $rId, $rType, $rId);
$theNotes = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

for ($i = 0; $i < count($theNotes); $i ++) {
	$relType = $theNotes[$i]['rtype'];
	$relId = $theNotes[$i]['rid'];

	// Khoong hop le
	if ($relType == 'case') {$relURL = 'cases/r/'.$relId; $relTypeName = 'Hồ sơ'; $q = $db->query('SELECT name FROM at_cases WHERE id=%i LIMIT 1', $relId);}
	if ($relType == 'tour') {$relURL = 'tours/r/'.$relId; $relTypeName = 'Tour'; $q = $db->query('SELECT CONCAT(code, " - ", name) FROM at_tours WHERE id=%i LIMIT 1', $relId);}
	if ($relType == 'user') {$relURL = 'users/r/'.$relId; $relTypeName = 'Danh bạ cá nhân'; $q = $db->query('SELECT name FROM persons WHERE id=%i LIMIT 1', $relId);}
	if ($relType == 'venue') {$relURL = 'venues/r/'.$relId; $relTypeName = 'Điểm cung cấp dịch vụ'; $q = $db->query('select name from venues where id=%i LIMIT 1', $relId);}
	if ($relType == 'company') {$relURL = 'companies/r/'.$relId; $relTypeName = 'Companies'; $q = $db->query('select name from at_companies where id=%i LIMIT 1', $relId);}
	if ($q->countReturnedRows() > 0) {
		$theNotes[$i]['rname'] = $q->fetchScalar();
		$theNotes[$i]['rurl'] = $relURL;
	}
}
// User meta
$q = $db->query('SELECT * FROM at_meta WHERE rtype="user" AND rid=%i ORDER BY id LIMIT 100', $rId);
$theMetas = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$um = new hUserManager();
$theUser['groups'] = $um->getRoles($theUser['id']);
 */

?>
<? if ($theUser['is_member'] == 'yes') { ?>
<? yap('page_layout', '-t header_no_margin'); ?>
<? $this->beginBlock('h'); ?>
<div class="profile-cover">
	<div class="profile-cover-img" style="background-image: url(https://images.unsplash.com/photo-1433360405326-e50f909805b3?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&w=1080&fit=max&s=359e8e12304ffa04a38627a157fc3362)"></div>
	<div class="media">
		<div class="media-left">
			<a href="#" class="profile-thumb">
				<img src="/timthumb.php?w=100&h=100&src=<?= $theUser['image'] ?>" class="img-circle" alt="">
			</a>
		</div>

		<div class="media-body">
    		<h1><?= $theUser['name'] ?> <small class="display-block"><?= $theUser['about'] ?></small></h1>
		</div>

		<div class="media-right media-middle">
			<ul class="list-inline list-inline-condensed no-margin-bottom text-nowrap">
				<li><a href="https://my.amicatravel.com/members/r/<?= $theUser['id'] ?>" class="btn btn-default"><i class="icon-file-stats position-left"></i> Profile</a></li>
				<li><a href="https://my.amicatravel.com/users/upload/<?= $theUser['id'] ?>" class="btn btn-default"><i class="icon-file-picture position-left"></i> Upload</a></li>
			</ul>
		</div>
	</div>
</div>
<? $this->endBlock(); ?>
<? } ?>
<br>
<div class="col-lg-3 col-md-4 col-sm-6">
	<?
	if ($theUser['is_member'] != 'yes') {
		if ($theUser['image'] == '') {
			$theUser['image'] = 'https://secure.gravatar.com/avatar/'.md5($theUser['email'] == '' ? $theUser['id'] : $theUser['email']).'?s=300&d=mm';
		} else {
			$theUser['image'] = str_replace('http://', 'https://', $theUser['image']);
		} 
	}
	?>
	<div class="panel panel-default">
		<table class="table">
			<tbody>
				<? if ($theUser['is_member'] != 'yes') { ?>
				<tr>
					<td colspan="2">
					<img class="img-responsive" style="display:block; float:left; padding:0; margin:0;" src="<?= $theUser['image'] ?>" alt="Avatar">
					</td>
				</tr>
				<? } ?>
				<tr><td><strong>Full name:</strong></td><td><?= $theUser['fname'] ?> / <?= $theUser['lname'] ?></td></tr>
				<tr><td><strong>Gender:</strong></td><td><?= $theUser['gender'] ?></td></tr>
				<tr><td><strong>Date of birth:</strong></td><td><?= $theUser['bday'] ?> / <?= $theUser['bmonth'] ?> / <?= $theUser['byear'] ?> (<?= $theUser['byear'] != 0 ? date('Y') - $theUser['byear'] : '?' ?> tuổi)</td></tr>
				<tr><td><strong>Nationality:</strong></td><td><?= Html::img(DIR.'assets/img/flags/16x11/'.$theUser['country_code'].'.png') ?> <?= $theUser['country']['name_en'] ?></td></tr>
				<tr><td><strong>Language:</strong></td><td><?= $theUser['language'] ?></td></tr>
				<tr><td><strong>Timezone:</strong></td><td><?= $theUser['timezone'] ?></td></tr>
			</tbody>
		</table>
	</div>
</div>
<div class="col-lg-9 col-md-8 col-sm-6">
	<ul class="nav nav-tabs mb-1em">
		<li class="active"><a href="javascript:;">Overview</a></li>
		<? if ($userMemberProfile) { ?>
		<li><?= Html::a('Member profile', '@web/members/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileTourguide']) { ?>
		<li><?= Html::a('Tour guide profile', '@web/tourguides/r/'.$theUser['id']) ?></li>
		<? } ?>
		<? if ($theUser['profileDriver']) { ?>
		<li><?= Html::a('Driver profile', '@web/drivers/r/'.$theUser['id']) ?></li>
		<? } ?>
		<li><?= Html::a('Upload', '@web/users/upload/'.$theUser['id']) ?></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="t-overview">
			<div class="well well-sm">
				<i class="fa fa-fw fa-info-circle"></i>
				This page has been viewed by
				<?
				$cnt = 0; foreach ($viewedBy as $user) {
					$cnt ++;
					if ($cnt != 1) {
						echo ', ';
					}
					echo Html::a($user['name'], '@web/users/r/'.$user['id']);
				}
				?>
			</div>

			<p><strong>MORE INFORMATION</strong></p>
			<p><?=fHTML::convertNewLines($theUser['info'])?></p>
			<br>
			<p><strong>CONTACT INFORMATION</strong></p><?
			if (!empty($theUser['metas'])) {
				foreach ($theUser['metas'] as $item) { ?>
			<div>
				<strong><?=array_key_exists($item['k'], $userMetaList) ? $userMetaList[$item['k']] : $item['k']?>:</strong>
				<?=$item['v']?><? if ($item['x'] != '') { ?> <em><?=$item['x']?></em><? } ?>
			</div><?
				} // foreach $theMetas
			} // if not empty

			if (!empty($theUser['roles'])) { ?>
			<br>
			<p><strong>ROLES & GROUPS</strong></p><?
				foreach ($theUser['roles'] as $role) { ?>
			<div><?= Html::a($role['name'], '@web/roles/r/'.$role['id']) ?></div><?
				} // foreach
			} // if not empty

			if (!empty($userTags)) { ?>
			<br>
			<p><strong>TAGS:</strong> <?
				foreach ($userTags as $tag) {
					echo Html::a($tag['name'], '@web/users/tags?tag='.$tag['id']). ', ';
				} ?>
			</p><?
			} ?>
			<p><?= Html::a('View all user tags', '@web/users/tags') ?></p>

			<hr>
			<? if ($theUser['refCases']) { ?>
			<p><strong>REFERRAL CASES</strong> <?= Html::a('View all', DIR.'referrals?user='.$theUser['id']) ?></p>
			<? foreach ($theUser['refCases'] as $case) { ?>
			<div>
				<i class="fa fa-fw fa-briefcase text-muted"></i>
				<?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
				<? if ($case['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
				<? if ($case['status'] == 'onhold') { ?><i class="fa fa-clock-o text-muted"></i><? } ?>
				<? if ($case['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
				<? if ($case['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
				<?= date('n/Y', strtotime($case['created_at'])) ?>
				<?= $case['owner']['name'] ?>
			</div>
			<? } ?>
			<br>
			<? } // if cases ?>
			<? if ($theUser['cases']) { ?>
			<p><strong>USER CASES</strong></p>
			<? foreach ($theUser['cases'] as $case) { ?>
			<div>
				<i class="fa fa-fw fa-briefcase text-muted"></i>
				<?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
				<? if ($case['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
				<? if ($case['status'] == 'onhold') { ?><i class="fa fa-clock-o text-muted"></i><? } ?>
				<? if ($case['deal_status'] == 'won') { ?><i class="fa fa-dollar text-success"></i><? } ?>
				<? if ($case['deal_status'] == 'lost') { ?><i class="fa fa-dollar text-danger"></i><? } ?>
				<?= date('n/Y', strtotime($case['created_at'])) ?>
				<?= $case['owner']['name'] ?>
			</div>
			<? } ?>
			<br>
			<? } // if cases ?>
			<? if ($theUser['bookings']) { ?>
			<p><strong>USER TOURS</strong></p>
			<? foreach ($theUser['bookings'] as $booking) { ?>
			<? if ($booking['product']) { ?>
			<div>
				<i class="fa fa-car text-muted"></i> 
				<?= Html::a($booking['product']['op_code'].' - '.$booking['product']['op_name'], '@web/tours/r/'.$booking['product']['tour']['id']) ?>
				<?= $booking['product']['day_count'] ?>d
				<?= $booking['pax'] ?>p
				<?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?>
			</div>
			<? } ?>
			<? } ?>
			<br>
			<? } // if bookings ?>

			<p><strong>LATEST EMAIL & NOTES</strong> <?= Html::a('View all notes by this person', '@web/notes?from='.$theUser['id']) ?></p>
			<? foreach ($userMails as $mail) { ?>
			<div class="mb">
				<i class="fa text-muted fa-envelope-o"></i>
				<?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?>
				<span class="text-muted"><?= Yii::$app->formatter->asDatetime($mail['sent_dt']) ?></span>
			</div>
			<? } ?>

			<? foreach ($userNotes as $note) { ?>
			<div class="mb">
				<? if ($note['via'] == 'email') { ?><i class="fa text-muted fa-envelope-o"></i><? } ?>
				<?= Html::a($note['title'] == '' ? '( No title )' : $note['title'], '@web/notes/r/'.$note['id']) ?>
				<span class="text-muted"><?= Yii::$app->formatter->asDatetime($note['co']) ?></span>
			</div>
			<? } ?>
		</div>
	</div>
	<hr>
	<p>Last update by <?= $theUser['updatedBy']['name'] ?>: <?= $theUser['updated_at'] ?></p>
</div>
<?
//$this->registerCssFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.css');
//$this->registerJsFile(DIR.'assets/fancyapps/fancybox/lib/jquery.mousewheel-3.0.6.pack.js', ['depends'=>'app\assets\MetronicAsset']);
//$this->registerJsFile(DIR.'assets/fancyapps/fancybox/jquery.fancybox.pack.js', ['depends'=>'app\assets\MetronicAsset']);
$jsCode = <<<TXT
$('a.fancybox').fancybox({titlePosition:'over', centerOnScroll:true});
TXT;
//$this->registerJs($jsCode);

