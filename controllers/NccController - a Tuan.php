<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Company;
use common\models\User;
use common\models\Search;
use common\models\Venue;
use common\models\Ncc;
use common\models\Message;
use yii\web\HttpException;

class NccController extends MyController
{

	public function actionIndex() {
		$query = Ncc::find();
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
		]);
		$models = $query
			->orderBy('code_kt')
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();
		return $this->render('ncc', [
			'pages'=>$pages,
			'models'=>$models,
		]);
	}

	public function actionCx($id = 0) {
		$theCompany = new Company;
		$theMetas = [];

		if ($theCompany->load($_POST) && $theCompany->validate()) {
			$theCompany->save();
			return Yii::$app->response->redirect('companies');
			exit;
		}
				
		return $this->render('companies_u', [
			'theCompany'=>$theCompany,
			'theMetas'=>$theMetas,
		]);
	}

	public function actionR($id = 0) {
		$theNcc = Ncc2::find()
			->where(['id'=>$id])
			->with([
				'venue',
				])
			->asArray()
			->one();
		if (!$theNcc)
			throw new HttpException(404, 'NCC not found');
				
		return $this->render('ncc_r', [
			'theNcc'=>$theNcc,
		]);
	}

	public function actionU($id = 0) {
		$theCompany = Company::find()->where(['id'=>$id])->one();
		if (!$theCompany)
			throw new HttpException(404, 'Company not found');

		$theMetas = Meta::find()->where(['rtype'=>'company', 'rid'=>$id])->all();

		if ($theCompany->load($_POST) && $theCompany->validate()) {
			$theCompany->save();
			return Yii::$app->response->redirect('companies');
			exit;
		}
				
		return $this->render('companies_u', [
			'theCompany'=>$theCompany,
			'theMetas'=>$theMetas,
		]);
	}

	public function actionV($id = 0) {
		if (Yii::$app->user->id != 1 && Yii::$app->user->id != 11)
			throw new HttpException(403, 'Access denied');

		// id la ten venue
		$nccx = Ncc::find()->orderBy('code_kt')->asArray()->all();

		$model = Venue::findOne($id);
		if (!$model)
			throw new HttpException(404, 'Venue not found');

		if (isset($_POST['ncc_id']) && (int)$_POST['ncc_id'] != 0) {
			//$model->ncc_id = (int)$_POST['ncc_id'];
			//$model->save();
			Yii::$app->db->createCommand('UPDATE venues SET ncc_id='.((int)$_POST['ncc_id']).' WHERE id='.$id.' LIMIT 1')->execute();
			return $this->redirect('test/hotels');
		}
				
		return $this->render('ncc_v', [
			'model'=>$model,
			'nccx'=>$nccx,
		]);
	}
}
