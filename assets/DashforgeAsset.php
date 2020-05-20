<?php
namespace app\assets;

use yii\web\AssetBundle;

class DashforgeAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/dashforge_1.0.0';
    public $baseUrl = '@web/themes/dashforge_1.0.0';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'lib/@fortawesome/fontawesome-free/css/all.min.css',
        'lib/ionicons/css/ionicons.min.css',

        'assets/css/dashforge.css',
        'assets/css/dashforge.demo.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
    ];

    public $js = [
        'lib/jquery/jquery.min.js',
        'lib/bootstrap/js/bootstrap.bundle.min.js',
        'lib/feather-icons/feather.min.js',
        'lib/perfect-scrollbar/perfect-scrollbar.min.js',
        'assets/js/dashforge.js',

        'lib/js-cookie/js.cookie.js',
        'assets/js/dashforge.settings.ims.js'
    ];
}