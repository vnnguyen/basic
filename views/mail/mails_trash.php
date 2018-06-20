<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

include('_mails_inc.php');

$this->title = 'Emails ('.number_format($pages->totalCount, 0).')';
$this->params['breadcrumb'] = [
	['Mails', '@web/mails'],
	['Trash', '@web/mails/trash'],
];

?>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::textInput('from_email', $getFromEmail, ['class'=>'form-control', 'placeholder'=>'From email']) ?>
		<?= Html::textInput('to_email', $getToEmail, ['class'=>'form-control', 'placeholder'=>'To email']) ?>
		<?= Html::dropdownList('attachments', $getAttachments, ['all'=>'Attachments?', 'yes'=>'With attachments', 'no'=>'No attachments'], ['class'=>'form-control']) ?>
		<?= Html::dropdownList('case_id', $getCaseId, ['all'=>'Case?', 'yes'=>'In a case', 'no'=>'Not in a case'], ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/mails') ?>
	</form>
	<? if (empty($theMails)) { ?>
	<p>No mails found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="100">Saved</th>
					<th width="100">Form</th>
					<th width="100">To</th>
					<th width="">Subject</th>
					<th>Date</th>
					<th width="100">Case</th>
					<th width="30"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theMails as $mail) { ?>
				<tr>
					<td class="text-nowrap"><?= DateTimeHelper::convert($mail['created_at'], 'd-m-Y H:i', 'UTC', Yii::$app->user->identity->timezone) ?></td>
					<td class="text-nowrap"><?= $mail['from_email'] ?></td>
					<td class="text-nowrap"><?= $mail['to_email'] ?></td>
					<td>
						<?= $mail['status'] == 'on' ? '' : '('. $mail['status']. ') ' ?>
						<? if ($mail['attachment_count'] > 0) { ?><i class="fa fa-paperclip"></i><? } ?>
						<?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?>
					</td>
					<td class="text-nowrap"><?= $mail['sent_dt_text'] ?></td>
					<td class="text-nowrap">
						<? if (isset($mail['case'])) { ?>
						<?= Html::a($mail['case']['name'], '@web/cases/r/'.$mail['case']['id']) ?>
						<span class="text-muted"><?= $mail['case']['owner']['name'] ?></span>
						<? } ?>
					</td>
					<td class="text-nowrap">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>mails/u/<?= $mail['id'] ?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>mails/d/<?= $mail['id'] ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
	<? } ?>
</div>
