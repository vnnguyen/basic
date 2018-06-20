<?php
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Myupload;

// use app\models\UploadHandler;
/*
 * jQuery File Upload Plugin PHP Class
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */


class FileuploadController extends Controller
{
    public function actionIndex(){
        $upload_handler = new Myupload([
            'user_dirs' => true,
            // 'upload_dir' => Yii::getAlias('@webroot').'/file/',
            // 'upload_url' => Yii::getAlias('@www').'/file/'
        ]);
    }
}
