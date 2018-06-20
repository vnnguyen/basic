<?
use yii\helpers\Html;
$this->title = 'Destinations';
$this->params['breadcrumb'] = [
	['Destinations', 'destinations'],
	];
$this->params['icon'] = 'globe';
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New destination', 'link'=>'destinations/c', 'active'=>SEG2 == 'c'],
	],
];
?>
<div class="col-lg-12">
<?
	$currentCountry = 'NO-COUNTRY';
	foreach ($models as $item) {
		if ($item['country_code'] != $currentCountry) {
			if ($currentCountry != 'NO-COUNTRY') { ?></div><? }
			?><div class="clearfix"><h4 class="sub"><span>
			<i class="globe"></i>
			<?
			foreach ($countries as $tc) {
				if ($item['country_code'] == $tc['code']) {
					echo $tc['name_en'];
					if ($tc['name_en'] != $tc['name_vi']) echo ' / ', $tc['name_vi'];
					break;
				}
			}
			?></span></h4><?
			$currentCountry = $item['country_code'];
		}
	?>
	<div class="clearfix thumbnail col-lg-3 ">
		<div class="pull-left" style="width:160px; height:20px; overflow:hidden;">
			<?=Html::a($item['name_en'], 'destinations/r/'.$item['id'])?>
		<?//=flag_16($item['country_code'])?>
		<?//=anchor('destinations/r/'.$item['id'], $item['name_en'], $item['name_local'] != '' ? 'title="'.$item['name_local'].'"' : '')?>
		</div>
		<div class="pull-right" style="width:30px;">
		<a title="Edit" class="muted td-n" href="<?=DIR?>v2destinations/u/<?=$item['id']?>"><i class="fa fa-edit"></i></a>
		<a title="Delete" class="muted td-n" href="<?=DIR?>v2destinations/d/<?=$item['id']?>"><i class="fa fa-trash-o"></i></a>
		</div>
	</div>
	<?
	} // foreach
	if ($currentCountry != 'NO-COUNTRY') { ?></div><? }
	?>
</div>