<?
namespace app\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
	];
	public $js = [
		'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
	];
}
