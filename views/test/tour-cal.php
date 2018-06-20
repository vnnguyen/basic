<?
use yii\helpers\Html;
require_once('/var/www/__apps/my.amicatravel.com/views/fdb.php');

$this->title = 'TEST: tour cal';

$getSize = fRequest::getValid('size', array(2, 6, 12));
$getWidth = fRequest::getValid('width', array(25, 50, 75, 100));

define('WI', $getWidth);
define('MO', $getSize);

// Tour months
$q = $db->query('SELECT SUBSTRING(ct.day_from, 1, 7) AS ym, COUNT(*) AS total FROM at_tours t, at_ct ct WHERE ct.id=t.ct_id GROUP BY ym ORDER BY ym DESC');
$tourMonths = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
$tourMonthList = array(date('Y-m'));
foreach ($tourMonths as $ym) if ($ym['ym'] != date('Y-m')) $tourMonthList[] = $ym['ym'];

$getMonth = fRequest::getValid('month', $tourMonthList);

// Tours
$getToday = strtotime('today');
$getStart = strtotime($getMonth.'-01');
$getEnd = strtotime('-1 day', strtotime('+'.MO.' month', $getStart));
$getPrev = strtotime('-1 month', $getStart);

$getStart = strtotime('-2 days');
$getEnd = strtotime('+2 day');


$m0 = date('n', $getPrev);
$m1 = date('n', $getStart);
$m2 = date('n', $getEnd);
$y0 = date('Y', $getPrev);
$y1 = date('Y', $getStart);
$y2 = date('Y', $getEnd);

$q = $db->query('SELECT t.id, t.name, t.code, ct.pax, ct.days, ct.day_from FROM at_tours t, at_ct ct WHERE ct.id=t.ct_id AND ((ct.day_from>=%d AND ct.day_from<=%d) OR (DATE_ADD(ct.day_from, INTERVAL ct.days - 1 DAY)>=%d AND DATE_ADD(day_from, INTERVAL ct.days - 1 DAY)<=%d)) ORDER BY ct.day_from', $getStart, $getEnd, $getStart, $getEnd);
$theTours = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$daysInMonth[$m0] = date('t', $getPrev);

$prepend = date('w', $getStart);
$length = 0;
$getNext = $getPrev;
for ($ii = 1; $ii <= MO; $ii ++) {
	$getNext = strtotime('+1 month', $getNext);
	$displayedMonths[] = array(
		'year'=>date('Y', $getNext),
		'month'=>date('n', $getNext),
		'days'=>date('t', $getNext),
	);
	$length += date('t', $getNext);
}

$cnt = 0;
foreach ($theTours as $tt) {
	$tourColors[$tt['id']] = ++$cnt;
	
	$tourStart = strtotime($tt['day_from']);
	$tourEnd = strtotime('+'.($tt['days'] - 1).' days', $tourStart);
	
	$displayStart = max($tourStart, $getStart);
	$displayEnd = min($tourEnd, $getEnd);
	
	$displayWidth = min($tt['days'], ceil(($displayEnd - $displayStart + 1) / (60 * 60 * 24)));
	
	$displayClass = $tourStart < $getStart ? 'start-prev-month' : '';
	$displayClass .= $tourEnd > $getEnd ? ' end-next-month' : '';
	
	$tourList[$tt['id']] = array(
		'id'=>$tt['id'],
		'name'=>$tt['name'],
		'code'=>$tt['code'],
		'day_from'=>$tt['day_from'],
		'days'=>$tt['days'],
		'pax'=>$tt['pax'],
		'start'=>$displayStart,
		'end'=>$displayEnd,
		'width'=>($displayWidth * (WI + 1)) - 1,
		'class'=>$displayClass,
	);
}

?>
<div class="col-md-8">
	<p><?= $q->getSQL() ?></p>
	<form class="well well-small form-inline">
		<a class="btn" href="<?=DIR.URI?>?month=<?=$y0?>-<?=substr('0'.$m0, -2)?>">&laquo; Prev</a>
		<a class="btn" href="<?=DIR.URI?>?month=<?=$displayedMonths[1]['year']?>-<?=substr('0'.$displayedMonths[1]['month'], -2)?>">Next &raquo;</a>
		<a class="btn" href="<?=DIR.URI?>">Current</a>
		<select class="input-medium" name="month">
			<? foreach ($tourMonths as $ym) { ?>
			<option <?=$ym['ym'] == $getMonth ? 'selected="selected"' : ''?> value="<?=$ym['ym']?>"><?=$ym['ym']?> (<?=$ym['total']?>)</option>
			<? } ?>
		</select>
		<select class="input-medium" name="size">
			<option <?=$getSize == 2 ? 'selected="selected"' : ''?> value="2">2 months</option>
			<option <?=$getSize == 6 ? 'selected="selected"' : ''?> value="6">6 months</option>
			<option <?=$getSize == 12 ? 'selected="selected"' : ''?> value="12">12 months</option>
		</select>
		<select class="input-medium" name="width">
			<option <?=$getWidth == 25 ? 'selected="selected"' : ''?> value="25">25px cell</option>
			<option <?=$getWidth == 50 ? 'selected="selected"' : ''?> value="50">50px cell</option>
			<option <?=$getWidth == 75 ? 'selected="selected"' : ''?> value="75">75px cell</option>
			<option <?=$getWidth == 100 ? 'selected="selected"' : ''?> value="100">100px cell</option>
		</select>
		<button type="submit" class="btn">Go</button>
		Currently displaying <?=count($theTours)?> tours
	</form>

	<!-- REAL LIST HERE -->
	<? foreach ($theTours as $li) { ?>
	<div class="row clearfix">
		<div class="col-md-2" style="background-color:#ffc; border:1px solid #fff;"><?= Html::a($li['code'], 'tours/r/'.$li['id']) ?></div>
		<? for ($i = 0; $i < 5; $i ++) { ?>
		<div class="col-md-2" style="background-color:#f6f6f6; border:1px solid #fff;">XX</div>
		<? } ?>
	</div>
	<? } ?>


	<div class="clearfix" style="width:<?=(1 + WI) * 31 * MO?>px;">
		<div class="clearfix">
		<? foreach ($displayedMonths as $mo) { ?>
		<div class="month-name" style="width:<?=(WI + 1) * $mo['days']?>px"><?=date('F Y', mktime(0, 0, 0, $mo['month'], 1, $mo['year']))?></div>
		<? } ?>
		</div><!-- month name -->
		<div class="clearfix">
