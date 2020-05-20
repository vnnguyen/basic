<?php
namespace app\assets;

use yii\web\AssetBundle;

class GridAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/limitless_2.1.0';
    public $baseUrl = '@web/themes/limitless_2.1.0';
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&subset=latin,vietnamese',
        '/assets/simple-line-icons_2.4.0/css/simple-line-icons.css',
        'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/css/flag-icon.min.css',
        'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css',
        ['https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', 'integrity'=>'sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T', 'crossorigin'=>'anonymous'],
        'layout_2/LTR/default/full/assets/css/colors.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css',
    ];

    public $js = [
        /* Core JS files */
        // ['https://code.jquery.com/jquery-3.4.1.min.js', 'integrity'=>'sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=', 'crossorigin'=>'anonymous'],
        ['https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', 'integrity'=>'sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo', 'crossorigin'=>'anonymous'],
        ['https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', 'integrity'=>'sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6', 'crossorigin'=>'anonymous'],
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js',
    ];

}