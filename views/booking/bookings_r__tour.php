<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tcgtour_inc.php');

$this->title = 'A booking';

?>
<div class="col-lg-12">
	<!-- Nav tabs -->
	<ul class="nav nav-pills">
	<li class="active"><a href="#t1" data-toggle="tab">Tổng quát</a></li>
	<li><a href="#t2" data-toggle="tab">Chương trình</a></li>
	<li><a href="#t3" data-toggle="tab">Khách</a></li>
	<li><a href="#t4" data-toggle="tab">Chi phí</a></li>
	<li><a href="#t5" data-toggle="tab">Thanh toán</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="t1">...</div>
		<div class="tab-pane" id="t2">...</div>
		<div class="tab-pane" id="t3">...</div>
		<div class="tab-pane" id="t4">...</div>
		<div class="tab-pane" id="t5">...</div>
	</div>
</div>
