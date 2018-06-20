<?
use yii\helpers\Html;

$this->title = 'Media gallery';
Yii::$app->params['page_breadcrumbs'] = [
	['Media gallery', 'gallery'],
];

?>
<div class="col-md-12">
	<p><strong>THƯ VIỆN TRÊN GOOGLE DRIVE:</strong> Amica sử dụng Google Drive để lưu trữ nội dung thư viện này. Bạn có thể xem thư viện theo từng năm hoặc xem từng album theo sự kiện / chủ đề.</p>
	<p><?= Html::a('Click vào đây để xem toàn bộ gallery ảnh của Amica qua các năm', 'https://drive.google.com/folderview?id=1OYVVokQRwIV9BJvqpGkPMPOrOCF5q6vsvoGQP-i-Sos#grid', ['target'=>'_blank']) ?></p>

	<hr>

	<h2>SỰ KIỆN <small>Tập hợp các ảnh theo sự kiện, trình tự thời gian</small></h2>
	<div class="row"><?
		$year = 0;
		foreach ($theCollections as $collection) {
			if ($collection['stype'] == 'event') {
				if ($year != substr($collection['event_date'], 0, 4)) {
					$year = substr($collection['event_date'], 0, 4); ?>
	</div><!-- row -->
	<div class="row">
		<div class="col-md-12"><h3><i class="fa fa-calendar-o"></i> <?= $year ?></h3></div>
	</div>

	<div class="row"><?
				}
				if ($collection['external_url'] == '') {
					$link = '@web/gallery/collections/r/'.$collection['id'];
					$target = 'new';
				} else {
					$link = $collection['external_url'];
					$target = '_blank';
				} ?>
		<div class="col-lg-3 col-sm-6">
			<div class="thumbnail">
				<div class="thumb">
					<?= Html::img('@web/timthumb.php?w=600&h=400&src='.$collection['image']) ?>
					<div class="caption-overflow">
						<span>
							<a href="<?= $collection['image'] ?>" data-popup="lightbox" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
							<a href="<?= $link ?>" target="<?= $target ?>" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
						</span>
					</div>
				</div>

				<div class="caption">
					<h6 class="no-margin-top text-semibold">
						<a href="<?= $link ?>" target="<?= $target ?>" class="text-default"><?= $collection['title'] ?></a>
						<? if ($collection['external_url'] != '') { ?><!--a href="#" class="text-muted"><img src="https://is5.mzstatic.com/image/pf/us/r30/Purple3/v4/1e/f8/2f/1ef82ff2-4fac-236e-cb96-7f4836fc5022/pr_source.256x256-75.png" style="width:20px; height:20px;"></a --><? } ?>
					</h6>
					<?= date('j/n/Y', strtotime($collection['event_date'])) ?> | <?= $collection['summary'] ?>
				</div>
			</div>
		</div>
		<!--
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
				<?= Html::a(Html::img('@web/timthumb.php?w=600&h=400&src='.$collection['image'], ['class'=>'img-responsive img-thumbnail', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>Html::encode($collection['summary'])]), $link, ['target'=>$target]) ?>
				<div style="height:40px; overflow:hidden;">
					<? if ($collection['external_url'] != '') { ?><img src="http://is5.mzstatic.com/image/pf/us/r30/Purple3/v4/1e/f8/2f/1ef82ff2-4fac-236e-cb96-7f4836fc5022/pr_source.256x256-75.png" style="height:20px;"><? } ?>
					<span class="text-danger"><?= date('j/n/Y', strtotime($collection['event_date'])) ?></span>
					<?= $collection['title'] ?>
				</div>
			</div>--><?
			}
		} ?>
	</div>

	<hr>

	<h2>CHỦ ĐỀ <small>Tập hợp các ảnh theo chủ đề</small></h2>
	<div class="row"><?
		foreach ($theCollections as $collection) {
			if ($collection['stype'] == 'topic') {
				if ($collection['external_url'] == '') {
					$link = '@web/gallery/collections/r/'.$collection['id'];
					$target = 'new';
				} else {
					$link = $collection['external_url'];
					$target = '_blank';
				} ?>
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
				<?= Html::a(Html::img('@web/timthumb.php?w=600&h=400&src='.$collection['image'], ['class'=>'img-responsive img-thumbnail', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>Html::encode($collection['summary'])]), $link, ['target'=>$target]) ?>
				<div style="height:40px; overflow:hidden;">
					<? if ($collection['external_url'] != '') { ?><img src="http://is5.mzstatic.com/image/pf/us/r30/Purple3/v4/1e/f8/2f/1ef82ff2-4fac-236e-cb96-7f4836fc5022/pr_source.256x256-75.png" style="height:20px; width:20px;"><? } ?>
					<?= $collection['title'] ?>
				</div>
			</div><?
			}
		} ?>
	</div>

	<hr>

	<h2>VIDEO <small>Tập hợp các video được đưa lên trang Youtube của Amica</small></h2>
	<div class="row">
		<div class="col-md-12 clearfix">
			<? foreach ($theCollections as $collection) { ?>
			<? if ($collection['stype'] == 'video') { ?>
			<div style="float:left; text-align:center; display:inline-block; width:300px; margin:0 8px 8px 0">
				<?= Html::a(Html::img('@web/timthumb.php?w=300&h=300&src='.$collection['image'], ['style'=>'display:inline-block;', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>$collection['title']]), '@web/gallery/collections/r/'.$collection['id']) ?>
				<?= Html::a('Xem', '@web/gallery/collections/r/'.$collection['id']) ?>
				<? if ($collection['external_url'] != '') { ?>
				 - <?= Html::a('Trên Drive', $collection['external_url'], ['target'=>'_blank']) ?>
				<? } ?>
			</div>
			<? } ?>
			<? } ?>
		</div>
	</div>
</div>
<?
$js = <<<'TXT'
$('[data-toggle="tooltip"]').tooltip();
// Initialize lightbox
$('[data-popup="lightbox"]').fancybox({
	padding: 3
});

TXT;
$this->registerJsFile('/assets/limitless_1.1/assets/js/plugins/media/fancybox.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);