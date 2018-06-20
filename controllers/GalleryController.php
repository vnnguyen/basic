<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Gallery;
use yii\web\HttpException;

class GalleryController extends MyController
{
	public function actionIndex()
	{

		$query = Gallery::find();

		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$theGalleries = $query
			->orderBy('created_at DESC')
			->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('galleries', [
			'pages'=>$pages,
			'theGalleries'=>$theGalleries,
			]
		);
	}

	public function actionC()
	{
		$theGallery = new Gallery;
		$theGallery->scenario = 'create';

		if ($theGallery->load($_POST) && $theGallery->validate()) {
			$theGallery->save();
			return $this->redirect('galleries');
		}
			
		return $this->render('galleries_c', [
			'theGallery'=>$theGallery,
		]);
	}

	public function actionR($id = 0)
	{
		$theGallery = Gallery::findOne($id);
		if (!$theGallery)
			throw new HttpException(404);
			
		return $this->render('galleries_r', [
			'model'=>$theGallery,
		]);
	}


	public function actionU($id = 0)
	{
		$theGallery = Gallery::find()
			->where(['id'=>$id])
			->one();
		if (!$theGallery)
			throw new HttpException(404);

		$theGallery->scenario = 'update';

		$allCountries = Country::find()->select(['code', 'name_en'])->orderBy('name_en')->asArray()->all();

		if ($theGallery->load($_POST) && $theGallery->validate()) {
			$theGallery->save();
			return $this->redirect('customers');
		}

		return $this->render('galleries_u', [
			'model'=>$theGallery,
			'allCountries'=>$allCountries,
		]);
	}

}
