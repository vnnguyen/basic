<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppworkAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/appwork_1.2.0';
    public $baseUrl = '@web/themes/appwork_1.2.0';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        // Main font
        'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&subset=latin,vietnamese',

        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
        // 'assets/vendor/fonts/fontawesome.css',
        'assets/vendor/fonts/ionicons.css',
        // 'assets/vendor/fonts/linearicons.css',
        // 'assets/vendor/fonts/open-iconic.css',
        // 'assets/vendor/fonts/pe-icon-7-stroke.css',

        // Core stylesheets
        ['assets/vendor/css/bootstrap.css', 'class'=>'theme-settings-bootstrap-css'],
        ['assets/vendor/css/appwork.css', 'class'=>'theme-settings-appwork-css'],
        ['assets/vendor/css/theme-shadow.css', 'class'=>'theme-settings-theme-css'],
        ['assets/vendor/css/colors.css', 'class'=>'theme-settings-colors-css'],
        'assets/vendor/css/uikit.css',
        'assets/css/demo.css',

        'assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css',
        'assets/vendor/libs/select2/select2.css',

        '/themes/limitless_2/assets/css/colors.min.css',
    ];

    public $js = [
        ['assets/vendor/js/material-ripple.js', 'position'=>\yii\web\View::POS_HEAD],
        // Layout helpers
        ['assets/vendor/js/layout-helpers.js', 'position'=>\yii\web\View::POS_HEAD],
        // Theme settings
        ['assets/vendor/js/theme-settings.js', 'position'=>\yii\web\View::POS_HEAD],
        // Core scripts
        ['assets/vendor/js/pace.js', 'position'=>\yii\web\View::POS_HEAD],
        'assets/vendor/libs/popper/popper.js',
        'assets/vendor/js/bootstrap.js',
        'assets/vendor/js/sidenav.js',
        'assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
        'assets/vendor/libs/select2/select2.js',
        // Main script
        // 'assets/js/main.js',
        'assets/js/demo.js',
    ];

}