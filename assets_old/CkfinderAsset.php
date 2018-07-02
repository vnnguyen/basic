<?
namespace app\assets;

use yii\web\AssetBundle;

class CkfinderAsset extends AssetBundle
{
    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $css = [
    ];
    public $js = [
        'ckfinder_3.4.1/ckfinder.js?ver=2',
    ];

    // Insert CKFinder code into page
    public static function ckfinderJs($ckf = '')
    {
        $js = <<<'TXT'
// ckfinder

var ckfinderUpdate = '';

function selectFileWithCKFinder(el) {
    CKFinder.modal( {
        chooseFiles: true,
        connectorInfo: 'ckf={$ckf}',
        language: '{$lang}',
        displayFoldersPanel: false,
        width: 800,
        height: 600,
        onInit: function( finder ) {
            finder.on( 'files:choose', function( evt ) {
                var file = evt.data.files.first();
                $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', file.getUrl());
                $('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(file.getUrl());
            } );

            finder.on( 'file:choose:resizedImage', function( evt ) {
                $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', evt.data.resizedUrl);
                $('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(evt.data.resizedUrl);
            } );
        }
    } );
}

$(function(){
    $('.ckfinder').dblclick(function(){
        ckfinderUpdate = $(this).data('ckfinder-update')
        selectFileWithCKFinder(ckfinderUpdate)
    });
    $('input.ckfinder').change(function(){
        fileUrl = $(this).val();
        if (fileUrl == '') {
            fileUrl = 'https://placehold.it/350x150?text=No+Image';
        } else {
            ckfinderUpdate = $(this).data('ckfinder-update');
        }
        $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
    });
})
TXT;
        return str_replace([
            '{$ckf}',
            '{$lang}',
            ], [
            $ckf,
            \Yii::$app->language,
            ], $js);
    }
}
