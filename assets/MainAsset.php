<?
namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot/assets/limitless_1_6';
    public $baseUrl = '@web/assets/limitless_1_6';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=latin,vietnamese',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'css/icons/icomoon/styles.css', // for arrows
        // 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
        'css/bootstrap.min.css',//bs 3
        'css/core.min.css',
        'css/components.min.css',
        'css/colors.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css',
    ];
    public $js = [
        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
        // 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
        // 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js',
        'js/core/app.js?x',
    ];
}