<?php
namespace app\assets;

use yii\web\AssetBundle;

class LimitlessAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/limitless_2';
    public $baseUrl = '@web/themes/limitless_2';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
    ];

    public $js = [
        // 'global_assets/js/main/jquery.min.js',
        'global_assets/js/main/bootstrap.bundle.min.js',
        'global_assets/js/plugins/loaders/blockui.min.js',
        'assets/js/app.js',

        // 'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
        // 'js/plugins/ui/nicescroll.min.js',
        // //'js/plugins/ui/drilldown.js',
        // 'js/plugins/notifications/pnotify.min.js',
    ];

}