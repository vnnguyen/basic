<?
$this->title = 'Thông tin PHP';
$this->params['breadcrumb'] = [
	['Manager', 'manager'],
	['PHP information', 'manager/phpinfo'],
];
?>
<div class="col-lg-12">
<?
ob_start();
phpinfo();
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

$output = str_replace('<table', '<div class="table-responsive"><table', $output);
$output = str_replace('</table>', '</table></div>', $output);

echo $output;
?>
</div>