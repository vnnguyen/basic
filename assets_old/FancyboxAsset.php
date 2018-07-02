<?
namespace app\assets;

use yii\web\AssetBundle;

class FancyboxAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css',
	];
	public $js = [
		'https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js',
		'https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js',
	];
}
