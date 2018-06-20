<?php
namespace app\assets;

use yii\web\AssetBundle;

class CommAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
		'//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/cerulean/bootstrap.min.css',
		'bootstrap-select_1.6.3/css/bootstrap-select.min.css',
		//'//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css',
	];
	public $js = [
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js',
		'bootstrap-select_1.6.3/js/bootstrap-select.min.js',
	];
}
