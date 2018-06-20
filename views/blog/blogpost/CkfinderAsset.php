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
        'ckfinder_3.4.1/ckfinder.js',
    ];

    // Insert CKFinder code into page
    public static function ckfinderJs()
    {
        $js = <<<'TXT'

// ckfinder
var ckfinderUpdate = '';

function BrowseServer()
{
    var finder = new CKFinder();
    finder.basePath = '/assets/ckfinder_3.4.1/';
    finder.selectActionFunction = SetFileField;
    finder.popup();
}

function SetFileField( fileUrl )
{
    $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
    $('input.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').val(fileUrl);
}

$(function(){
    $('.ckfinder').dblclick(function(){
        ckfinderUpdate = $(this).data('ckfinder-update')
        BrowseServer();
    });
    $('input.ckfinder').change(function(){
        fileUrl = $(this).val();
        if (fileUrl == '') {
            //fileUrl = '/holder.js/100px100?theme=gray&text=No Image';
            var myImage = $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]:eq(0)');
            Holder.run({images: myImage});
        } else {
            ckfinderUpdate = $(this).data('ckfinder-update');
            $('img.ckfinder[data-ckfinder-update="'+ckfinderUpdate+'"]').attr('src', fileUrl);
        }
    });
})
TXT;
        return $js;
    }
}
