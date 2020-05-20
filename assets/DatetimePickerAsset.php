<?php
namespace app\assets;

use yii\web\AssetBundle;

class DatetimePickerAsset extends AssetBundle
{
	public $basePath = '@webroot/assets';
	public $baseUrl = '@web/assets';
	public $depends = [
		'app\assets\MainAsset',
	];
	public $css = [
		'bootstrap-datetimepicker_4.7.14/bootstrap-datetimepicker.css',
	];
	public $js = [
		'moment_2.9.0/moment.js',
		'bootstrap-datetimepicker_4.7.14/bootstrap-datetimepicker.js',
	];
}
