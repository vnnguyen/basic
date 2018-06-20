<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use common\models\Chcongty;
use common\models\Meta;
use common\models\Term;


class ChcongtyController extends MyController
{
	public function actionIndex()
	{
		$getName = Yii::$app->request->get('name', '');
		$getStatus = Yii::$app->request->get('status', 'all');
		$getSticky = Yii::$app->request->get('sticky', 'all');
		$getCat = Yii::$app->request->get('cat', 'all');

		$query = Chcongty::find();

		if ($getStatus != 'all') $query->andWhere(['status'=>$getStatus]);
		if ($getSticky != 'all') $query->andWhere(['is_sticky'=>$getSticky]);
		//if ($getCat != 'all') $query->andWhere(['id'=>$getStatus]);
		if ($getName != 'all') $query->andWhere(['like', 'name', $getName]);

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);

		$theEntries = $query
			->select(['id', 'status', 'title', 'url_title', 'address', 'website', 'tel', 'fax', 'image'])
			->orderBy('title')
			->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('chcongty', [
			'pages'=>$pages,
			'theEntries'=>$theEntries,
			'getName'=>$getName,
			'getStatus'=>$getStatus,
			'getSticky'=>$getSticky,
			'getCat'=>$getCat,
			'statusList'=>Chcongty::$statusList,
			'catList'=>Yii::$app->params['cat-congty'],
		]);
	}

	public function actionC() {
		$theEntry = new Chcongty;
		$theEntry->scenario = 'channels/chcongty/c';

		if ($theEntry->load(Yii::$app->request->post()) && $theEntry->validate()) {
			$theEntry->url_title = Inflector::slug($theEntry->title);
			$theEntry->created_at = NOW;
			$theEntry->created_by = Yii::$app->user->id;
			$theEntry->status = 'draft';
			$theEntry->is_sticky = 'no';
			$theEntry->save();
			return $this->redirect('channels/chcongty/u/'.$theEntry->id);
		}

		return $this->render('chcongty_c', [
			'theEntry'=>$theEntry,
		]);
	}

	public function actionR($id = 0) {
		$theEntry = Chcongty::find($id);
		if (!$theEntry)
			throw new HttpException(404, 'Entry not found');

		return $this->render('chcongty_r', [
			'theEntry'=>$theEntry,
		]);
	}

	public function actionU($id = 0) {
		$theEntry = Chcongty::find($id);
		if (!$theEntry) {
			throw new HttpException(404, 'Entry not found');
		}

		$theEntry->scenario = 'channels/chcongty/u';

		if ($theEntry->load(Yii::$app->request->post())) {
			if ($theEntry->url_title == '') {
				$theEntry->url_title = Inflector::slug($theEntry->title);
			}

			$theEntry->updated_at = NOW;
			$theEntry->updated_by = Yii::$app->user->id;
			if ($theEntry->save()) {
				return $this->redirect('channels/chcongty/r/'.$id);
			}
		}

		$theFolder = '/var/www/kienviet.net/upload/cong-ty/'.substr($theEntry->created_at, 0, 7).'/'.$id;
		FileHelper::createDirectory($theFolder);

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_dir', $theFolder);
		Yii::$app->session->set('ckfinder_base_url', 'http://kienviet.net/upload/cong-ty/'.substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id']);
		Yii::$app->session->set('ckfinder_resource_name', 'cong-ty');
		Yii::$app->session->set('ckfinder_thumbs', substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id']);

		$allCategories = Term::find()
			->where(['taxonomy_id'=>3])
			->orderBy('name')
			->all();

		return $this->render('chcongty_u', [
			'allCategories'=>$allCategories,
			'theEntry'=>$theEntry,
		]);
	}

	public function actionD($id = 0) {
		$theEntry = Chcongty::find($id);
		if (!$theEntry)
			throw new HttpException(404, 'Entry not found');

		return $this->render('chcongty_d', [
			'theEntry'=>$theEntry,
		]);
	}

}
