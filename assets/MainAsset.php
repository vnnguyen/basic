<?php
namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot/assets/limitless_1.6';
    public $baseUrl = '@web/assets/limitless_1.6';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        //'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'css/icons/icomoon/styles.css', // for arrows
        'css/bootstrap.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-18/css/ionicons.min.css',
        // 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
        'css/core.min.css',
        'css/components.min.css?v=12',
        'css/colors.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
    ];

    public $js = [
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
        'js/plugins/ui/nicescroll.min.js',
        //'js/plugins/ui/drilldown.js',
        'js/core/app.min.js?e',
        'js/plugins/notifications/pnotify.min.js',
    ];

}