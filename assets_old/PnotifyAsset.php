<?
namespace app\assets;

use yii\web\AssetBundle;

class PnotifyAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'app\assets\MainAsset',
	];
	public $css = [
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.brighttheme.min.css',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.material.min.css',
	];
	public $js = [
		//'limitless_1.2.1/js/plugins/notifications/pnotify.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.buttons.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.callbacks.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.confirm.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.desktop.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.history.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.mobile.min.js',
	];
}