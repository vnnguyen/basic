<?
use yii\helpers\Html;
$this->title = 'Discussion forum';
$this->params['breadcrumb'] = [
	['Forum', 'forum'],
];
?>
<div class="col-md-12">
	<table>
		<thead></thead>
		<tbody>
			<? foreach ($thePosts as $post) { ?>
			<tr>
				<td><?= $post['id'] ?></td>
				<td><?= Html::a($post['title'], 'forum/topics/'.$post['id']) ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>

</div>