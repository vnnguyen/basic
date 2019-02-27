<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

use CKSource\CKFinder\CKFinder;

class CkfinderController extends MyController
{

    public function actionFinder($ckf = '', $ui = '', $width = '100%', $height = '')
    {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"><title>File Browser</title></head><body><script src="/assets/ckfinder_3.4.5/ckfinder.php?ckf='.$ckf.'&v=2"></script><script>CKFinder.start({'.($ui == 'compact' ? 'displayFoldersPanel:false' : '').'});</script></body></html>';
    }

    public function actionConnector()
    {
        require_once '/var/www/my.amicatravel.com/www/assets/ckfinder_3.4.5/core/connector/php/vendor/autoload.php';
        // $ckfinder = new CKFinder('/var/www/my.amicatravel.com/www/assets/ckfinder_3.4.5/config.php');

        $config = [];

        $config['authentication'] = function(){
            return in_array(USER_ID, [1, 29013]);
        };

        $config['licenseName'] = '';
        $config['licenseKey']  = '';

        $config['privateDir'] = [
            'backend' => 'b2bsample',
            'tags'   => '.ckfinder/tags',
            'logs'   => '.ckfinder/logs',
            'cache'  => '.ckfinder/cache',
            'thumbs' => '.ckfinder/cache/thumbs',
        ];

        $config['images'] = [
            'maxWidth'  => 1600,
            'maxHeight' => 1200,
            'quality'   => 80,
            'sizes' => array(
                'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
                'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
                'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
            )
        ];

/*=================================== Backends ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

        $config['backends'][] = array(
            'name'         => 'b2bsample',
            'adapter'      => 'local',
            'baseUrl'      => '/sample-days/b2b',
            'root'         => '/var/www/my.amicatravel.com/www/upload/sample-days/b2b', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        /*================================ Resource Types =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

        $config['defaultResourceTypes'] = '';

        $config['resourceTypes'][] = array(
            'name'              => 'Files', // Single quotes not allowed.
            'directory'         => 'files',
            'maxSize'           => 0,
            'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
            'deniedExtensions'  => '',
            'backend'           => 'b2bsample'
        );

        $config['resourceTypes'][] = array(
            'name'              => 'Images',
            'directory'         => 'images',
            'maxSize'           => 0,
            'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
            'deniedExtensions'  => '',
            'backend'           => 'b2bsample'
        );

/*================================ Access Control =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

        $config['roleSessionVar'] = 'CKFinder_UserRole';

        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
        $config['accessControl'][] = array(
            'role'                => '*',
            'resourceType'        => '*',
            'folder'              => '/',

            'FOLDER_VIEW'         => true,
            'FOLDER_CREATE'       => true,
            'FOLDER_RENAME'       => true,
            'FOLDER_DELETE'       => true,

            'FILE_VIEW'           => true,
            'FILE_CREATE'         => true,
            'FILE_RENAME'         => true,
            'FILE_DELETE'         => true,

            'IMAGE_RESIZE'        => true,
            'IMAGE_RESIZE_CUSTOM' => true
        );


        /*================================ Other Settings =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

        $config['overwriteOnUpload'] = false;
        $config['checkDoubleExtension'] = true;
        $config['disallowUnsafeCharacters'] = false;
        $config['secureImageUploads'] = true;
        $config['checkSizeAfterScaling'] = true;
        $config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
        $config['hideFolders'] = array('.*', 'CVS', '__thumbs');
        $config['hideFiles'] = array('.*');
        $config['forceAscii'] = false;
        $config['xSendfile'] = false;

        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
        $config['debug'] = false;

        /*==================================== Plugins ========================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

        $config['pluginsDirectory'] = __DIR__ . '/plugins';
        $config['plugins'] = array();

        /*================================ Cache settings =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_cache

        $config['cache'] = array(
            'imagePreview' => 24 * 3600,
            'thumbnails'   => 24 * 3600 * 365,
            'proxyCommand' => 0
        );

        /*============================ Temp Directory settings ================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_tempDirectory

        $config['tempDirectory'] = sys_get_temp_dir();

        /*============================ Session Cause Performance Issues =======================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

        $config['sessionWriteClose'] = true;

        /*================================= CSRF protection ===================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_csrfProtection

        $config['csrfProtection'] = true;

        $config['headers'] = [];

        $ckfinder = new CKFinder($config);
        $ckfinder->run();
    }

    private function config()
    {
        $config = [];

        $config['authentication'] = $this->actionAuth();

        $config['licenseName'] = '';
        $config['licenseKey']  = '';

        $config['privateDir'] = [
            'backend' => 'b2bsample',
            'tags'   => '.ckfinder/tags',
            'logs'   => '.ckfinder/logs',
            'cache'  => '.ckfinder/cache',
            'thumbs' => '.ckfinder/cache/thumbs',
        ];

        $config['images'] = [
            'maxWidth'  => 1600,
            'maxHeight' => 1200,
            'quality'   => 80,
            'sizes' => array(
                'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
                'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
                'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
            )
        ];

/*=================================== Backends ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

        $config['backends'][] = array(
            'name'         => 'b2bsample',
            'adapter'      => 'local',
            'baseUrl'      => '/sample-days/b2b',
            'root'         => '/var/www/my.amicatravel.com/www/upload/sample-days/b2b', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        /*================================ Resource Types =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

        $config['defaultResourceTypes'] = '';

        $config['resourceTypes'][] = array(
            'name'              => 'Files', // Single quotes not allowed.
            'directory'         => 'files',
            'maxSize'           => 0,
            'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
            'deniedExtensions'  => '',
            'backend'           => 'b2bsample'
        );

        $config['resourceTypes'][] = array(
            'name'              => 'Images',
            'directory'         => 'images',
            'maxSize'           => 0,
            'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
            'deniedExtensions'  => '',
            'backend'           => 'b2bsample'
        );

/*================================ Access Control =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

        $config['roleSessionVar'] = 'CKFinder_UserRole';

        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
        $config['accessControl'][] = array(
            'role'                => '*',
            'resourceType'        => '*',
            'folder'              => '/',

            'FOLDER_VIEW'         => true,
            'FOLDER_CREATE'       => true,
            'FOLDER_RENAME'       => true,
            'FOLDER_DELETE'       => true,

            'FILE_VIEW'           => true,
            'FILE_CREATE'         => true,
            'FILE_RENAME'         => true,
            'FILE_DELETE'         => true,

            'IMAGE_RESIZE'        => true,
            'IMAGE_RESIZE_CUSTOM' => true
        );


        /*================================ Other Settings =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

        $config['overwriteOnUpload'] = false;
        $config['checkDoubleExtension'] = true;
        $config['disallowUnsafeCharacters'] = false;
        $config['secureImageUploads'] = true;
        $config['checkSizeAfterScaling'] = true;
        $config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
        $config['hideFolders'] = array('.*', 'CVS', '__thumbs');
        $config['hideFiles'] = array('.*');
        $config['forceAscii'] = false;
        $config['xSendfile'] = false;

        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
        $config['debug'] = false;

        /*==================================== Plugins ========================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

        $config['pluginsDirectory'] = __DIR__ . '/plugins';
        $config['plugins'] = array();

        /*================================ Cache settings =====================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_cache

        $config['cache'] = array(
            'imagePreview' => 24 * 3600,
            'thumbnails'   => 24 * 3600 * 365,
            'proxyCommand' => 0
        );

        /*============================ Temp Directory settings ================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_tempDirectory

        $config['tempDirectory'] = sys_get_temp_dir();

        /*============================ Session Cause Performance Issues =======================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

        $config['sessionWriteClose'] = true;

        /*================================= CSRF protection ===================================*/
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_csrfProtection

        $config['csrfProtection'] = true;

        $config['headers'] = [];
        return $config;
    }

    public function actionAuth()
    {
        return in_array(USER_ID, [1, ]);
    }

    public function actionJs()
    {
        header('Content-Type: text/javascript');
        $js = file_get_contents(Yii::getAlias('@www').'/assets/ckfinder_3.4.5/ckfinder.js');
        return $js;
    }
}
