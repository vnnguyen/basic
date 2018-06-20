<?
use yii\helpers\Html;

$this->title = $model['name'];
$this->params['icon'] = 'list';

$this->params['breadcrumb'] = [
	['Community', '#'],
	['Knowledge base', '@web/kb'],
	['Lists', '@web/kb/lists'],
	['Rivals', '@web/kb/lists/rivals'],
	['View', '@web/kb/lists/rivals/r'.$model['id']],
];
$this->params['active'] = 'kb';
$this->params['active2'] = 'kblist';

?>
<div class="col-lg-8">
	<p><strong>SINCE</strong> <?=$model['byear']?> | <strong>WEBSITE</strong> <?=Html::a($model['website'], $model['website'], ['rel'=>'external'])?></p>

	<p><strong>PRODUCTS & SERVICES</strong></p>
	<div class="mb-10">
		<?=nl2br($model['products'])?>
	</div>

	<p><strong>STRENGTH</strong></p>
	<div class="mb-10">
		<?=nl2br($model['diemmanh'])?>
	</div>

	<p><strong>WEAKNESSES</strong></p>
	<div class="mb-10">
		<?=nl2br($model['diemyeu'])?>
	</div>

	<p><strong>INFORMATION</strong></p>
	<div class="mb-10">
		<?=$model['body']?>
	</div>
</div>
<div class="col-lg-4">
	<? if ($model['image']!= '') { ?>
	<p><strong>IMAGE</strong></p>
	<p><img id="image_src" class="img-responsive" src="<?=$model['image']?>"></p>
	<? } ?>
	<p class="muted">Updated <?=$model['updated_at']?> by <?=$theUser['name']?></p>
</div>