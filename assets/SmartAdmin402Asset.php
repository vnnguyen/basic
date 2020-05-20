<?php
namespace app\assets;

use yii\web\AssetBundle;

class SmartAdmin402Asset extends AssetBundle
{
    public $basePath = '@webroot/themes/smartadmin_4.0.2';
    public $baseUrl = '@web/themes/smartadmin_4.0.2';
    public $depends = [
        // 'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&subset=latin,vietnamese',

        'css/vendors.bundle.css',
        'css/app.bundle.css',
        '/themes/limitless_2.3.0/layout_2/LTR/default/seed/assets/css/colors.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/css/flag-icon.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css'
    ];

    public $js = [
        'js/vendors.bundle.js',
        'js/app.bundle.js',
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js',
        // 'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
    ];

}