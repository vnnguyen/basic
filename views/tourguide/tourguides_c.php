<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tourguides_inc.php');

$this->title = 'New tour guide';

$this->params['breadcrumb'] = [
	['Drivers', '@web/drivers'],
	['New', '@web/drivers/c'],
];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<div class="alert alert-info">
		<i class="fa fa-info-circle"></i>
		<strong>NOTE</strong>
		Một người mới sẽ được thêm, sau đó bạn sẽ chuyển đến trang nhập thông tin chi tiết cho người đó. Nếu bạn muốn thêm profile tourguide cho một người đã có, hãy chọn từ <a class="alert-link" href="<?= DIR ?>users">Danh sách mọi người</a>.
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theUser, 'name') ?></div>
		<div class="col-md-6"><?= $form->field($theUser, 'country_code')->dropdownList(ArrayHelper::map($allCountries, 'code', 'name_en')) ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
</div>
<?

ActiveForm::end();