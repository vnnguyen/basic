<?
namespace app\assets;

use yii\web\AssetBundle;
die('ok');
class MainAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'https://fonts.googleapis.com/css?family=Roboto:300,400,700&amp;subset=latin,vietnamese',
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
		'metronic_4.1/css/layout.css',
		//'metronic_4.1/css/light.css.php?color=33AC71',
	];
	public $js = [
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
		'jquery-slimscroll_1.3.3/jquery.slimscroll.min.js',
		//'global/plugins/jquery.blockui.min.js',
		//'global/plugins/jquery.cookie.min.js',

		// GLOBAL
		'metronic_4.1/js/metronic.js',
		'metronic_4.1/js/layout.js',
		'metronic_4.1/js/quick-sidebar.js',

		'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/holder/2.8.2/holder.min.js',
	];
}
