<?
use yii\helpers\Html;
use yii\helpers\FileHelper;

include('_collections_inc.php');

$this->title = $theCollection['title'];
Yii::$app->params['page_small_title'] = date('j/n/Y', strtotime($theCollection['event_date']));

$theImages = FileHelper::findFiles(
	Yii::getAlias('@webroot').'/upload/collections/'.substr($theCollection['created_at'], 0, 7).'/'.$theCollection['id']
	//['only'=>['jpg', 'jpeg']]
	);

?>
<div class="col-md-12">
	<p><?= $theCollection['summary'] ?></p>

	<div class="row">
	<? foreach ($theImages as $image) {
	$image = str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@www'), $image); ?>
	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
		<div style="padding:16px 0"><a class="fancybox" rel="gallery" target="_blank" href="<?= $image ?>"><img src="/timthumb.php?w=400&h=300&src=<?= $image ?>" alt="Image" class="img-responsive img-thumbnail"></a></div>
	</div><?
	} ?>
	</div>
</div>
<?

app\assets\FancyboxAsset::register($this);
$js = <<<'TXT'
$('a.fancybox').fancybox();
TXT;
$this->registerJs($js);