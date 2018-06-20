<?
use yii\helpers\Html;
use yii\helpers\Markdown;
$this->title = 'Tour command: '.$theTour['op_code'].' - '.$theTour['op_name'];

$tourdayIds = explode(',', $theTour['day_ids']);

?>
<div class="col-md-6">
	
</div>
<div class="col-md-6">
	<ul class="nav nav-tabs mb-1em">
		<li class="active"><a href="/products/r/<?= $theTour['id'] ?>">Lịch trình</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Sales <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
				<li role="presentation" class="dropdown-header">CASES</li>
				<li class=""><a role="menuitem" href="">Name fo case</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">BOOKINGS</li>
				<li class=""><a role="menuitem" href="">Proposals &amp; Bookings</a></li>
				<li class=""><a role="menuitem" href="">Invoices</a></li>
				<li class=""><a role="menuitem" href="">Payments</a></li>
			</ul>
		<li>
		<li class=""><a href="/tours/r/<?= $theTour['id'] ?>">Operation</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Test menu <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
				<li role="presentation" class="dropdown-header">PRODUCT</li>
				<li class=""><a role="menuitem" href="">Product Overview</a></li>
				<li class=""><a role="menuitem" href="">Itinerary</a></li>
				<li class=""><a role="menuitem" href="">Prices</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">SALES</li>
				<li><a href="/products/sb/32946">Sales Overview</a></li>
				<li><a href="/bookings?product_id=32946">Bookings</a></li>
				<li class=""><a role="menuitem" href="">People</a></li>
				<li class=""><a role="menuitem" href="">Payments</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">OPERATIONS</li>
				<li><a href="/products/op/32946">Operation Overview</a></li>
				<li class=""><a role="menuitem" href="">Service costs</a></li>
				<li class=""><a role="menuitem" href="">Customers</a></li>
				<li class=""><a role="menuitem" href="">Feedback</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
			</ul>
		</li>
	</ul>
	<p>Last update <?= $theTour['updated_at'] ?></p>
<?

$cnt = 0;
foreach ($tourdayIds as $id) {
	foreach ($theTour['days'] as $day) {
		if ($day['id'] == $id) {
?>
	<hr>
	<p><strong>Day <?= ++$cnt ?></strong> <strong class="text-info"><?= $day['name'] ?></strong> <em><?= $day['meals'] ?></em></p>
	<div style="display:xnone; padding-left:2em;"><?= Markdown::process($day['body']) ?></div>
<?
		}
	}
}
?>
</div>