<?
$wd = $prepend - 1;
foreach ($displayedMonths as $mo) {
	for ($dd = 1; $dd <= $mo['days']; $dd ++) {
		$thisDay = strtotime($mo['year'].'-'.$mo['month'].'-'.$dd);
?>
		<div class="block month-day wd-<?=$wd?> <?=$thisDay == $getToday ? 'today' : ''?> <?=$dd == $mo['days'] ? 'last' : ''?>"><?=$dd?></div>
<?
		$wd ++;
		if ($wd > 6) $wd = 0;
	}
}
?>		
		</div><!-- month day -->
<?
$lineCnt = 0;
while (!empty($tourList) && $lineCnt < 100) {
?>
		<div class="clearfix">
<?
	$lineCnt ++;
	$wd = $prepend - 1;
	$nextAvailableDay = $getStart;
	//for ($dd = 1; $dd <= $length; $dd ++) {
		//$thisDay = strtotime('+'.($dd - 1).' days', $getStart);
	foreach ($displayedMonths as $mo) {
		for ($dd = 1; $dd <= $mo['days']; $dd ++) {
			$thisDay = strtotime($mo['year'].'-'.$mo['month'].'-'.$dd);
?>
			<div class="block day wd-<?=$wd?> <?=$thisDay == $getToday ? 'today' : ''?> <?=$dd == $mo['days'] ? 'last' : ''?>">
<?
		
			foreach ($tourList as $k=>$tt) {
				if ($nextAvailableDay > $getEnd) break;
				if ($tt['start'] == $thisDay && $tt['start'] >= $nextAvailableDay) {
					$nextAvailableDay = strtotime('+1 day', $tt['end']);
?>	
				<a href="<?=DIR?>tours/r/<?=$k?>" class="tour <?=$tt['class']?> color-<?=$tourColors[$k]?>" style="width:<?=$tt['width']?>px;">
					<a title="<?=$tt['name']?>" class="tour-name" href="<?=DIR?>tours/r/<?=$k?>"><?=$tt['code']?> <?=$tt['days']?>d</a>
				</a>
<?
					unset($tourList[$k]);
				} // if start
			} // for each
?>
			</div>
<?
			$wd ++;
			if ($wd > 6) $wd = 0;
		} // for dd
	} // for mo
?>
		</div><!-- tours -->
<?
} // while
?>
		<p>Remaining: <?=count($tourList)?></p>
	</div>
<style>
#hd, #mn, .breadcrumb {display:none;}
<? for ($ii = 1; $ii <= count($theTours); $ii ++) { ?>
.xcolor-<?=$ii?> {background-color:#<?=substr('0'.dechex(mt_rand(32, 128)), -2)?><?=substr('0'.dechex(mt_rand(32, 128)), -2)?><?=substr('0'.dechex(mt_rand(32, 128)), -2)?>;}
.color-<?=$ii?> {background-color:#148040;}
<? } ?>

.block {width:<?=WI?>px; height:30px; line-height:30px; float:left; border-right:1px solid #fff; border-bottom:1px solid #fff; text-align:center;}
.month-name {color:#999; font-weight:bold; height:20px; line-height:20px; float:left; text-transform:uppercase; border-bottom:1px solid #999;}
.month-day { color:#666;}
.week-day {color:#fff; background:#ccc;}
.day {background-color:#eee;}
.last {border-right-color:#c00;}
.wd-6 {background-color:#ddd;}
.today {background-color:#ee3;}
.tour {display:block; height:20px; position:absolute; margin-top:5px; border-radius:8px;}
.start-prev-month {border-radius:0 8px 8px 0;}
.end-next-month {border-radius:8px 0 0 8px;}
a.tour-name {text-decoration:none; display:block; position:absolute; text-align:left; padding-left:5px; position:absolute; white-space:nowrap; color:#fff; text-shadow:1px 1px #000;}
</style>
</div>
<div class="col-md-4">
	
</div>
