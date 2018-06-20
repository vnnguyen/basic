<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($theDv['venue_id'] != 0) {
	$theDv['name'] = str_replace(':p', Html::a($theDv['venue']['name'], '/venues/r/'.$theDv['id']), $theDv['name']);
}

Yii::$app->params['page_title'] = 'Dịch vụ tour: '.$theDv['name'];
Yii::$app->params['page_meta_title'] = 'Dịch vụ tour: '.strip_tags($theDv['name']);
Yii::$app->params['page_breadcrumbs'] = [
	['Dữ liệu', '@web/test'],
	['Dịch vụ tour', '@web/test/dv'],
];

$dvTypeList = [
	1=>'Đi lại, vận chuyển',
	2=>'Ngủ nghỉ',
	3=>'Ăn uống',
	4=>'Tham quan, mua sắm, xem',
	5=>'Giấy tờ thủ tục',
	6=>'Guide, porter, dịch',
	7=>'Chăm sóc sức khoẻ',
	8=>'Học tập, hội họp',
	9=>'Loại khác',
];


?>
<div class="col-md-8">
	<ul>
		<li><strong>ID:</strong> <?= $theDv['id'] ?></li>
		<li><strong>Loại dv:</strong> <?= $dvTypeList[$theDv['stype']] ?></li>
		<li><strong>Điểm du lịch:</strong> <?= $theDv['destination']['name_vi'] ?></li>
		<li><strong>Tên dv:</strong> <?= $theDv['name'] ?></li>
		<li><strong>Nhà cung cấp:</strong> <?= $theDv['provider_id'] == 0 ? '-' : $theDv['provider']['name'] ?></li>
		<li><strong>Thời hạn:</strong> <?= $theDv['validity'] ?></li>
		<li><strong>Điều kiện, đối tượng:</strong> <?= $theDv['conditions'] ?></li>
		<li><strong>Ghi chú:</strong> <?= $theDv['note'] ?></li>
	</ul>
</div>
