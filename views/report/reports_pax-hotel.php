<?
use yii\helpers\Html;

$this->title = 'Danh sách khách sử dụng dịch vụ tại '.$theVenue['name'];
$this->title .= ' ('.count($theTours).' tour)';

$this->params['icon'] = 'list';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports', '@web/manager/reports'],
];
$yearList = [2018=>2018,2017=>2017,2016=>2016,2015=>2015,2014=>2014,2013=>2013,2012=>2012,2011=>2011,2010=>2010];
for ($mm = 1; $mm <= 12; $mm ++) {
	$monthList[0] = 'Mọi tháng';
	$monthList[$mm] = $mm;
}
$venueList = [
	1563=>'Boping, Kompong Thom, ghép',
	1197=>'Cầu, Tùng Bá, không ghép',
	1672=>'Chiến, Bảo Lạc, không ghép',
	942=>'Cư, Nộn Khê, không ghép',
	459=>'Hải, Bảo Lạc, không ghép',
	616=>'Ích, Nộn Khê, không ghép',
	1191=>'Liễu, Lũng Lai, không ghép',
	1604=>'Loma, Phong Sali, Laos, 12',
	1605=>'Lungton, Phong Sali, Laos, 6+4+4',
	1577=>'Ngoan, Nậm Ngùa, không ghép',
	807=>'Nguyên, Huế, ghép',
	1603=>'Opa, Phong Sali, Laos, 7+4+4',
	455=>'Pà Chi, Bắc Hà, ghép',
	1192=>'Phin, Vai Thai / Sạc Xậy, không ghép',
	1583=>'Phương, Bảo Lạc, không ghép',
	259=>'Phượng, Nghĩa Lộ, không ghép',
	1369=>'Quỳnh, Hà Giang, ghép',
	310=>'Sa, Bắc Hà, ghép',
	1126=>'San, Siem Reap, ghép',
	751=>'Sáng, Bắc Hà, ghép',
	1198=>'Sỹ, Séo Lủng, không ghép',
	1023=>'Tam Coc Garden, Ninh Binh, ghép',
	752=>'Tập, Ba Bể, ghép',
	581=>'Thành, Hồng Phong, không ghép',
	452=>'Tư, Mù Căng Chải, ghép',
	1193=>'Tưng, Nậm Ngùa, không ghép',
	1400=>'Việt, Bến Tre, ghép',
];

?>
<div class="col-md-12">
	<?//\fCore::expose($theTours) ?>
	<form class="form-inline well well-sm">
		<?= Html::dropdownList('id', $id, $venueList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
			<tr>
				<th width="30"></th>
				<th width="100">Tour</th>
				<th width="100">Name</th>
				<th width="50">Gender</th>
				<th width="100">Nationality</th>
				<th width="50">Age</th>
				<th width="100">Email</th>
				<th width="100">Phone</th>
				<th>Addr</th>
			</tr>
			</thead>
			<tbody>
<?
$cnt = 0;
foreach ($theTours as $tour) {
	if (isset($tour['product']['bookings'])) {
		foreach ($tour['product']['bookings'] as $booking) {
			foreach ($booking['pax'] as $user) {
?>
			<tr>
				<td class="text-muted text-center"><?= ++$cnt ?></td>
				<td class="text-nowrap"><?= Html::a($tour['code'], '@web/tours/r/'.$tour['id']) ?></td>
				<td class="text-nowrap"><?= Html::a($user['name'], '@web/users/r/'.$user['id'])?></td>
				<td class="text-nowrap text-center"><?= $user['gender'] ?></td>
				<td class="text-nowrap text-center"><?= isset($allCountries[$user['country_code']]) ? $allCountries[$user['country_code']]['name_en'] : '' ?></td>
				<td class="text-nowrap text-center"><?= $user['byear'] == 0 ? '' : date('Y') - $user['byear'] ?></td>
				<td><?= $user['email'] ?></td>
				<td class="text-nowrap"><?= $user['phone'] ?></td>
				<td><?
				/*
				foreach ($paxAddrs as $paxa) {
					if ($paxa['rid'] == $user['user_id']) echo $paxa['v'], ' &nbsp; ';
				}*/
				?></td>
			</tr>

<?
			}
		}
	}
}
?>
			</tbody>
		</table>
	</div>
</div>