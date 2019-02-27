<?php
namespace app\assets;

use yii\web\AssetBundle;

class Limitless210Asset extends AssetBundle
{
    public $basePath = '@webroot/themes/limitless_2.1.0';
    public $baseUrl = '@web/themes/limitless_2.1.0';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&subset=latin,vietnamese',

        'global_assets/css/icons/icomoon/styles.css',
        'layout_2/LTR/default/full/assets/css/bootstrap.min.css',
        'layout_2/LTR/default/full/assets/css/bootstrap_limitless.min.css',
        'layout_2/LTR/default/full/assets/css/layout.min.css',
        'layout_2/LTR/default/full/assets/css/components.min.css',
        'layout_2/LTR/default/full/assets/css/colors.min.css',

        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
    ];

    public $js = [
        /* Core JS files */
        // 'global_assets/js/main/jquery.min.js'
        'global_assets/js/main/bootstrap.bundle.min.js',
        'global_assets/js/plugins/loaders/blockui.min.js',

        /* Theme js files */
        'global_assets/js/plugins/ui/perfect_scrollbar.min.js',
        'layout_2/LTR/default/full/assets/js/app.js',

        // '/theadmin.js?v=3',

        // 'global_assets/js/demo_pages/layout_fixed_sidebar_custom.js'

        // 'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
    ];

}