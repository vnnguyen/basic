<?php

namespace app\controllers;

use Yii;
use common\models\Kblssspot;
use common\models\User;
use common\models\Destination;
use yii\data\Pagination;
use yii\web\HttpException;

class KblssspotController extends MyController
{
	public function actionIndex()
	{
		$getDestination = Yii::$app->request->get('destination', 'default');
		if ($getDestination == 'default')
			$getDestination = Yii::$app->session->get('kb/lists/ssspots?destination', 'default');
		if ($getDestination == 'default')
			$getDestination = 1;
		Yii::$app->session->set('kb/lists/ssspots?destination', $getDestination);

		$destinations = Destination::findBySql('SELECT id, name_en, (SELECT COUNT(*) FROM at_kbl_ssspots s WHERE s.destination_id=d.id) AS total FROM at_destinations d HAVING total>0 ORDER BY country_code, name_en')->asArray()->all();
		$models = Kblssspot::find()
			->where(['destination_id'=>$getDestination])
			->orderBy('name')
			->all();
		return $this->render('kblssspots', [
			'models'=>$models,
			'destinations'=>$destinations,
			'getDestination'=>$getDestination,
		]);
	}

	public function actionC()
	{
		$model = new Kblssspot();
		$destinations = Destination::find()
			->select(['id', 'name_en', 'country_code'])
			->orderBy('country_code, name_en')
			->asArray()
			->all();

		if ($model->load($_POST) && $model->validate()) {
			$model->save();
			return $this->redirect('kb/lists/ssspots');
		}

		return $this->render('kblssspots_u', [
			'model'=>$model,
			'destinations'=>$destinations,
		]);
	}

	public function actionR($id = 0)
	{
		// No need to view
		return $this->redirect('kb/lists/ssspots');

		$model = Kblssspot::findOne($id);
		if (!$model)
			throw new HttpException(404);

		if ($model->status != 'on' && Yii::$app->user->id != $model->updated_by)
			throw new HttpException(403);

		$theUser = User::find($model->updated_by);

		return $this->render('kblssspots_r', [
			'model'=>$model,
			'theUser'=>$theUser,
		]);
	}

	public function actionU($id = 0)
	{
		$model = Kblssspot::find()
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
			return $this->redirect('kb/lists/ssspots');
		}

		return $this->render('kblssspots_u', [
			'model'=>$model,
			'destinations'=>$destinations,
		]);
	}

	public function actionD($id = 0)
	{
		$model = Kblssspot::findOne($id);
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
		return $this->redirect('kb/lists/ssspots');
	}
}
