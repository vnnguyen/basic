<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

function getVarsFrom($txt = '')
{
	$ok = '';
	$fields = [];
	$parts = explode(' }}', $txt);
	foreach ($parts as $part) {
		$qa = explode('{{ ', $part);
		if (isset($qa[1])) {
			$a = explode(' | ', $qa[1]);
			if (isset($a[1])) {
				$fields[trim($a[0])] = trim($a[1]);
				$ok .= $qa[0];
				$ok .= '<span style="color:brown">'.$a[1].'</span>';
			}
			else {
				$ok .= $part.' }}';
			}
		} else {
			$ok .= $part;
		}
	}
	return $fields;
}

function getValue($key, $array)
{
	return isset($array[$key]) ? $array[$key] : '-';
}

include('_bookings_inc.php');

$this->title = 'Registration information';

$this->params['breadcrumb'][] = ['Registration info', '@web/bookings/reg-info/'.$theBooking['id']];

$regInfoKeys = [
	'pp_number'=>'Passport number',
	'pp_country_code'=>'Nationality',
	'pp_name_1'=>'Surname(s)',
	'pp_name_2'=>'Given names(s)',
	'pp_gender'=>'Gender',
	'pp_bday'=>'Day of birth',
	'pp_bmonth'=>'Month of birth',
	'pp_byear'=>'Year of birth',
	'pp_iday'=>'Passport day of issue',
	'pp_imonth'=>'Passport month of issue',
	'pp_iyear'=>'Passport year of issue',
	'pp_eday'=>'Passport number',
	'pp_emonth'=>'Passport number',
	'pp_eyear'=>'Passport number',
	'tel_1'=>'Landline',
	'tel_2'=>'Mobile',
	'email'=>'Email',
	'website'=>'Website',

	'visa_vn_arrival'=>'Visa on arrival (Vietnam only)',

	'in_name'=>'Travel insurance company',
	'in_number'=>'Travel insurance policy no.',
	'in_tel'=>'Travel insurance company tel',
	'in_email'=>'Travel insurance company email',

	'em_name'=>'Emergency contact person',
	'em_relation'=>'Emergency contact relation',
	'em_tel'=>'Emergency contact tel',
	'em_email'=>'Emergency contact email',
];

