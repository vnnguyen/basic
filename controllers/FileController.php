<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;
use common\models\Folder;
use common\models\File;


class FileController extends MyController
{
	public function actionIndex()
	{
		$getFolderId = Yii::$app->request->get('folder_id', 0);
		$getRtype = Yii::$app->request->get('rtype', '');
		$getName = Yii::$app->request->get('name', '');

		$query = File::find();

		if ($getRtype != '') {
			$query->andWhere(['rtype'=>$getRtype]);
		}

		if (strlen($getName) > 2) {
			$query->andWhere(['like', 'name', $getName]);
		}

		$theFolder = null;
		if ((int)$getFolderId != 0) {
			$theFolder = Folder::find($getFolderId);
			if (!$theFolder) {
				throw new HttpException(404, 'Folder not found.');
			}
			$query->andWhere(['folder_id'=>$getFolderId]);
			$countQuery = clone $query;
		} else {
			$countQuery = clone $query;
			//$query->with(['folder']);
		}

		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);

		$theFiles = $query
			->with([
				'updatedBy'=>function($q){
					return $q->select(['id', 'name']);
				}
			])
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy('uo DESC')
			->asArray()
			->all();

		$theFolders = []; /*Folder::find()
			->select(['id', 'name', 'alias'])
			->orderBy('name')
			->asArray()
			->all();
*/
		return $this->render('files', [
			'pages'=>$pages,
			'theFiles'=>$theFiles,
			'theFolder'=>$theFolder,
			'theFolders'=>$theFolders,
			'getFolderId'=>$getFolderId,
			'getRtype'=>$getRtype,
			'getName'=>$getName,
		]);
	}

	public function actionC()
	{
		$theFile = new Term;

		if ($theFile->load(Yii::$app->request->post())) {
			if ($theFile->save()) {
				return $this->redirect('terms');
			}
		}

		return $this->render('terms_u', [
			'theFile'=>$theFile,
		]);
	}

	public function actionR($id = 0)
	{
		$sql = 'SELECT f.*, fg.upload_path, fg.is_public, fg.is_ym_path FROM at_files f, at_filegroups fg WHERE f.filegroup_id=fg.id AND f.id=:id LIMIT 1';
		$theFile = Yii::$app->db->createCommand($sql, [':id'=>$id])->queryOne();

		if (!$theFile) {
			throw new HttpException(404, 'File not found');			
		}

		$theFileName = substr($theFile['name'], 0, 1-strlen($theFile['ext']));
		$theFile['path'] = 'file-'.$theFile['cb'].'-'.$theFile['id'].'-'.$theFile['uid'];
		if ($theFile['is_public'] == 'yes') $theFile['path'] = $theFile['id'].'-'.fURL::makeFriendly($theFileName, '-').$theFile['ext'];
		if ($theFile['is_ym_path'] == 'yes') $theFile['path'] = substr($theFile['co'], 0, 7).'/'.$theFile['path'];
		$theFile['path'] = $theFile['upload_path'].$theFile['path'];

		if (!file_exists($theFile['path'])) {
			throw new HttpException(404, 'File does not exsist');
		}
	
		return Yii::$app->response->sendFile($theFile['path'], $theFile['name'], [
			'inline'=>true,
			'mimeType'=>\yii\helpers\FileHelper::getMimeTypeByExtension($theFile['name']),
		]);
	}

	public function actionU($id = 0) {
		$theFile = Term::findOne($id);

		if (!$theFile) {
			throw new HttpException(404, 'Term not found.');
		}

		if ($theFile->load(Yii::$app->request->post())) {
			if ($theFile->save()) {
				return $this->redirect('terms');
			}
		}

		return $this->render('terms_u', [
			'theFile'=>$theFile,
		]);
	}

}
