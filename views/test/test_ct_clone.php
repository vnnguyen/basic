<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

require_once('/var/www/__apps/my.amicatravel.com/views/fdb.php');

$q = $db->query('SELECT * FROM at_ct WHERE id=%i LIMIT 1', Yii::$app->request->get('id'));
$theCt = $q->countReturnedRows() > 0 ? $q->fetchRow() : null;

if (!$theCt)
	die('NOT FOUND');

if (fRequest::isPost()) {
	/*
  if ($fv->run()) {
		// Clone CT
		$q = $db->query('INSERT INTO at_ct (uo, ub, status, title, summary) VALUES (%s,%i,%s,%s,%s)',NOW, myID, 'draft',$_POST['title'], $_POST['summary']);
		$newId = $q->getAutoIncrementedValue();
		$q = $db->query('UPDATE at_ct SET 
			offer_type=%s,
			offer_count=%i,
			days=%i,
			day_from=%s,
			pax=%i,
			about=%s,
			intro=%s,
			esprit=%s,
			points=%s,
			conditions=%s,
			others=%s,
			price=%s,
			price_unit=%s,
			price_for=%s,
			price_until=%s,
			prices=%s,
			promo=%s,
			image=%s
			WHERE id=%i LIMIT 1',
			$theCt['offer_type'],
			0,
			$theCt['days'],
			$theCt['day_from'],
			$theCt['pax'],
			$theCt['about'],
			$theCt['intro'],
			$theCt['esprit'],
			$theCt['points'],
			$theCt['conditions'],
			$theCt['others'],
			$theCt['price'],
			$theCt['price_unit'],
			$theCt['price_for'],
			$theCt['price_until'],
			$theCt['prices'],
			$theCt['promo'],
			$theCt['image'],
			$newId
		);
		// Clone days
		$q = $db->query('SELECT * FROM at_days WHERE rid=%i', $theCt['id']);
		$theDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		
		$dayIdList = explode(',', $theCt['day_ids']);
		
		if (is_array($dayIdList) && !empty($dayIdList)) {
			$newIdList = array();
			foreach ($dayIdList as $di) {
				foreach ($theDays as $td) {
					if ($di == $td['id']) {
						$q = $db->query('INSERT INTO at_days (uo, ub, rid, name, body, image, meals, guides, transport, hotels, others, note)
							VALUES (%s,%i,%i,%s,%s,%s,%s,%s,%s,%s,%s,%s)', 
							NOW, myID, $newId, $td['name'], $td['body'], $td['image'], $td['meals'], $td['guides'], $td['transport'], $td['hotels'], $td['others'], $td['note']
							);
						$newDayId = $q->getAutoIncrementedValue();
						if ($newDayId != 0) $newIdList[] = $newDayId;
					}
				}
			}
			// Update new CT
			$db->query('UPDATE at_ct SET days=%i, day_ids=%s WHERE id=%i LIMIT 1', count($newIdList), implode(',', $newIdList), $newId);
		}
		
		redirect('ct/r/'.$newId);
		exit;
  }*/
}

$this->title = 'Copy chương trình: <em>'.$theCt['title'].'</em> thành chương trình mới';
$this->params['icon'] = 'copy';
$this->params['breadcrumb'] = [
	['Products', 'products'],
	['Provate tours', 'products/tour'],
	['Copy', '#'],
];
?>
<div class="col-md-8">
	<h4>Chương trình mới</h4>
	<? $form = ActiveForm::begin() ?>	
	<?= $form->field($model, 'title', ['inputOptions'=>['class'=>'form-control datepicker']]); ?>
	<?=$form->field($model, 'intro')->textArea(['rows'=>4]); ?>
	<?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
	<h4>Chương trình hiện tại</h4>
  <div class="mb-5 fw-b"><?=$theCt['title']?></div>
  <div class="mb-5"><?=$theCt['about']?></div>
  <div class="mb-5"><?=$theCt['days']?> ngày - <?=$theCt['pax']?> pax - từ <?=$theCt['day_from']?></div>
</div>