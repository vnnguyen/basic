<?php

namespace app\controllers;

use Yii;
use common\models\Kblpost;
use common\models\User;
use common\models\Destination;
use yii\data\Pagination;
use yii\web\HttpException;

class KblpostController extends MyController
{
	public function actionIndex()
	{
		$getDestination = Yii::$app->request->get('destination', 'default');
		if ($getDestination == 'default')
			$getDestination = Yii::$app->session->get('kb/lists/posts?destination', 'default');
		if ($getDestination == 'default')
			$getDestination = 1;
		Yii::$app->session->set('kb/lists/posts?destination', $getDestination);

		$models = Kblpost::find()
			->orderBy('category,name')
			->all();
		return $this->render('kblposts', [
			'models'=>$models,
		]);
	}

	public function actionC()
	{
		$model = new Kblpost();
		$destinations = Destination::find()
			->select(['id', 'name_en', 'country_code'])
			->orderBy('country_code, name_en')
			->asArray()
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return $this->redirect('kb/lists/posts');
		}

		return $this->render('kblposts_u', [
			'model'=>$model,
			'destinations'=>$destinations,
		]);
	}

	public function actionR($id = 0)
	{
		// No need to view
		return $this->redirect('kb/lists/posts');

		$model = Kblpost::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->status != 'on' && Yii::$app->user->id != $model->updated_by)
			throw new HttpException(403);

		$theUser = User::find($model->updated_by);

		return $this->render('kblposts_r', [
			'model'=>$model,
			'theUser'=>$theUser,
		]);
	}

	public function actionU($id = 0)
	{
		$this->layout = 'main';
		$model = Kblpost::find()
			->where(['id'=>$id])
			->with('updatedBy')
			->one();
		if (!$model)
			throw new HttpException(404);
			
		if (Yii::$app->user->id != 1 && Yii::$app->user->id != $model->created_by)
			throw new HttpException(403, 'Access denied');

		$destinations = Destination::find()
			->select(['id', 'name_en', 'country_code'])
			->orderBy('country_code, name_en')
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return $this->redirect('kb/lists/posts');
		}

		return $this->render('kblposts_u', [
			'model'=>$model,
			'destinations'=>$destinations,
		]);
	}

	public function actionD($id = 0)
	{
		$model = Kblpost::findOne($id);
		if (!$model)
			throw new HttpException(404, 'Entry not found');

		if (Yii::$app->user->id != 1 && Yii::$app->user->id != $model->created_by)
			throw new HttpException(403, 'Access denied');

		if ($model->status != 'deleted') {
			$model->scenario = 'delete';
			$model->status = 'deleted';
			$model->save();
			Yii::$app->session->setFlash('success', 'Entry has been marked DELETED: '.$model->name);
		} else {
			$model->delete();
			Yii::$app->session->setFlash('success', 'Entry has been deleted: '.$model->name);
		}
		return $this->redirect('kb/lists/posts');
	}
}
