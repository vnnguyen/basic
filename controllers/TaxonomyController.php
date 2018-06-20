<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Taxonomy;
use common\models\Term;


class TaxonomyController extends MyController
{
	public function actionIndex()
	{
		$theTaxonomies = Taxonomy::find()
			->orderBy('name')
			->asArray()
			->all();

		return $this->render('taxonomies', [
			'theTaxonomies'=>$theTaxonomies,
		]);
	}

	public function actionC()
	{
		$theTaxonomy = new Taxonomy;

		if ($theTaxonomy->load(Yii::$app->request->post())) {
			if ($theTaxonomy->save()) {
				return $this->redirect('taxonomies');
			}
		}

		return $this->render('taxonomies_u', [
			'theTaxonomy'=>$theTaxonomy,
		]);
	}

	public function actionU($id = 0) {
		$theTaxonomy = Taxonomy::findOne($id);

		if (!$theTaxonomy) {
			throw new HttpException(404, 'Taxonomy not found.');
		}

		if ($theTaxonomy->load(Yii::$app->request->post())) {
			if ($theTaxonomy->save()) {
				return $this->redirect('taxonomies');
			}
		}

		return $this->render('taxonomies_u', [
			'theTaxonomy'=>$theTaxonomy,
		]);
	}

}
