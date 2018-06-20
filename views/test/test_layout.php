<?

use yii\helpers\Html;

$this->title = 'Common layout, 140901';
$this->params['icon'] = 'euro';
$this->params['breadcrumb'] = [
	['One', '1'],
	['Two', '2'],
];
$this->params['actions'] = [
	[
		['icon'=>'font', 'title'=>'Font', 'link'=>'', 'active'=>isset($_GET['font'])],
		['icon'=>'fire', 'title'=>'Fire', 'link'=>'',  'active'=>isset($_GET['fire'])],
		['icon'=>'cog', 'title'=>'Cog', 'link'=>'',  'active'=>isset($_GET['cog'])],
	],
	[
		['icon'=>'truck', 'title'=>'Truck', 'label'=>'Truck', 'link'=>'',  'active'=>isset($_GET['truck'])],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'',  'active'=>isset($_GET['edit'])],
		['icon'=>'info', 'title'=>'Info', 'link'=>'',  'active'=>isset($_GET['info'])],
	],
	[
		['icon'=>'magic', 'label'=>'Magic', 'link'=>'',  'active'=>isset($_GET['magic'])],
		['title'=>'More', 'submenu'=>[
			['icon'=>'question', 'label'=>'Question', 'link'=>'',  'active'=>isset($_GET['question'])],
			['icon'=>'flag', 'label'=>'This is a very long text to test the menu', 'link'=>'',  'active'=>isset($_GET['flag'])],
			'-',
			['icon'=>'plus', 'label'=>'Add more...', 'link'=>'',  'active'=>isset($_GET['more'])],
			]
		],
	],
];
?>
<div class="col-md-3">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs mb-1em" role="tablist">
		<li class="active"><a href="#now" role="tab" data-toggle="tab">Viewing</a></li>
		<li><a href="#all" role="tab" data-toggle="tab">My tours</a></li>
		<li><a href="#more" role="tab" data-toggle="tab">More</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="now">
		</div>
		<div class="tab-pane" id="all">
			<table class="table table-condensed">
				<tbody>
					<? foreach ($theTours as $tour) { ?>
					<tr>
						<td><?= $tour['id'] ?></td>
						<td><?= Html::a($tour['code'], '@web/tours/r/'.$tour['id']) ?></td>
						<td><?= $tour['name'] ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
		<div class="tab-pane" id="more">
			<form method="" action="" class="form-inline">
				<input type="text" class="form-control" name="q" value="" placeholder="Search">
				<button type="submit" class="btn btn-primary">Go</button>
			</form>
			<hr>
			<p><strong>RECENTLY VIEWED ITEMS</strong></p>
			<hr>
			<p><strong>STARRED ITEMS</strong></p>
		</div>
	</div>
</div>
<div class="col-md-6">
	<? $tzl = \DateTimeZone::listIdentifiers($what = DateTimeZone::PER_COUNTRY, $_SERVER['HTTP_CF_IPCOUNTRY']); ?>
	<? \fCore::expose($tzl) ?>
	<div class="alert alert-info">Information</div>
	<p>This is a test page.</p>
<?
ob_start();
phpinfo(1);
$buffer = ob_get_contents();
ob_end_clean();
$output = (preg_match("/<body.*?".">(.*)<\/body>/is", $buffer, $match)) ? $match['1'] : $buffer;
$output = preg_replace("/width\=\".*?\"/", "width=\"100%\"", $output);        
$output = preg_replace("/<hr.*?>/", "<br />", $output);
$output = preg_replace("/<a href=\"http:\/\/www.php.net\/\">.*?<\/a>/", "", $output);
$output = preg_replace("/<a href=\"http:\/\/www.zend.com\/\">.*?<\/a>/", "", $output);
$output = preg_replace("/<a.*?<\/a>/", "", $output);
$output = preg_replace("/<th(.*?)>/", "<th \\1 align=\"left\" class=\"tableHeading\">", $output); 
$output = preg_replace("/<tr(.*?).*?".">/", "<tr \\1>\n", $output);
$output = preg_replace("/<td.*?".">/", "<td valign=\"top\" class=\"tableCellOne\">", $output);
$output = preg_replace("/cellpadding=\".*?\"/", "cellpadding=\"2\"", $output);
$output = preg_replace("/cellspacing=\".*?\"/", "", $output);
$output = preg_replace("/<h2 align=\"center\">PHP License<\/h2>.*?<\/table>/si", "", $output);
$output = preg_replace("/ align=\"center\"/", "", $output);
$output = preg_replace("/<table(.*?)bgcolor=\".*?\">/", "\n\n<table\\1>", $output);
$output = preg_replace("/<table(.*?)>/", "\n\n<table\\1 class=\"table table-condensed table-striped table-bordered\" style=\"border-top-width:2px\" cellspacing=\"0\">", $output);
$output = preg_replace("/<h2>PHP License.*?<\/table>/is", "", $output);
$output = preg_replace("/<br \/>\n*<br \/>/is", "", $output);
$output = str_replace("<h1></h1>", "", $output);
$output = str_replace("<h2></h2>", "", $output);
$output = str_replace("h2", "h4", $output);
$output = str_replace("h1", "h3", $output);

$output = str_replace('<table', '<div class="table-responsive"><table class="table table-condensed table-bordered"', $output);
$output = str_replace('</table>', '</table></div>', $output);

echo $output;
?>
</div>
<div class="col-md-3">
	THIS IS A 3
</div>