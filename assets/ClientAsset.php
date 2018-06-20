<?php
namespace app\assets;

use yii\web\AssetBundle;

class ClientAsset extends AssetBundle
{
	public $basePath = '@webroot/metronic_assets';
	public $baseUrl = '@web/metronic_assets';
	public $depends = [
		'yii\web\JqueryAsset',
	];
	public $css = [
		// BEGIN GLOBAL MANDATORY STYLES
		// '//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,vietnamese',
		'global/plugins/font-awesome/css/font-awesome.min.css',
		'global/plugins/simple-line-icons/simple-line-icons.min.css',
		'global/plugins/bootstrap/css/bootstrap.min.css',
		'global/plugins/uniform/css/uniform.default.css',
		'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',

		// BEGIN THEME STYLES
		'global/css/components.css', // id style_components
		'global/css/plugins.css',
		'admin/layout/css/layout.css',
		'admin/layout/css/themes/default.css', // id style_color
		// 'admin/layout/css/custom.css',
	];
	public $js = [
		// BEGIN CORE PLUGINS
		// <!--[if lt IE 9]>
		// 'global/plugins/respond.min.js',
		// 'global/plugins/excanvas.min.js',
		// <![endif]-->
		// 'global/plugins/jquery.min.js',
		'global/plugins/jquery-migrate.min.js',
		// IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip
		'global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
		'global/plugins/bootstrap/js/bootstrap.min.js',
		'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
		'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
		'global/plugins/jquery.blockui.min.js',
		'global/plugins/jquery.cokie.min.js',
		'global/plugins/uniform/jquery.uniform.min.js',
		'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',

		// GLOBAL
		'global/scripts/metronic.js',
		'admin/layout/scripts/layout.js',
		'admin/layout/scripts/quick-sidebar.js',
		// 'admin/layout/scripts/demo.js',

		'//cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.5/mousetrap.min.js',
	];
}
