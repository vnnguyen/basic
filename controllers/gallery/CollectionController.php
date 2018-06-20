<?php

namespace app\controllers\gallery;

use common\models\Collection;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\HttpException;

class CollectionController extends \app\controllers\MyController
{
	public function actionIndex()
	{
		$query = Collection::find();
		$countQuery = clone $query;
		$pagination = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>24,
		]);
		$theCollections = $query
			->orderBy('event_date DESC')
			->offset($pagination->offset)
			->limit($pagination->limit)
			->asArray()
			->all();
		return $this->render('collections', [
			'pagination'=>$pagination,
			'theCollections'=>$theCollections,
		]);
	}

	public function actionC($id = 0)
	{
		$theCollection = new Collection;
		$theCollection->scenario = 'collection/c';

		if ($theCollection->load(Yii::$app->request->post()) && $theCollection->validate()) {
			$theCollection->created_at = NOW;
			$theCollection->created_by = MY_ID;
			$theCollection->updated_at = NOW;
			$theCollection->updated_by = MY_ID;
			$theCollection->status = 'draft';
			$theCollection->save(false);
			return $this->redirect('@web/gallery/collections/u/'.$theCollection['id']);
		}
				
		return $this->render('collections_c', [
			'theCollection'=>$theCollection,
		]);
	}

	public function actionR($id = 0)
	{
		$theCollection = Collection::find()
			->where(['id'=>$id])
			->asArray()
			->one();
		if (!$theCollection) {
			throw new HttpException(404, 'Collection not found');
		}

		return $this->render('collections_r', [
			'theCollection'=>$theCollection,
		]);
	}

	public function actionU($id = 0)
	{
		$theCollection = Collection::find()
			->where(['id'=>$id])
			->one();
		if (!$theCollection) {
			throw new HttpException(404, 'Collection not found');
		}

		if (!in_array(MY_ID, [1, $theCollection->updated_by])) {
			throw new HttpException(403, 'You are not allowed to edit this entry');
		}

		$theCollection->scenario = 'collection/u';

		if ($theCollection->load(Yii::$app->request->post()) && $theCollection->validate()) {
			$theCollection->updated_at = NOW;
			$theCollection->updated_by = MY_ID;
			$theCollection->save(false);
			return $this->redirect('@web/gallery/collections/r/'.$theCollection['id']);
		}

		$uploadPath = '/upload/collections/'.substr($theCollection['created_at'], 0, 7).'/'.$theCollection['id'];
		\yii\helpers\FileHelper::createDirectory(Yii::getAlias('@webroot').$uploadPath);

		Yii::$app->session->set('ckfinder_authorized', true);
		Yii::$app->session->set('ckfinder_base_url', Yii::getAlias('@www').$uploadPath);
		Yii::$app->session->set('ckfinder_base_dir', Yii::getAlias('@webroot').$uploadPath);
		Yii::$app->session->set('ckfinder_role', 'user');
		Yii::$app->session->set('ckfinder_thumbs_dir', 'upload');
		Yii::$app->session->set('ckfinder_resource_name', 'upload');

		return $this->render('collections_u', [
			'theCollection'=>$theCollection,
		]);
	}
}
