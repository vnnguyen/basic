<?
use yii\helpers\Html;

Yii::$app->params['page_icon'] = 'book';
Yii::$app->params['page_title'] = $theEntry['title'];

?>
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Mục lục</h6>
		</div>
		<div class="panel-body">
			<ul>
				<? foreach ($theEntries as $entry) { ?>
				<li class="<?= $theEntry['id'] == $entry['id'] ? 'active text-bold' : '' ?>"><?= Html::a($entry['title'], '/help/docs?page='.$entry['id']) ?></li>
				<? } ?>
			</ul>
		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Nội dung hướng dẫn</h6>
		</div>
		<div class="panel-body">
			<?= $theEntry['body'] ?>
		</div>
	</div>
</div>