<?php

namespace app\controllers;

use Yii;
use common\models\Kblrival;
use common\models\User;
use yii\data\Pagination;

class KblrivalController extends MyController
{
	public function actionIndex()
	{
		$query = Kblrival::find()
			->where(['status'=>'on']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);
		$models = $query
			->select('id, name, byear, website, diemmanh')
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy('name')
			->asArray()
			->all();
		return $this->render('kblrivals', [
			'models'=>$models,
			'pages'=>$pages,
		]);
	}

	public function actionR($id = 0)
	{
		$model = Kblrival::find()
			->where(['id'=>$id])
			->asArray()
			->one();

		if (!$model) {
			throw new HttpException(404);
		}

		if ($model['status'] != 'on' && Yii::$app->user->id != $model['updated_by'])
			throw new HttpException(403);

		$theUser = User::find()
			->where(['id'=>$model['updated_by']])
			->asArray()
			->one();

		return $this->render('kblrivals_r', [
			'model'=>$model,
			'theUser'=>$theUser,
		]);
	}
}
