<?php

namespace app\controllers;

use Yii;
use common\models\Kblist;
use common\models\User;
use yii\data\Pagination;

class KblistController extends MyController
{
	public function actionIndex()
	{
		$query = Kblist::find()
			->where(['status'=>'on']);
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);
		$kbPosts = $query
			->select(['id', 'created_at', 'status', 'alias', 'title', 'author_id', 'summary'])
			->with(['author'])
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy('created_at DESC')
			->asArray()
			->all();
		return $this->render('kblists', [
			'kbPosts'=>$kbPosts,
			'pages'=>$pages,
		]);
	}

	public function actionR($id = 0)
	{
		$model = Kblist::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->status != 'on' && Yii::$app->user->id != $model->author_id)
			throw new HttpException(403);

		$theAuthor = User::find($model->author_id);

		return $this->render('kblists_r', [
			'model'=>$model,
			'theAuthor'=>$theAuthor,
		]);
	}
}
