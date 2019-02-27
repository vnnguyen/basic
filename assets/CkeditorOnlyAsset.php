<?php
namespace app\assets;

use yii\web\AssetBundle;

class CkeditorOnlyAsset extends AssetBundle
{
    public $basePath = '@webroot/assets';
    public $baseUrl = '@web/assets';
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $css = [
    ];
    public $js = [
        'https://cdn.ckeditor.com/4.11.1/full-all/ckeditor.js',
        'https://cdn.ckeditor.com/4.11.1/full-all/adapters/jquery.js',
    ];

    // Insert CKE code into page
    public static function ckeditorJs($el = 'textarea.ckeditor', $toolbar = 'basic')
    {
        if ($toolbar == 'full') {
            $toolbarConfig = <<<'TXT'
    toolbar: [
        // { name: 'document', items: ['Source'] },
        { name: 'styles', items: [ 'Format'] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike','RemoveFormat' ] },
        { name: 'clipboard', items: ['Undo', 'Redo', 'PasteText', 'PasteFromWord' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize'] }
    ],
TXT;
        } elseif ($toolbar == 'basic') {
            $toolbarConfig = <<<'TXT'
toolbar: [
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
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
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize'] }
    ],
TXT;
        }
        if ($toolbar == 'full') {
        $js = <<<'TXT'
CKEDITOR.plugins.addExternal( 'autosave', '/assets/ckeditor_4.11.1/plugins/autosave/', 'plugin.js?xv' );
CKEDITOR.config.autosave = { 
    // Auto save Key - The Default autosavekey can be overridden from the config ...
    SaveKey : 'autosave_{$user}_' + location.href + $('#' + editor.name).attr('name'),

    // Ignore Content older then X (minutes, 1440 = 1 day)
    NotOlderThen : 1440,

    // Save Content on Destroy - Setting to Save content on editor destroy (Default is false) ...
    saveOnDestroy : false,

    // Setting to set the Save button to inform the plugin when the content is saved by the user and doesn't need to be stored temporarily ...
    saveDetectionSelectors : "button[type='submit']",

    // Show in the Status Bar
    messageType : "statusbar",

    // Delay
    delay : 10,

    // The Default Diff Type for the Compare Dialog, you can choose between "sideBySide" or "inline". Default is "sideBySide"
    diffType : "inline",

    // autoLoad when enabled it directly loads the saved content
    autoLoad: true
};
$('{$el}').ckeditor({
    language: '{$lang}',
    allowedContent: 'h1 h2 h3 h4 h5 h6 p figure figcaption hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
    disallowedContent: '*{font*}',
    contentsCss: '/assets/css/style_ckeditor.css',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    extraPlugins: 'magicline,tableresize,widget,autosave',
    height: 500,
    $toolbarConfig
});
TXT;
    } else {
        $js = <<<'TXT'
$('{$el}').ckeditor({
    language: '{$lang}',
    allowedContent: 'h1 h2 h3 h4 h5 h6 p figure figcaption hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
    disallowedContent: '*{font*}',
    contentsCss: '/assets/css/style_ckeditor.css',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    extraPlugins: 'magicline,tableresize',
    height: 500,
    $toolbarConfig
});
TXT;
    }
        $js = str_replace(['{$lang}', '{$user}', '{$el}', '$toolbarConfig'], [\Yii::$app->language, USER_ID, $el, $toolbarConfig], $js);
        return $js;
    }
}
