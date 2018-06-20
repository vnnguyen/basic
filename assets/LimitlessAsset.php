<?
namespace app\assets;

use yii\web\AssetBundle;

class LimitlessAsset extends AssetBundle
{
	public $basePath = '@webroot/assets/limitless_1.2.1';
	public $baseUrl = '@web/assets/limitless_1.2.1';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css',
		'css/icons/icomoon/styles.css',
		'css/bootstrap.min.css',
		'css/core.min.css',
		'css/components.min.css',
		'css/colors.min.css',
	];
	public $js = [
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
		'js/plugins/ui/nicescroll.min.js',
		'js/plugins/ui/drilldown.js',
		'js/core/app.js',
	];
}