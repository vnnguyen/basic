<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Taxonomy;
use common\models\Term;


class TermController extends MyController
{
	public function actionIndex()
	{
		$getTaxonomyId = Yii::$app->request->get('taxonomy_id', 0);

		$query = Term::find()
			->orderBy('name');

		$theTaxonomy = null;
		if ((int)$getTaxonomyId != 0) {
			$theTaxonomy = Taxonomy::find()->where(['id'=>$getTaxonomyId])->one();
			if (!$theTaxonomy) {
				throw new HttpException(404, 'Taxonomy not found.');
			}
			$query->andWhere(['taxonomy_id'=>$getTaxonomyId]);
			$countQuery = clone $query;
		} else {
			$countQuery = clone $query;
			$query->with(['taxonomy']);
		}

		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'=>25,
			]);

		$theTerms = $query
			->offset($pages->offset)
			->limit($pages->limit)
			->asArray()
			->all();

		$theTaxonomies = Taxonomy::find()
			->select(['id', 'name', 'alias'])
			->orderBy('name')
			->asArray()
			->all();

		return $this->render('terms', [
			'pages'=>$pages,
			'theTerms'=>$theTerms,
			'theTaxonomy'=>$theTaxonomy,
			'theTaxonomies'=>$theTaxonomies,
			'getTaxonomyId'=>$getTaxonomyId,
		]);
	}

	public function actionC()
	{
		$theTerm = new Term;

		if ($theTerm->load(Yii::$app->request->post())) {
			if ($theTerm->save()) {
				return $this->redirect('terms');
			}
		}

		return $this->render('terms_u', [
			'theTerm'=>$theTerm,
		]);
	}

	public function actionR($id = 0)
	{
		$theTerm = Term::findOne($id);

		if (!$theTerm) {
			throw new HttpException(404, 'Term not found.');
		}

		return $this->render('terms_r', [
			'theTerm'=>$theTerm,
		]);
	}

	public function actionU($id = 0) {
		$theTerm = Term::findOne($id);

		if (!$theTerm) {
			throw new HttpException(404, 'Term not found.');
		}

		if ($theTerm->load(Yii::$app->request->post())) {
			if ($theTerm->save()) {
				return $this->redirect('terms');
			}
		}

		return $this->render('terms_u', [
			'theTerm'=>$theTerm,
		]);
	}

}
