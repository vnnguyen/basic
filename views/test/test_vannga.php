<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$say = [
	'smile'=>'Likes',
	'frown'=>'Dislikes',
	'meh'=>'Comments',
];

// include('_feedbacks_inc.php');

$this->title = 'List of tour customers for Van Nga';
?>
<div class="col-md-12">
	<? if (empty($theTours)) { ?>
	<p>No tours.</p>
	<? } else { ?>
	<div class="table-responsive">
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Tour</th>
				<th>Ngày khởi hành</th>
				<th>Ngày kết thúc</th>
				<th>Tên khách</th>
				<th>Ngày sinh</th>
				<th>Giới tính</th>
				<th>Quốc tịch</th>
			</tr>
		</thead>
		<tbody>
			<?
			$code = '';
			$cnt = 0;
			foreach ($theTours as $tour) {
				foreach ($theCustomers as $customer) {
					if ($customer ['tour_id'] == $tour['id']) {
			?>
			<tr>
				<td><?= ++ $cnt ?></td>
			<?
						if ($code != $tour['op_code']) {
			?>
				<td><?= $tour['op_code'] ?></td>
				<td><?= date('j/n/Y', strtotime($tour['day_from'])) ?></td>
				<td><?= date('j/n/Y', strtotime('+'.($tour['day_count'] - 1).' days', strtotime($tour['day_from']))) ?></td>
			<?
						} else { ?>
				<td></td>
				<td></td>
				<td></td>
			<?
						} ?>
				<td><?= $customer['fname'] ?> / <?= $customer['lname'] ?></td>
				<td><?= $customer['bday'] ?>/<?= $customer['bmonth'] ?>/<?= $customer['byear'] ?></td>
				<td><?= $customer['gender'] ?></td>
				<td><?= $customer['country'] ?></td>
			</tr>
			<?
						$code = $tour['op_code'];
					}
				}
			}
			?>
		</tbody>
	</table>
	</div>
	<? } ?>
</div>