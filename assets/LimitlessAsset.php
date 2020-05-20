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
        'assets/css/bootstrap.min.css',
        'assets/css/bootstrap_limitless.min.css',
        'assets/css/layout.min.css',
        // 'assets/css/components.min.css',
        'https://my.amicatravel.com/assets/l2/layout_1/LTR/default/full/assets/css/components.min.css?v1',
        'assets/css/colors.min.css',
        'global_assets/css/icons/icomoon/styles.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css',
    ];

    public $js = [
        // 'global_assets/js/main/jquery.min.js',
        // 'http://code.jquery.com/jquery-migrate-3.0.1.min.js',
        'global_assets/js/main/bootstrap.bundle.min.js',
        'global_assets/js/plugins/loaders/blockui.min.js',
        'assets/js/app.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js',
        '/theadmin.js?v=3',

        // 'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
        // 'js/plugins/ui/nicescroll.min.js',
        // //'js/plugins/ui/drilldown.js',
        // 'js/plugins/notifications/pnotify.min.js',
    ];

}