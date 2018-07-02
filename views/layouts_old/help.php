<?
use yii\helpers\Html;

include('_nav.php');
include(Yii::getAlias('@webroot').'/../config/functions.php');// /var/www/my.amicatravel.com

app\assets\HelpAsset::register($this);

Yii::$app->params['page_title'] = Yii::$app->params['page_title'] == '' ? $this->title : Yii::$app->params['page_title'];
Yii::$app->params['page_meta_title'] = Yii::$app->params['page_meta_title'] == '' ? Yii::$app->params['page_title'] : Yii::$app->params['page_meta_title'];

$this->beginPage();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Help Center - Amica Travel IMS</title>
	<? $this->head() ?>
</head>
<body>
	<? $this->beginBody() ?>
	<header id="main-header" class="header">
		<div class="max60">
			<div class="pure-g">
				<div class="pure-u-1 pure-u-lg-1-2">
					<a class="current header-logo" href="/help"><img style="height:39px;" class="logo" src="/assets/img/logo-amica-for-ims.png" alt="Amica Travel logo" /></a>
					<h1 class="header-title"><a href="/help">Help Center</a></h1>
				</div>
				<div class="pure-u-1 pure-u-lg-1-2">
					<ul class="header-contact">
						<li><a href="mailto:support@amicatravel.com">support@amicatravel.com</a></li>
						<li>+84 9 79 70 69 36</li>
					</ul>
				</div>
			</div>
		</div>
	</header>

	<?= $content ?>


	<footer id="main-footer">
		<div class="max60">
			<div class="pure-g">
				<div class="pure-u-22-24 text-left text-small">
					<ul class="footer-menu">
						<li><a href="index.html">Help Home</a></li>
						<li><a href="books/index.html">Guides</a></li>
						<li><a href="whats-new/index.html">What’s New</a></li>
						<li><a href="mailto:bugs@amicatravel.com">Report a Bug</a></li>
						<li><a href="mailto:featurerequest@amicatravel.com">Suggest a Feature</a></li>
						<li><a href="https://help-classic.amicatravel.com/index.html">Help for Amica Travel IMS 4</a></li>
						<!-- <li><a href="https://labs.amicatravel.com/shade/index.html">Built using Shade</a></li> -->
					</ul>
				</div>
				<div class="pure-u-2-24 text-right footer-logo">
					<img class="logo-footer" src="/assets/img/logo-amica-for-ims.png" alt="Amica Travel logo" style="height:40px; width:40px;">
				</div>
			</div>
		</div>
	</footer>
	<? $this->endBody() ?>
</body>
</html> 
<? $this->endPage();

$js = <<<'TXT'
$('.inner-page-sidebar').stickit({
// Sets the element stick in the parent element or entire document.
scope: StickScope.Parent,

// Sets the class name to the element when it's stick.
className: 'stick',

// Sets sticky top, eg. it will be stuck at position top 50 if you set 50.
top: 20,

// Sets extra height for parent element, it could be used only StickScope.Parent. When the contents of parent has margin or something let the actual height out of container, you could use this options to fix.
extraHeight: 0
});
TXT;
$this->registerJs($js);