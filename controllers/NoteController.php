<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use app\models\Note;
use common\models\User;
use common\models\Kase;
use common\models\Tour;
use common\models\File;

class NoteController extends MyController
{
	public function actionCreate() {
        $action = 'create';
        $model = new Note();
        if ($model->load(Yii::$app->request->post())) {
            //CHECK AVATAR IMG FILE UPLOAD
            $model->avatar = '';
            if (isset($_POST['img-avatar']) && $_POST['img-avatar'] != '') {
                $path = "/uploads/".date('Y').DIR.date('m').DIR.USER_ID.DIR.Yii::$app->getSecurity()->generateRandomString(10).'_'.time();
                $rel_path = Yii::getAlias('@webroot').$path;
                $virtual_path = Yii::getAlias('@www').$path;
                $file_name = isset($_POST['img-avatar']) ? basename(stripslashes($_POST['img-avatar'])) : null;
                $arr_tmp_path = explode(DIR, str_replace('//', '', $_POST['img-avatar']));
                $tmp = implode(DIR, array_diff($arr_tmp_path, [$arr_tmp_path[0]]));
                $tmp_path = Yii::getAlias('@webroot').DIR.$tmp;
                $tmp_dir = dirname($tmp_path);
                if (file_exists($tmp_path)) {
                    if( !is_dir( $rel_path ) ) {
                        FileHelper::createDirectory( $rel_path );
                        // chmod( $path, 0777 );
                    }

                    $file_ext = substr(strrchr($file_name, '.'), 1);
                    $newFileName = Yii::$app->getSecurity()->generateRandomString(10).'.'.$file_ext;
                    $final_dir = $rel_path.DIR.$newFileName;
                    if (copy($tmp_path, $final_dir)) {
                        // BaseFileHelper::removeDirectory($tmp_dir);
                        $model->avatar = $virtual_path.DIR.$newFileName;
                    }
                }
            }
            if ($model->validate() && $model->save()) {
                if (isset($_POST['tmp_attach_url']) && isset($_POST['delete_url'])) {

                    foreach ($_POST['tmp_attach_url'] as $key => $tmp_file_url) {
                        if ($tmp_file_url == '') {
                            continue;
                        }
                        $path = "/uploads/".date('Y').DIR.date('m').DIR.USER_ID.DIR.Yii::$app->getSecurity()->generateRandomString(10).'_'.time();
                        $rel_path = Yii::getAlias('@webroot').$path;
                        $virtual_path = Yii::getAlias('@www').$path;
                        if (strpos($tmp_file_url, yii::getAlias('@www')) == 0) {
                            $tmp = ltrim($tmp_file_url, yii::getAlias('@www'));
                        } else {
                            $arr_tmp_path = explode(DIR, str_replace('//', '', $_POST['img-avatar']));
                            $tmp = implode(DIR, array_diff($arr_tmp_path, [$arr_tmp_path[0]]));
                        }
                        $file_name = basename(stripslashes($tmp_file_url));
                        $tmp_path = Yii::getAlias('@webroot').DIR.$tmp;

                        if (file_exists($tmp_path)) {
                            $pathInfo = pathinfo($tmp_path);
                            if( !is_dir( $rel_path ) ) {
                                FileHelper::createDirectory( $rel_path );
                                // chmod( $path, 0777 );
                            }
                            $newFileName = Yii::$app->getSecurity()->generateRandomString(10).'.'.$pathInfo['extension'];
                            $final_dir = $rel_path.DIR.$newFileName;
                            $tmp_thumbnail = $pathInfo['dirname'].'/thumbnail/'.$file_name;
                            $thumbnail_dir = $rel_path.'/thumbnail/';
                            if (file_exists($tmp_thumbnail)) {//die('ok');
                                if (!is_dir($thumbnail_dir)) {
                                    FileHelper::createDirectory( $thumbnail_dir );
                                }
                            }
                            if (copy($tmp_path, $final_dir)) {
                                if (file_exists($tmp_thumbnail)) {
                                     copy($tmp_thumbnail, $thumbnail_dir.$newFileName);
                                }
                                $file = new File();
                                $file->co = NOW;
                                $file->cb = USER_ID;
                                $file->n_id = $model->id;
                                $file->name = $pathInfo['filename'];
                                $file->url = $virtual_path.DIR.$newFileName;
                                $file->ext = '.'.$pathInfo['extension'];
                                $file->size = filesize($final_dir);
                                $file->thumbnail_url = (file_exists($tmp_thumbnail))? $virtual_path.'/thumbnail/'.$newFileName: '';
                                if (isset($_POST['delete_url'][$key])) {
                                    $file->delete_url = $_POST['delete_url'][$key];
                                }
                                $file->save(false);
                                unlink($tmp_path);
                                if ($key == count($_POST['tmp_attach_url']) - 1) {
                                    //remove tmp folder
                                    FileHelper::removeDirectory($pathInfo['dirname']);
                                }
                            }
                        }
                    }

                }
                return $this->redirect('/note/update/'.$model->id);
            }

        }
		return $this->render('_form',[
            'action' => $action,
            'model' => $model,
        ]);
	}
    public function actionUpdate($id) {
        $action = 'update';

        $model = Note::find()
            ->with([
                'files'
            ])
            ->where(['id' => $id])->one();
        if ($model == null) {
            throw new HttpException(404, 'Note not found.');
        }

        if ($model->load(Yii::$app->request->post())) {
            //CHECK AVATAR IMG FILE UPLOAD
            if (isset($_POST['img-avatar']) && $_POST['img-avatar'] != '') {
                $path = "/uploads/".date('Y').DIR.date('m').DIR.USER_ID.DIR.Yii::$app->getSecurity()->generateRandomString(10).'_'.time();
                $rel_path = Yii::getAlias('@webroot').$path;
                $virtual_path = Yii::getAlias('@www').$path;
                //remove old avatar
                $old_file = $model->avatar;

                if ($old_file != $_POST['img-avatar']) {
                    if ($old_file != '') {
                        $old_file = str_replace(Yii::getAlias('@www'), Yii::getAlias('@webroot'), $old_file);
                        $pathinfo = pathinfo($old_file);
                        $parentFolder = dirname($old_file);
                        if (file_exists($old_file)) {
                            unlink($old_file);
                            rmdir($parentFolder);
                        }
                    }
                    $file_name = isset($_POST['img-avatar']) ? basename(stripslashes($_POST['img-avatar'])) : null;
                    $arr_tmp_path = explode(DIR, str_replace('//', '', $_POST['img-avatar']));
                    $tmp = implode(DIR, array_diff($arr_tmp_path, [$arr_tmp_path[0]]));
                    $tmp_path = Yii::getAlias('@webroot').DIR.$tmp;
                    $tmp_dir = dirname($tmp_path);

                    if (file_exists($tmp_path)) {
                        if( !is_dir( $rel_path ) ) {
                            FileHelper::createDirectory( $rel_path );
                            // chmod( $path, 0777 );
                        }

                        $file_ext = substr(strrchr($file_name, '.'), 1);
                        $newFileName = Yii::$app->getSecurity()->generateRandomString(10).'.'.$file_ext;
                        $final_dir = $rel_path.DIR.$newFileName;
                        if (copy($tmp_path, $final_dir)) {
                            // BaseFileHelper::removeDirectory($tmp_dir);
                            $model->avatar = $virtual_path.DIR.$newFileName;
                        }
                    }
                }
            }
            if ($model->validate() && $model->save()) {
                if ($_POST['remove_ids'] && $_POST['remove_ids'] != '') {
                    $remove_ids = explode(',', $_POST['remove_ids']);
                    foreach ( $remove_ids as $file_id) {
                        $file = File::find()->where(['id' => $file_id])->one();
                        if ($file != null) {
                            $old_file = str_replace(Yii::getAlias('@www'), Yii::getAlias('@webroot'), $file['url']);

                            if (!file_exists($old_file)) {
                                continue;
                            }

                            if ($file->delete()) {
                                FileHelper::removeDirectory(dirname($old_file));
                            }
                            // ar_dump($file);die();

                        }
                    }
                }
                if (isset($_POST['tmp_attach_url']) && isset($_POST['delete_url'])) {

                    foreach ($_POST['tmp_attach_url'] as $key => $tmp_file_url) {
                        if ($tmp_file_url == '') {
                            continue;
                        }
                        $path = "/uploads/".date('Y').DIR.date('m').DIR.USER_ID.DIR.Yii::$app->getSecurity()->generateRandomString(10).'_'.time();
                        $rel_path = Yii::getAlias('@webroot').$path;
                        $virtual_path = Yii::getAlias('@www').$path;
                        if (strpos($tmp_file_url, yii::getAlias('@www')) == 0) {
                            $tmp = ltrim($tmp_file_url, yii::getAlias('@www'));
                        } else {
                            $arr_tmp_path = explode(DIR, str_replace('//', '', $_POST['img-avatar']));
                            $tmp = implode(DIR, array_diff($arr_tmp_path, [$arr_tmp_path[0]]));
                        }
                        $file_name = basename(stripslashes($tmp_file_url));
                        $tmp_path = Yii::getAlias('@webroot').DIR.$tmp;

                        if (file_exists($tmp_path)) {
                            $pathInfo = pathinfo($tmp_path);
                            if( !is_dir( $rel_path ) ) {
                                FileHelper::createDirectory( $rel_path );
                                // chmod( $path, 0777 );
                            }
                            $newFileName = Yii::$app->getSecurity()->generateRandomString(10).'.'.$pathInfo['extension'];
                            $final_dir = $rel_path.DIR.$newFileName;
                            $tmp_thumbnail = $pathInfo['dirname'].'/thumbnail/'.$file_name;
                            $thumbnail_dir = $rel_path.'/thumbnail/';
                            if (file_exists($tmp_thumbnail)) {//die('ok');
                                if (!is_dir($thumbnail_dir)) {
                                    FileHelper::createDirectory( $thumbnail_dir );
                                }
                            }
                            if (copy($tmp_path, $final_dir)) {
                                if (file_exists($tmp_thumbnail)) {
                                     copy($tmp_thumbnail, $thumbnail_dir.$newFileName);
                                }
                                $file = new File();
                                $file->co = NOW;
                                $file->cb = USER_ID;
                                $file->n_id = $model->id;
                                $file->name = $pathInfo['filename'];
                                $file->url = $virtual_path.DIR.$newFileName;
                                $file->ext = '.'.$pathInfo['extension'];
                                $file->size = filesize($final_dir);
                                $file->thumbnail_url = (file_exists($tmp_thumbnail))? $virtual_path.'/thumbnail/'.$newFileName: '';
                                if (isset($_POST['delete_url'][$key])) {
                                    $file->delete_url = $_POST['delete_url'][$key];
                                }
                                $file->save(false);
                                unlink($tmp_path);
                                if ($key == count($_POST['tmp_attach_url']) - 1) {
                                    //remove tmp folder
                                    FileHelper::removeDirectory($pathInfo['dirname']);
                                }
                            }
                        }
                    }

                }
                return $this->redirect('/note/update/'.$id);
            }

        }
        return $this->render('_form',[
            'action' => $action,
            'model' => $model,
        ]);
    }
    protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0 ||
            !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], DIR));
    }
	// public function actionIndex($from = 0, $to = 0, $via = 'all', $month = 'all', $title = '') {
	// 	return $this->redirect(['message/index']);
	// }

	public function actionC() {
		return $this->redirect(['message/c']);
	}

	public function actionR($id = 0)
	{
		return $this->redirect('@web/messages/r/'.$id);
	}

	public function actionU($id = 0)
	{
		return $this->redirect('@web/messages/u/'.$id);
	}

	public function actionD($id = 0)
	{
		return $this->redirect('@web/messages/d/'.$id);
	}
}
