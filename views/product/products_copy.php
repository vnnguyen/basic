<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
$q = $db->query('SELECT * FROM at_ct WHERE id=%i LIMIT 1', seg3);
$theCt = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

$fv = new hxFormValidation();
$fv->setRules('title', 'New title', 'trim|required|htmlspecialchars');
$fv->setRules('summary', 'New summary', 'trim|htmlspecialchars');

if (fRequest::isPost()) {
  if ($fv->run()) {
		// Clone CT
		$q = $db->query('INSERT INTO at_ct (created_at, created_by, updated_at, updated_by, status, title, summary) VALUES (%s,%i,%s,%i,%s,%s,%s)',NOW, myID, NOW, myID, 'draft',$_POST['title'], $_POST['summary']);
		$newId = $q->getAutoIncrementedValue();
		$q = $db->query('UPDATE at_ct SET 
			offer_type=%s,
			offer_count=%i,
			day_count=%i,
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
			$theCt['day_count'],
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
						$q = $db->query('INSERT INTO at_days (created_at, created_by, updated_at, updated_by, rid, name, body, image, meals, guides, transport, hotels, others, note)
							VALUES (%s,%i,%s,%i,%i,%s,%s,%s,%s,%s,%s,%s,%s,%s)', 
							NOW, myID, NOW, myID, $newId, $td['name'], $td['body'], $td['image'], $td['meals'], $td['guides'], $td['transport'], $td['hotels'], $td['others'], $td['note']
							);
						$newDayId = $q->getAutoIncrementedValue();
						if ($newDayId != 0) $newIdList[] = $newDayId;
					}
				}
			}
			// Update new CT
			$db->query('UPDATE at_ct SET day_count=%i, day_ids=%s WHERE id=%i LIMIT 1', count($newIdList), implode(',', $newIdList), $newId);
		}
		
		redirect('products/r/'.$newId);
		exit;
  }
}

$pageT = 'Copy chương trình: <em>'.$theCt['title'].'</em> thành chương trình mới';
$pageM = 'ct';
$pageB = array(
	anchor('ct', 'Chương trình tour'),
	anchor('ct/r/'.seg3, $theCt['title']),
	'active'=>'Copy'
	);


*/
include('_products_inc.php');

$this->title = 'Copy product: '.$theProduct['title'];

?>
<div class="col-md-8">
	<div class="alert alert-info">
		<i class="fa fa-fw fa-info-circle"></i>
		Enter the name and summary for the new Product below. Other data will be copied over from the existing product.
	</div>
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($newProduct, 'title') ?>
	<?= $form->field($newProduct, 'summary')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
