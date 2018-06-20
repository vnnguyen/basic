<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_products_inc.php');

if ($theProduct->isNewRecord) {
	$this->title = 'New tour product (B2B PROD)';
	$this->params['breadcrumb'][] = ['New', 'products/c?b2b=prod'];
} else {
	Yii::$app->params['page_title'] = 'Edit B2B Prod: '.$theProduct['title'];
	Yii::$app->params['page_breadcrumbs'][] = ['Products (B2B PROD)', 'products/b2b-prod'];
	Yii::$app->params['page_breadcrumbs'][] = ['View', 'products/r/'.$theProduct['id']];
	Yii::$app->params['page_breadcrumbs'][] = ['Edit', 'products/u/'.$theProduct['id']];
}

// cac file anh banner
$files = scandir(Yii::getAlias('@webroot').'/upload/devis-banners/', 1);
asort($files);
$fileNameList = [];
foreach ($files as $k=>$v) {
	if ($v != '.' && $v != '..') {
		$fileNameList[] = ['name'=>$v];
	}
}

$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-6"><?= $form->field($theProduct, 'title')->label('Name') ?></div>
		<div class="col-md-6"><?= $form->field($theProduct, 'about')->label('Description') ?></div>
	</div>
	<?= $form->field($theProduct, 'tags') ?>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theProduct, 'language')->dropdownList($languageList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-1">Days<br><?= $theProduct['day_count'] ?></div>
		<div class="col-md-2"><?= $form->field($theProduct, 'pax') ?></div>
	</div>
	<?= $form->field($theProduct, 'intro')->textArea(['rows'=>6])->label('Esprit/Spirit') ?>

	<p><strong>PRICES AND PROMOTIONS</strong></p>
	<?= $form->field($theProduct, 'prices')->textArea(['rows'=>15]) ?>
	<?= $form->field($theProduct, 'promo')->textArea(['rows'=>10]) ?>

	<?= $form->field($theProduct, 'conditions')->textArea(['rows'=>10]) ?>
	<?= $form->field($theProduct, 'others')->textArea(['rows'=>10]) ?>

	<p><strong>NOTE (FOR AMICA ONLY)</strong></p>
	<?= $form->field($theProduct, 'summary')->textArea(['rows'=>3]) ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<?= $form->field($theProduct, 'image')->dropdownList(ArrayHelper::map($fileNameList, 'name', 'name'), ['id'=>'header-image', 'prompt'=>'- Select -']) ?>
	<div id="image-preview" class="mb-1em">
		<? if ($theProduct['image'] != '') { ?>
		<img class="img-responsive thumbnail" src="<?= DIR ?>upload/devis-banners/small/<?= $theProduct['image'] ?>" />
		<? } ?>
	</div>

	<? if (isset($theDays)) { ?>
	<p><strong>ITINERARY</strong></p>
	<ol>
		<? foreach ($theDays as $day) { ?>
		<li><?= $day['name'] ?> (<?= $day['meals'] ?>)</li>
		<? } ?>
	</ol>
	<? } ?>
</div>
<? ActiveForm::end(); ?>
<?
$js = <<<TXT
$('#header-image').change(function(){
	var image = $(this).val();
	if (image == '') {
		$('#image-preview').html('<img class="img-responsive thumbnail" src="http://placehold.it/300x100" />');
	} else {
		$('#image-preview').html('<img class="img-responsive thumbnail" src="/upload/devis-banners/small/'+image+'" />');
	}
});
$('#product-day_from, #product-price_until').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2050-01-01',
	//startDate:moment(),
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true
});
TXT;
$this->registerCssFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);