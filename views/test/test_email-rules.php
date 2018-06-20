<?
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Venue;
$this->title = 'Testing email rules';

?>
<div class="col-md-12">
	<table class="table table-condensed table-bordered">
		<tbody>
			<? $cnt = 0; $emailMapping = []; foreach ($openCases as $case) { ?>
			<tr>
				<td><?= ++$cnt ?></td>
				<td>
					<?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
					<?= $case['created_at'] ?>
				</td>
				<td><?= Html::a($case['owner']['name'], '@web/users/r/'.$case['owner']['id']) ?></td>
				<td>
					<? foreach ($case['people'] as $user) { ?>
					<div>
						<?= Html::a($user['name'], '@web/users/r/'.$user['id']) ?>
						<? foreach ($user['metas'] as $meta) { ?>
						<br><?= $meta['v'] ?>
						<?
							$emailMapping[$meta['v']] = trim(strtolower($case['id']));
						} ?>
					</div>
					<? } ?>
				</td>
				<td></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
<?
echo '<hr>INSERT INTO at_email_mapping (email, case_id) VALUES';
foreach ($emailMapping as $email=>$id) {
	echo '<br>("', $email, '", ', $id, '),';
}
