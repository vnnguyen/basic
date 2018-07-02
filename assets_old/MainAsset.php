<?
namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot/assets/limitless_2_0';
    public $baseUrl = '@web/assets/limitless_2_0';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
        'css/icons/icomoon/styles.css', // for arrows
        'css/bootstrap.min.css',//bs 4
        'css/bootstrap_limitless.min.css',//new theme
        'css/layout.min.css',
        'css/components.min.css',
        'css/colors.min.css',
    ];
    public $js = [
        'js/main/bootstrap.bundle.min.js',
        'js/plugins/loaders/blockui.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
        'js/main/app.js',
        'js/plugins/ui/nicescroll.min.js',
        'js/plugins/notifications/pnotify.min.js',
    ];
}