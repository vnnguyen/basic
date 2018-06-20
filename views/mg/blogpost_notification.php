<div style="width:600px;">
	<p>Publish date: <?= date('j/n/Y', strtotime($theEntry['online_from'])) ?> by <?= $theEntry['author']['name'] ?></p>
	<p>Link to read and comment: https://my.amicatravel.com/blog/posts/r/<?= $theEntry['id'] ?></p>
	<p>----------------</p>
	<?= $theEntry['body'] ?>
	<p>----------------</p>
	<p>DON'T REPLY TO THIS EMAIL | ĐỪNG VIẾT TRẢ LỜI EMAIL NÀY</p>
</div>
