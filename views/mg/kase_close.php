<div style="width:600px;">
	<p>--- DON'T REPLY TO THIS EMAIL | ĐỪNG VIẾT TRẢ LỜI EMAIL NÀY ---</p>
	<p>Link to details: https://my.amicatravel.com/cases/r/<?= $theCase['id'] ?></p>
	<p>Creation date: <?= $theCase['created_at'] ?></p>
	<p>Owner: <?= $theCase['owner_id'] ?></p>
	<p>Sale status: <?= $theCase['deal_status'] ?></p>
	<p>Note: <?= nl2br($theCase['closed_note']) ?></p>
	<p>&mdash;<br />Updated by <?= Yii::$app->user->identity->fname ?> <?= Yii::$app->user->identity->lname ?> - Amica Travel</p>
</div>
