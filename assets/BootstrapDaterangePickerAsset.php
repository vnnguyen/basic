<?php
namespace app\assets;

use yii\web\AssetBundle;

class BootstrapDaterangePickerAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'app\assets\MainAsset',
	];
	public $css = [
		'bootstrap-daterangepicker_2.0.5/daterangepicker.css',
	];
	public $js = [
		'bootstrap-daterangepicker_2.0.5/moment.min.js',
		'bootstrap-daterangepicker_2.0.5/daterangepicker.js',
	];
}