?>
<div class="col-md-6">
	<h4><?= Yii::t('mn', 'Travellers') ?></h4>
	<? if (empty($regInfo['travellers'])) { ?>
	<p><?= Yii::t('mn', 'No data found.') ?></p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30"></th>
					<th><?= Yii::t('mn', 'Name') ?> (<?= Yii::t('mn', 'Click to view') ?>)</th>
					<th><?= Yii::t('mn', 'Gender') ?></th>
					<th><?= Yii::t('mn', 'Date of birth') ?></th>
					<th><?= Yii::t('mn', 'Nationality') ?></th>
					<th><?= Yii::t('mn', 'Passport number') ?></th>
				</tr>
			</thead>
			<tbody>
			<?
			$cnt = 0;
			foreach ($regInfo['travellers'] as $pax) {
				$pax['vars'] = getVarsFrom($pax['data']);
			?>
			<tr>
				<td class="text-center"><?= ++$cnt ?></td>
				<td>
					<? if ($pax['passport_file'] != '') { ?>
					<?= Html::a(Html::img($pax['passport_file'] .'-/resize/20x20/'), $pax['passport_file']) ?>
					<? } ?>
					<strong><?= Html::a($pax['name'], DIR.URI.'?action=edit&paxid='.$pax['id']) ?></strong>
				</td>
				<td><?= isset($pax['vars']['pp_gender']) ? Yii::t('mn', ucfirst($pax['vars']['pp_gender'])) : '' ?></td>
				<td><?= isset($pax['vars']['pp_bday']) ? $pax['vars']['pp_bday'] : '' ?>/<?= isset($pax['vars']['pp_bmonth']) ? $pax['vars']['pp_bmonth'] : '' ?>/<?= isset($pax['vars']['pp_byear']) ? $pax['vars']['pp_byear'] : '' ?></td>
				<td><?
				if (isset($pax['vars']['pp_country_code'])) {
					foreach ($countryList as $country) {
						if ($country['code'] == $pax['vars']['pp_country_code']) {
							echo $country['name'];
						}
					}
				}
				?>
				</td>
				<td><?= isset($pax['vars']['pp_number']) ? $pax['vars']['pp_number'] : '' ?></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">
					<div><strong>Passport number</strong>: <?= getValue('pp_number', $pax['vars']) ?>
						&nbsp; <strong>Date of issue</strong>: <?= getValue('pp_iday', $pax['vars']) ?>/<?= getValue('pp_imonth', $pax['vars']) ?>/<?= getValue('pp_iyear', $pax['vars']) ?>
						&nbsp; <strong>Date of expiry</strong>: <?= getValue('pp_eday', $pax['vars']) ?>/<?= getValue('pp_emonth', $pax['vars']) ?>/<?= getValue('pp_eyear', $pax['vars']) ?>
					</div>
					<br>
					<div><strong>Name(s) as in passport</strong>: <?= getValue('pp_name_1', $pax['vars']) ?> / <?= getValue('pp_name_2', $pax['vars']) ?></div>
					<div><strong>Date of birth</strong>: <?= getValue('pp_bday', $pax['vars']) ?>/<?= getValue('pp_bmonth', $pax['vars']) ?>/<?= getValue('pp_byear', $pax['vars']) ?>
						&nbsp;
						<strong>Gender</strong>: <?= getValue('pp_gender', $pax['vars']) ?>
						&nbsp;
						<strong>Nationality</strong>: <?= getValue('pp_country_code', $pax['vars']) ?>
					</div>
					<div><strong>Place of birth</strong>: <?= getValue('place_of_birth', $pax['vars']) ?></div>
					<br>
					<div><strong>Email</strong>: <?= getValue('email', $pax['vars']) ?> &nbsp; <strong>Website</strong>: <?= getValue('website', $pax['vars']) ?></div>
					<div><strong>Fixed tel.</strong>: <?= getValue('tel_1', $pax['vars']) ?> &nbsp; <strong>Mobile</strong>: <?= getValue('tel_2', $pax['vars']) ?></div>
					<div><strong>Address</strong>: <?= getValue('address', $pax['vars']) ?></div>
					<div><strong>Profession</strong>: <?= getValue('tel_1', $pax['vars']) ?></div>
					<br>
					<div><strong>Visa on arrival (Vietnam)</strong>: <?= getValue('visa_vn_arrival', $pax['vars']) ?></div>
					<br>
					<div>
						<strong>Payment (deposit)</strong>: <?= getValue('pay_deposit', $pax['vars']) ?>
						&nbsp;
						<strong>Payment (balance)</strong>: <?= getValue('pay_balance', $pax['vars']) ?>
					</div>
					<br>
					<div><strong>Travel insurance</strong>: <?= getValue('in_name', $pax['vars']) ?> / <?= getValue('in_number', $pax['vars']) ?> / <?= getValue('in_tel', $pax['vars']) ?> / <?= getValue('in_email', $pax['vars']) ?></div>
					<br>
					<div><strong>Emergency contact</strong>: <?= getValue('em_name', $pax['vars']) ?> / <?= getValue('em_relation', $pax['vars']) ?> / <?= getValue('em_tel', $pax['vars']) ?> / <?= getValue('em_email', $pax['vars']) ?></div>
					<br>
					<div><strong>Note</strong>: <?= getValue('note', $pax['vars']) ?></div>

				</td>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>


	<h4><?= Yii::t('mn', 'Rooming list') ?></h4>
	<? if (empty($regInfo['rooms'])) { ?>
	<p><?= Yii::t('mn', 'No information found. Please add rooms using the form below.') ?></p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="border-left:0; border-right:0;">
			<thead>
				<tr>
					<th width="50"><?= Yii::t('mn', 'Room') ?></th>
					<th><?= Yii::t('mn', 'Room type') ?></th>
					<th><?= Yii::t('mn', 'Person(s)') ?></th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($regInfo['rooms'] as $room) { ?>
				<tr><td colspan="5" style="border:0;"></td></tr>
				<tr>
					<td class="text-center"><?= ++$cnt ?></td>
					<td><?
					if (isset($roomTypeList[$room['room_type']])) {
						echo $roomTypeList[$room['room_type']];
					} else {
						echo ucfirst($room['room_type']);
					}
					?></td>
					<td><?
					$paxIdList = explode(',', $room['pax_ids']);
					foreach ($regInfo['travellers'] as $pax) {
						if (in_array($pax['id'], $paxIdList)) {
							echo '<div>', $pax['name'], '</div>';
						}
					}
					?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>
<div class="col-md-6">
	<h4><?= Yii::t('mn', 'Transportation') ?></h4>
	<? if (empty($regInfo['flights'])) { ?>
	<p><?= Yii::t('mn', 'No data found.') ?></p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered" style="border-left:0; border-right:0;">
			<thead>
				<tr>
					<th width="30"></th>
					<th colspan="3"><?= Yii::t('mn', 'Information') ?></th>
					<th width="60"></th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($regInfo['flights'] as $flight) { ?>
				<tr><td colspan="5" style="border:0;"></td></tr>
				<tr>
					<td class="text-center" rowspan="4"><?= ++$cnt ?></td>
					<td><strong><?
					if (isset($transportTypeList[$flight['stype']])) {
						echo $transportTypeList[$flight['stype']];
					} else {
						echo $flight['stype'];
					}
					?></strong>
					</td>
					<td colspan="2"><?= $flight['number'] ?></td>
					<td rowspan="4"></td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('mn', 'Departure') ?></strong></td>
					<td><?= $flight['departure_port'] ?></td>
					<td><?= date('j/n/Y H:i', strtotime($flight['departure_dt'])) ?></td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('mn', 'Arrival') ?></strong></td>
					<td><?= $flight['arrival_port'] ?></td>
					<td><?= date('j/n/Y H:i', strtotime($flight['arrival_dt'])) ?></td>
				</tr>
				<tr>
					<td><strong><?= Yii::t('mn', 'Passengers') ?></strong></td>
					<td colspan="2"><?
					$paxIdList = explode(',', $flight['pax_ids']);
					foreach ($regInfo['travellers'] as $pax) {
						if (in_array($pax['id'], $paxIdList)) {
							echo '<div>', $pax['name'], '</div>';
						}
					}
					?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>