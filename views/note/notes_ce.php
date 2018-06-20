<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/*

// Khoong hop le
if ($rType == 'none') {redirect('work');exit;}
if ($rType == 'case') {$relURL = 'cases/r/'.$rId; $rTypeName = 'Hồ sơ'; $q = $db->query('SELECT name FROM at_cases WHERE id=%i LIMIT 1', $rId);}
if ($rType == 'tour') {$relURL = 'tours/r/'.$rId; $rTypeName = 'Tour'; $q = $db->query('SELECT CONCAT(code, " - ", name) FROM at_tours WHERE id=%i LIMIT 1', $rId);}
if ($rType == 'user') {$relURL = 'users/r/'.$rId; $rTypeName = 'Danh bạ cá nhân'; $q = $db->query('SELECT name FROM persons WHERE id=%i LIMIT 1', $rId);}
if ($rType == 'venue') {$relURL = 'venues/r/'.$rId; $rTypeName = 'Điểm cung cấp dịch vụ'; $q = $db->query('select name from venues where id=%i LIMIT 1', $rId);}
if ($q->countReturnedRows() > 0) $relName = $q->fetchScalar();

if ($getFrom != 0) {
  $q = $db->query('SELECT * FROM persons WHERE id=%i LIMIT 1', $getFrom);
  if ($q->countReturnedRows() > 0) {
    $from_user = $q->fetchRow();
  }
} else {
  $m_from = myID;
}

// If isset m_to
if ($getTo != 0) {
  $q = $db->query('SELECT * FROM persons WHERE id=%i LIMIT 1', $getTo);
  if ($q->countReturnedRows() > 0) {
    $to_user = $q->fetchRow();
  }
} else {
  $m_to = 0;
}

// Users for m_to
$q = $db->query('select u.id, u.lname, u.email FROM persons u, at_user_role ur where ur.user_id=u.id AND (u.id=767 OR u.id=699 OR ur.role_id IN(%i, %i)) ORDER BY lname LIMIT 100', $hxRoles['admin']['id'], $hxRoles['member']['id']);
$ux = $q->fetchAllRows();

$fv = new hxFormValidation();
$fv->setRules('title', 'Title', 'trim|required|max_length[128]|htmlspecialchars');
$fv->setRules('body', 'Body', 'trim|required');

if (fRequest::isPost()) {
	if ($fv->run()) {
    $q = $db->query('INSERT INTO at_messages (uo, ub, via, from_id, m_to, priority, co, title, body, rtype, rid) 
			VALUES (%s,%i,%s,%i, %i,%s,%s,%s,%s, %s,%i)',
      NOW,
      myID,
      'email',
      $getFrom,
      $getTo,
      'normal',
      NOW,
      $_POST['title'],
      fHTML::convertNewLines($_POST['body']),
      $rType,
      $rId
    );
    $newNoteId = $q->getAutoIncrementedValue();
    // Redir
    redirect($relURL);
    exit;
  }
} else {
  $_POST['title'] = '';
  $_POST['body'] = '';
}
*/
$this->title = 'Add email message';
$this->params['icon'] = 'envelope-o';
$this->params['breadcrumb'] = [
	['Notes', 'notes'],
	[$theName, $theLink],
];
?>
<div class="col-md-8">
	<div class="alert alert-warning">
		<i class="fa fa-fw fa-warning"></i>
		IMPORTANT: You're justing adding a record of an email you sent or received. This program will NOT send your email.
	</div>

	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><div class="well well-sm">FROM: <?= $fromUser['name'] ?> (<?= $fromUser['email'] ?>)</div></div>
		<div class="col-md-6"><div class="well well-sm">TO: <?= $toUser['name'] ?> (<?= $toUser['email'] ?>)</div></div>
	</div>
	<?= $form->field($theNote, 'title') ?>
	<?= $form->field($theNote, 'body')->textArea(['rows'=>30, 'class'=>'form-control ckeditor xredactor']) ?>
	<div class="text-right"><?= Html::submitButton('Save note', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<style type="text/css">
.cke_top, .cke_bottom, .cke_toolgroup {background-color:#eee; background-image:none;}
</style>
<?
$js = <<<TXT
$('.redactor').redactor({
	minHeight: 300,
	cleanFontTag: true,
	cleanSpaces: true,
	convertImageLinks: true,
	convertLinks: true,
	convertVideoLinks: true,
	tidyHtml: false,
	plugins: ['fontcolor', 'fullscreen']
});
TXT;
$js = <<<TXT
$('.ckeditor').ckeditor({
	customConfig: '/assets/js/ckeditor_config_simple_1.js'
});
TXT;

$this->registerJsFile(DIR.'assets/ckeditor_4.4.2/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/ckeditor_4.4.2/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);
