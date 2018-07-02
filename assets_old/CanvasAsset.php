<?
namespace app\assets;

use yii\web\AssetBundle;

class CanvasAsset extends AssetBundle
{
	public $basePath = '@webroot/assets/canvas_2.5';
	public $baseUrl = '@web/assets/canvas_2.5';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700|Roboto:300,400,500,600,700,400italic&amp;subset=latin,vietnamese',
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
		'style.css',
		'css/dark.css',
		'css/font-icons.css',
		'css/animate.css',
		'css/magnific-popup.css',
		'css/responsive.css',
		'css/colors.php?color=bd499b',
	];
	public $js = [
		//'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
		'js/plugins.js',
		'js/functions.js',
	];
}
