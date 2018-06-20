<?
use yii\helpers\Html;

include('_forum_topics_inc.php');

$this->title = 'Discussion forum';
?>
<div class="col-md-12">
	<table class="table table-condensed table-bordered">
		<thead>
			<tr>
				<th>Author</th>
				<th>Title</th>
				<th>Date</th>
				<th>Replies</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theTopics as $topic) { ?>
			<tr>
				<td><?= Html::a($topic['author']['name'], 'users/r/'.$topic['author']['id']) ?></td>
				<td><?= Html::a($topic['title'], 'forum/topics/'.$topic['id']) ?></td>
				<td><?= $topic['updated_at'] ?></td>
				<td><?= $topic['id'] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>