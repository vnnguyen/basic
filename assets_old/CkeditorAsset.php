<?
namespace app\assets;

use yii\web\AssetBundle;

class CkeditorAsset extends AssetBundle
{
    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $css = [
    ];
    public $js = [
        'https://cdn.ckeditor.com/4.6.1/full-all/ckeditor.js',
        'https://cdn.ckeditor.com/4.6.1/full-all/adapters/jquery.js',
    ];

    // Insert CKE code into page
    public static function ckeditorJs($el = 'textarea.ckeditor', $toolbar = 'full', $ckf = '')
    {
        if ($toolbar == 'full') {
            $toolbarConfig = <<<'TXT'
    toolbar: [
        // { name: 'document', items: ['Source'] },
        { name: 'styles', items: [ 'Format'] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike','RemoveFormat' ] },
        { name: 'clipboard', items: ['Undo', 'Redo', 'PasteText', 'PasteFromWord' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize'] }
    ],
TXT;
        } elseif ($toolbar == 'basic') {
            $toolbarConfig = <<<'TXT'
toolbar: [
        { name: 'basicstyles', items: ['RemoveFormat', 'Bold', 'Italic', 'Underline', 'Strike' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', '_Outdent', '_Indent', '-', 'Blockquote'] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
],
TXT;

        } else {
            $toolbarConfig = <<<'TXT'
    toolbar: [
        // { name: 'document', items: ['Source'] },
        { name: 'styles', items: [ 'Format'] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike','RemoveFormat' ] },
        { name: 'clipboard', items: ['Undo', 'Redo', 'PasteText', 'PasteFromWord' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize'] }
    ],
TXT;

        }
        $js = <<<'TXT'
$('{$el}').ckeditor({
    allowedContent: 'h1 h2 h3 h4 h5 h6 p figure figcaption hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
    disallowedContent: '*{font*}',
    language: '{$lang}',
    contentsCss: '/assets/css/style_ckeditor.css',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    extraPlugins: 'magicline,tableresize,image2,uploadimage,uploadfile',
    filebrowserBrowseUrl: '/filebrowser?ckf={$ckf}',
    uploadUrl: '/assets/ckfinder_3.4.1/core/connector/php/connector.php?ckf={$ckf}&command=QuickUpload&type=upload&responseType=json',
    height: 500,
    $toolbarConfig
});

TXT;
        $js = str_replace([
            '{$el}',
            '{$lang}',
            '{$ckf}', // CKFinder config session
            '$toolbarConfig'
            ], [
            $el,
            \Yii::$app->language,
            $ckf,
            $toolbarConfig
            ], $js);
        return $js;
    }
}
