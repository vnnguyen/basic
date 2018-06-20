<?
use yii\helpers\Html;
$ownerAtList = [];
foreach ($theTour['bookings'] as $booking) {
	if (MY_ID != $booking['case']['owner_id']) {
		$ownerAtList[$booking['case']['owner']['id']] = '@['.$booking['case']['owner']['nickname'].']';
	}
}
foreach ($theTour['tour']['operators'] as $user) {
	if (MY_ID != $user['id']) {
		$ownerAtList[$user['id']] = '@['.$user['nickname'].']';
	}
}
foreach ($theTour['tour']['cskh'] as $user) {
	if (MY_ID != $user['id']) {
		$ownerAtList[$user['id']] = '@['.$user['nickname'].']';
	}
}

$ownerAt = implode(' ', array_values($ownerAtList)).' ';
?>
				<div style="height:44px;" class="write-toggle" id="div-write-toggle"><?= Yii::t('op', 'Click here or press <kbd>p</kbd> to post') ?></div>
				<div style="display:none;" class="write-toggle">
					<form method="post" action="">
						<div id="files-list"></div>
						<p id="files-container">
							<a href="javascript:;" id="a-write-toggle" class="text-danger pull-right"><?= Yii::t('op', 'Cancel post') ?></a>
							<a id="files-browse" href="javascript:;"><?= Yii::t('op', 'Upload files') ?></a>
							<span id="files-console" class="text-danger"></span>
						</p>
						<p><textarea id="editor" class="form-control xckeditor" name="body" rows="8" style="min-height:200px;"></textarea></p>
						<p><input type="text" id="to" class="form-control" name="to" value="<?= $ownerAt ?>" autocomplete="off" placeholder="Type @ to select recipients"></p>
						<div class="row">
							<div class="col-md-10"><input type="text" id="title" class="form-control" name="title" value="" autocomplete="off" placeholder="#urgent #important #client Title (optional)"></div>
							<div class="col-md-2"><?= Html::submitButton(Yii::t('op', 'Submit'), ['class'=>'btn btn-primary btn-block']) ?></div>
						</div>
					</form>
				</div>
<?

$js = <<<'TXT'
$('div#div-write-toggle').click(function(){
	$('.write-toggle').toggle();
});
$('a#a-write-toggle').click(function(){
	$('.write-toggle').toggle(); return false;
});
TXT;
$this->registerJs($js);