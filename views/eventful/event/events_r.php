<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_events_inc.php');
$this->title  = $theEvent['name'];
//\fCore::expose($theEvent);
?>
<style type="text/css">
#blogpost_body img {max-width:100%; height:auto!important;}
</style>
<div class="col-sm-4">
	<p><?= Html::img($theEvent['image'], ['class'=>'img-responsive img-thumbnail']) ?></p>
	<p><strong>THÔNG TIN CHUNG</strong></p>
	<p><?= $theEvent['summary'] ?></p>
	<ul class="list-unstyled">
		<li><strong>THỜI GIAN:</strong> <?= date('j/n/Y l', strtotime($theEvent['from_dt'])) ?></li>
		<li><strong>ĐỊA ĐIỂM:</strong></li>
		<li><strong>THÀNH PHẦN:</strong></li>
	</ul>
	<p><strong>DANH SÁCH THAM GIA</strong></p>
	<p>Đang update...</p>
</div>
<div class="col-sm-4">
	<p><strong>CHƯƠNG TRÌNH CHI TIẾT</strong></p>
	<?= $theEvent['body'] ?>
</div>
<div class="col-sm-4">
	<p><strong>TIN BÀI LIÊN QUAN</strong></p>
	<? if ($theEvent['id'] == 1) { ?>
	<div class="row">
		<div class="col-xs-3"><?= Html::img('https://my.amicatravel.com/upload/blog/posts/2015-09/132/CoTo.jpg', ['class'=>'img-responsive']) ?></div>
		<div class="col-xs-9"><?= Html::a('Cô Tô tháng 9, bạn có đi cùng tôi không ?- L\'ile de Co To, qui va nous rejoindre ?', '@web/blog/posts/r/132') ?><br><span class="text-muted">7/9/2015</span></div>
	</div>
	<br>
	<? } ?>
	<p><strong>ẢNH, VIDEO, LINKS v.v</strong></p>
</div>