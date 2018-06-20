<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_tcgtour_inc.php');

$this->title = $theProduct->name;

?>
<div class="col-lg-8">
	<div class="table-responsive">
		<table class="table table-condensed table-striped">
			<tbody>
				<tr>
					<td width="20%"><strong>Min pax</strong></td>
					<td><?= $theVpcTour->min_pax ?></td>
					<td width="20%"><strong>Max pax</strong></td>
					<td><?= $theVpcTour->max_pax ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?
	echo $theCt->day_ids;
	$theDayIdList = explode(',', $theCt->day_ids);
	$cnt = 0;
	foreach ($theDayIdList as $id) {
		if (isset($theDays[$id])) {
			$cnt ++;
			$li = $theDays[$id];
	?>
	<hr>
	<h4><span class="badge"><?=$cnt?></span> <?=$li['name']?></h4>
	<? if ($li['image'] != '') { ?><img src="http://my.amicatravel.com/upload/devis-days/<?=$li['image']?>" style="float:right; margin:0 0 1em 1em"><? } ?>
	<div class="clearfix"><?=Markdown::process($li['body'])?></div>
	<?
		}
	} // foreach dayid
	?>

</div>
<div class="col-lg-4">
</div>
